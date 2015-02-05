<?php

    namespace api;

    class Test
        extends \api\AbstractAPI        
    {
        protected $intId;

        public function __construct( $args = null )
        {
            parent::dispatch($args);
    
            if ( is_numeric($this->getArg(0)) && ($this->intId = $this->getArg(0)) != null )
            {
                if ( $this->init($this->getId()) )
                    $this->addData($this->mapToArray());
                else
                    $this->addError('Unknown Test #'.$this->getArg(0));
            }
            else
            {   // method?
                if ( method_exists($this, 'api_'.$this->getArg(0)) )
                {
                    $strMethod = $this->getArg(0);
                    $this->{'api_'.$strMethod}();
                }
            }
        }
        
        public function init($mixedTest)
        {
            return false;
        }
        
        
        /** Methods **/       
        
        
        /** API Routes **/
        public function api_dosomething()
        {            
            if (count($this->args) >= 2 && is_numeric($this->args[1]))
            {
                $this->addData( array(
                    'callfor' => $this->args[1],
                    'dice' => mt_rand(1,6)
                ) ) );
            }    
            else
                $this->addError('Invalid parameters');
        }


        /** Properties **/
        public function getId() { return intval($this->intId); }       
    }
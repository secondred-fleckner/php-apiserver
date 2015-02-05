<?php

    class design
    {
        public $strTitle = '';
        public $strTitleSub = '';
        public $args = array();
        
        public function design()
        {
            global $global;
            $this->strTitle = $global['title'];

            $this->args = array_slice(explode('/', $_SERVER['REQUEST_URI']), 1);
            
            if ( isset($this->args[0]) && $this->args[0] == 'api' )
            {
                $this->apiManager( array_slice($this->args, 1) );
            }
        }

        protected function apiManager( $args )
        {            
            global $global;
            
            $arrTokens = array();
            if ( $global['access-token'] )
                array_push($arrTokens, $global['access-token']);
            
            if ( $global['mobile-token'] )
                array_push($arrTokens, $global['mobile-token']);
            
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept'.(count($arrTokens)>0 ? ', ' : '').implode(', ', $arrTokens));
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS');    
            header('Accept application/json');
            
            if ($_SERVER['REQUEST_METHOD'] == "OPTIONS") {
                exit;
            }
            
            $_POST = (array)json_decode(file_get_contents('php://input'));

            $objAPI = null;
            try
            {
                if (!isset($args[0]))
                    throw new \lib\Error('Invalid arguements');

                $strClass = '\\api\\'.ucfirst(strtolower($args[0]));

                if (!class_exists($strClass))
                    throw new \lib\Error('Invalid object');

                if (count($args) == 1)
                {   // constructor only
                    $objAPI = new $strClass();
                }
                else if (count($args) > 1)
                {   // constructor with arguements
                    $objAPI = new $strClass( array_slice($args, 1) );
                }

                \lib\Func::getJSONResponse($objAPI->getResponse(), $objAPI->getErrors());
            }
            catch(\Exception $e)
            {
                \lib\Func::getJSONResponse(null, "$e");
            }
        }

        private function getContent()
        {
            global $global;

            $arrData = array();
            $arrData['title'] = $this->strTitle;

            return 'APP SERVER v'.$global['api-version'];
        }

        public function __toString()
        {
            global $BENCHMARK;
            $BENCHMARK->stop('total');
            return $this->getContent()/*.'<div id="Benchmark">'.$BENCHMARK.'</div>'*/;
        }

        public static function wrap($strContent, $strTag, $arrHtmlAttributes=array())
        {
            $strReturn = '<'.$strTag;
            foreach((array)$arrHtmlAttributes as $strKey=>$strValue)
            {
                $strReturn .= ' '.$strKey.'="'.htmlentities($strValue, ENT_QUOTES, 'UTF-8').'"';
            }
            return $strReturn.'>'.$strContent.'</'.$strTag.'>';
        }
    }

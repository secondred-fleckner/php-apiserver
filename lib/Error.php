<?php

    namespace lib;

    class Error
        extends \Exception
    {
        public $strIdentifier = '';

        public $arrParams = array();

        public function __construct($mixedParams) {
            
            if ( is_array($mixedParams) ) {
                $this->strIdentifier = array_shift($mixedParams);
                $this->arrParams = $mixedParams;
            }
            else
            {
                $this->strIdentifier = $mixedParams;  
                $tmp_arrArgs = func_get_args();
                if ($tmp_arrArgs > 1)
                {
                    $this->arrParams = array_slice($tmp_arrArgs, 1);
                } 
            }
        }

        public function getParams() {
            return $this->arrParams;
        }

        public function getIdentifier() { return $this->strIdentifier; }

        public function __toString() {
            $objError = new \lib\Text('error:'.$this->strIdentifier);
            foreach((array)$this->getParams() as $k => $strParam) {
                $objError->addVar($k, $strParam);
            }

            $strError = "$objError";
            return ($strError ? $strError : 'Fehler: '.$this->strIdentifier);
        }
    }
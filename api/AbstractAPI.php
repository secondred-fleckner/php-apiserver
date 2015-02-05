<?php

    namespace api;

    abstract class AbstractAPI
        implements \JsonSerializable
    {
        protected $args;
        protected $errors = array();
        protected $response = array('data' => array());

        public function dispatch($args) {
            $this->args = $args;
        }

        public function getArg($key)
        {
            if (is_array($this->args) && isset($this->args[$key]))
                return $this->args[$key];
            else
                return $this->args;
        }

        public function addData($arrData)
        {
            $this->response['data'] = array_merge($this->response['data'], $arrData);
        }

        public function addError($strError)
        {
            array_push($this->errors, $strError);
        }

        public function getResponse() {
            if (count($this->response['data']) == 0)
                return array_merge($this->response, array('data' => $this->mapToArray()));
            else
                return $this->response;
        }

        public function getErrors() {
            return $this->errors;
        }
        
        public function mapToArray() {
            return $this->response['data'];
        }
                
        public function jsonSerialize() {            
            if (count($this->response['data']) == 0)
                return $this->mapToArray();
            else
                return $this->response['date'];
        }
        
        public static function parseDate($mixedDate)
        {            
            if (!isset($mixedDate))
                throw new \lib\Error('No date specified.');
                
            if (is_numeric($mixedDate)) {
                if ($mixedDate / time() > 100)
                {   // ms timestamp
                    $mixedDate = floor($mixedDate/1000);
                }
                $intDate = intval($mixedDate);
            }
            else
            {
                $intDate = strtotime($mixedDate);
            }
            
            if (!$intDate)
                throw new \lib\Error('The passed date is not in a valid format.');
            
            return $intDate;
        }
    }
<?php

    namespace lib;    

    class AjaxSession
    {
        CONST LIFETIME = 1209600;
        
        private $id;
        private $cookieName;
        
        public function __construct($strCookieName = 'SID_ajx') 
        {
            $this->cookieName = $strCookieName;
            $this->start();
        }
        
        public function __destruct() {
            #$this->destroy();
        }
        
        public function start() {
            if( !headers_sent() ) 
                @session_start();
                
            $this->id = session_id();    
            
            if ($_COOKIE[$this->cookieName]) {
                $this->id = session_id($_COOKIE[$this->cookieName]);
            }
            
            setcookie($this->cookieName, $this->id, time() + ajax_session::LIFETIME);          
        }
        
        public function destroy() {
            session_destroy();
            setcookie($this->cookieName, '', time() - (365*24*3600) );
        }
        
        public function register($strKey, $strValue) { $this->set($strKey, $strValue); }
        public function set($strKey, $strValue) {
            $_SESSION[$strKey] = $strValue;           
        }
        
        public function unregister($strKey) {
            unset($_SESSION[$strKey]);           
        }
        
        public function push($strContext, $objValue) {
            if (!is_array($_SESSION[$strContext]))
                $_SESSION[$strContext] = array();
                
            array_push($_SESSION[$strContext], $objValue);            
        }
        
        public function pop($strContext) {
            if (is_array($_SESSION[$strContext]))
                return array_pop($_SESSION[$strContext]);
            
            return false;            
        }
        
        public function count($strContext) {
            if (is_array($_SESSION[$strContext]))
                return count($_SESSION[$strContext]);
            else
                return 0;
        }
        
        public function __toString() {
            return 'SID: '.$this->id.'<br>CookieName: '.$this->cookieName.'<pre>'.print_r($_SESSION,true).'</pre>';
        }
        
        public function get($strKey) {
            return $_SESSION[$strKey];
        }
    }
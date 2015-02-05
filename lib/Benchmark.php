<?php

    namespace lib;    

    class Benchmark 
    {
        private $arrInformation;
        private $arrTimestamps;
        
        public function __construct() 
        { 
            $this->arrInformation = array();
            $this->arrTimestamps = array(); 
        }
        
        public function start($strKey, $bPublic = false) 
        { 
            global $global;
                        
            if (!$bPublic && !$global['debug'])
                return;
            
            if ( !isset($this->arrTimestamps[$strKey]) )    
                $this->arrTimestamps[$strKey] = microtime(true); 
        }
        
        public function stop($strKey, $bPublic = false) 
        {
            global $global;
            
            if (!$bPublic && !$global['debug'])
                return;
            
            if ( isset($this->arrTimestamps[$strKey]) )
            {
                if ( !isset($this->arrInformation[$strKey]) )
                    $this->arrInformation[$strKey] = 0;
                
                $this->arrInformation[$strKey] += abs($this->arrTimestamps[$strKey] - microtime(true));
                unset($this->arrTimestamps[$strKey]);                
            } 
        }
        
        
        public function getTime($strKey) 
        { 
            return number_format($this->arrInformation[$strKey], 5, '.', '\'').'s'; 
        }
        
        public function __toString() {
            $strReturn = '<table id="benchmark">';     
            
            // finish the unfinished
            foreach((array)$this->arrTimestamps as $strKey => $floatTimestamp) {
                $this->stop($strKey);
            }
                   
            foreach ($this->arrInformation as $strKey => $floatTime) 
            {                
                $strReturn .= '<tr><td class="key"><b>'.$strKey.'</b></td><td>'.number_format($floatTime, 5, '.', '\'').'s</td></tr>';
            }            
            $strReturn .= '<tr><td class="key"><b>mempeak:</b></td><td>'.round((memory_get_peak_usage()/(1024*1024)),3).'mb</td></tr>';
            return $strReturn.'</table>';
        }
    }
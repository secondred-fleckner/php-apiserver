<?php

    namespace lib;
    
    class LogBuilder
    {   
        const SECTION_STYLE_APPENDSECTION   = 'append';
        const SECTION_STYLE_PREPENDSECTION  = 'prepend';
        
        protected $strSeparator;
        protected $strSectionSeparator;
        protected $arrSections;
        protected $strSectionStyle;
        
        public function __construct( $strSeparator = ' ', $strSectionStyle = self::SECTION_STYLE_APPENDSECTION )
        {
            $this->strSeparator     = $strSeparator;
            $this->arrSections      = array('nosection' => array());
            $this->strSectionStyle  = $strSectionStyle;
        }
        
        public function addSection( $strSection )
        {
            if ( !isset($this->arrSections[$strSection]) )
                $this->arrSections[$strSection] = array();
                
            return $this;
        }
        
        public function add($strText, $strSection = null )
        {
            if ( $strSection != null )
                $this->addSection($strSection);
            
            array_push($this->arrSections[($strSection ? $strSection : 'nosection')], $strText);
            
            return $this;
        }
        
        public function setSectionSeparator( $strSectionSeparator = ' ' )
        {
            $this->strSectionSeparator = $strSectionSeparator;
            return $this;
        }
        
        public function addText($strIdent, $strSection = null, $arrVars = array() )
        {
            $objText = new \lib\Text($strIdent, '', \lib\Text::ORDER_RAND);            
            foreach((array)$arrVars as $strKey => $strValue) {
                $objText->addVar($strKey, $strValue);
            }            
            $this->add("$objText", $strSection);
            return $this;
        }
        
        public function __toString()
        {
            $arrReturn = array();
            
            if (count($this->arrSections['nosection']) > 0 && $this->strSectionStyle == self::SECTION_STYLE_APPENDSECTION)
                array_push($arrReturn, implode($this->strSeparator, $this->arrSections['nosection']));
            
            foreach((array)$this->arrSections as $strSection => $arrSection)
            {
                if ($strSection != 'nosection')
                    array_push($arrReturn, implode($this->strSeparator, $arrSection));
            }
            
            if (count($this->arrSections['nosection']) > 0 && $this->strSectionStyle == self::SECTION_STYLE_PREPENDSECTION)
                array_push($arrReturn, implode($this->strSeparator, $this->arrSections['nosection']));            
            
            return implode((isset($this->strSectionSeparator) ? $this->strSectionSeparator : $this->strSeparator), $arrReturn);
        }
    }
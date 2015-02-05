<?php

    namespace lib;

    class Text
    {
        var $content;
        var $ident;
        var $language;
        var $order_type;

        var $html_tag;
        var $html_arrAttributes;
        var $html_arrClasses;
        var $html_id;

        var $amount;

        public $bExpandable;
        public $intCollisionIndex;

        public $bJSready;

        private $bAutoCreate = false;

        private $arrReplacements = array();

        const ORDER_NEWEST = 'newest';
        const ORDER_RAND = 'rand';
        const ORDER_INDEXED = 'order_index';

        /**
         * Represents a database textobject
         * @param <string> $ident text_ident
         * @param <string> $html_tag e.g. 'span', 'div' or 'p', empty for plaintext
         * @param <string> $order_type [newest*|rand|order_index]
         * @param <int> $amount    Anzahl der zurückgegebenen Texte (0 = alle)
         * @param <string> $language default, or 3 Signs for language e.g. GER
         */
        public function  __construct($ident, $html_tag='', $order_type=self::ORDER_NEWEST, $amount=1, $lang_id='de_DE', $bAutoCreate = false)
        {
            global $DB, $AUTH;
            if(is_numeric($ident))
            {
                $oResult = $DB->selectObj('tstrings','id=\''.$ident.'\'', array('ident'));
                $this->ident = $oResult->ident;
            }
            else
            {
                $this->ident = $ident;
            }
            $this->html_tag = $html_tag;

            $this->html_arrAttributes = array();
            $this->html_arrClasses = array();

            $this->language = $lang_id; // at this point should be modified, that it take the user choosen language

            $this->order_type = $order_type;
            $this->amount = $amount;

            $this->bExpandable = false;
            $this->intCollisionIndex = 150;

            $this->bJSready = false;
            $this->bAutoCreate = $bAutoCreate;
        }

        public function addVar($strIdent, $strValue)
        {
            $this->arrReplacements[$strIdent] = $strValue;
        }

        public function Save()
        {
            global $DB;

            if ($this->ident)
            {
                $intAmount = $DB->select('tstrings', 'ident=\''.$this->ident.'\'', array('id'), 'num');
                if ($intAmount <= 0)
                {
                    $DB->insert('tstrings', array('ident'=>$this->ident, 'lang_id'=>$this->language,'text'=>''));
                }
            }
        }

        public function  __toString()
        {
            global $DB, $TEMPLATE, $arrTStrings;

            $text = '';
            if ( isset($arrTStrings[$this->ident]) )
            {   // aus File
                if ( is_array($arrTStrings[$this->ident]) )
                {
                    if($this->order_type == 'rand')
                        $text = $arrTStrings[$this->ident][mt_rand(0,count($arrTStrings[$this->ident])-1)];
                    else
                        $text = $arrTStrings[$this->ident][0];
                }
                else
                {
                    $text = $arrTStrings[$this->ident];
                }
            }
            else
            {   // aus DB
                if($this->order_type == 'newest')
                    $order_type = 'last_change DESC';
                else if($this->order_type == 'rand')
                    $order_type = 'RAND()';
                else if($this->order_type == 'order_index')
                    $order_type = 'order_index ASC';

                $text = $DB->query_one('SELECT text FROM `tstrings` WHERE `ident`=\''.$this->ident.'\' AND `lang_id`=\''.$this->language.'\' ORDER BY '.$order_type.' LIMIT 1;');
            }

            if($this->html_tag)
            {
                $html .= '<'.$this->html_tag.' ';

                // Classes
                $classes = join(' ', $this->html_arrClasses);
                if(count($this->html_arrClasses) > 0)
                {
                    $html .= 'class="'.$classes.'" ';
                }

                // Attributes
                if($this->html_id)
                {
                    $this->html_arrAttributes['id'] = $this->html_id;
                }
                foreach($this->html_arrAttributes as $k=>$v)
                {
                    $html .= $k.'="'.$v.'" ';
                }

                $html .= '>';

                if (!$this->bExpandable || strlen($text) <= $this->intCollisionIndex)
                {
                    $html .= $text;
                }
                else
                {
                    $pretext = substr($text, 0, $this->intCollisionIndex);
                    $posttext = substr($text, $this->intCollisionIndex);
                    $html .= $TEMPLATE->parsePattern('tstring_expandable', array('ID'=>$this->html_id.'_col'.uniqid(), 'PRETEXT'=>$pretext, 'POSTTEXT'=>$posttext, 'EXPAND_TEXT'=>'weiterlesen', 'COLLIDE_TEXT'=>'<div style="margin-top:10px;">Passage ausblenden</div>'));
                }

                $html .= '</'.$this->html_tag.'>';
            }

            return $this->parse( ($this->html_tag ? $html : $text) );
        }


        public function parse($strSnippet, $arrVars = array())
        {
            if (count($arrVars) == 0)
                $arrVars = $this->arrReplacements;

            if (count($arrVars) == 0)
                return "$strSnippet";

            // einfache Variablen Ersetzung
            foreach((array)$arrVars as $strKey => $strValue){
                $strSnippet = str_replace('[['.$strKey.']]', $strValue, $strSnippet);
            }

            // statische Methoden parsen
            $strSnippet = preg_replace_callback( '/\[\[[a-zA-Z0-9_]+\:([^\]]*)\]\]/i',
                                function ($t) {
                                    $t = trim($t[0], '[]');
                                    $tmp_a1 = explode(':', $t, 2);

                                    $key = $tmp_a1[0];
                                    $func = array();

                                    preg_match_all('/([a-zA-Z-0-9_]*)\((.*)\)/i', $tmp_a1[1], $func);
                                    $strFunc = $func[1][0];

                                    if (method_exists('\lib\Text', $strFunc)) {
                                        $tmp_params = explode('|', $func[2][0]);
                                        $params = array();

                                        foreach((array)$tmp_params as $param) {
                                            $paramParts = explode('=>', $param, 2);
                                            if (count($paramParts) == 2) {
                                                $params[$paramParts[0]] = $paramParts[1];
                                            } else {
                                                $params['default'] = $param;
                                            }
                                        }

                                        return $this->$strFunc($key, $params);
                                    }
                                    return '';
                                },
                                $strSnippet );

            // subtexte
            $strSnippet = preg_replace_callback( '/\[\[\=([a-zA-Z0-9_\:\- ]+)\]\]/i',
                                function($t) {
                                    $objText = new \lib\Text($t[1]);
                                    foreach((array)$this->arrReplacements as $strKey => $strValue){
                                        $objText->addVar($strKey, $strValue);
                                    }
                                    return "$objText";
                                },
                                $strSnippet);

            // if konstrukte
            $strSnippet = preg_replace_callback( '/\[\?([0-9<>!=a-zA-Z_]*[^\?])*\?([^\?]*)\?\]/i',
                                        function($t) {
                                            $return = explode('|', $t[2], 2);

                                            $op = preg_replace('/([^=><!]*)/', '', $t[1]);

                                            if ($op)
                                            {
                                                $vals = explode($op, $t[1]);

                                                if (count($vals) == 1) {
                                                    if ($vals[0] != 'false' && $vals[0])
                                                        return $return[0];
                                                    else if (count($return) == 2)
                                                        return $return[1];
                                                    else
                                                        return '';
                                                }

                                                $bCheck = false;
                                                switch($op) {
                                                    case '>': $bCheck = ($vals[0] > $vals[1]); break;
                                                    case '<': $bCheck = ($vals[0] < $vals[1]); break;
                                                    case '<=': $bCheck = ($vals[0] <= $vals[1]); break;
                                                    case '>=': $bCheck = ($vals[0] >= $vals[1]); break;
                                                    case '<>':
                                                    case '!=': $bCheck = ($vals[0] != $vals[1]); break;
                                                    case '==':
                                                    default: $bCheck = ($vals[0] == $vals[1]); break;
                                                }
                                            }
                                            else
                                            {
                                                if ($t[1] != 'false' && $t[1])
                                                    return $return[0];
                                                else if (count($return) == 2)
                                                    return $return[1];
                                                else
                                                    return '';
                                            }

                                            if ($bCheck)
                                                return $return[0];
                                            else if (count($return) == 2)
                                                return $return[1];
                                            else
                                                return '';
                                        },
                                        $strSnippet);

            return $strSnippet;

        }

        public function pluralize($mixedAmount, $arrOptions = array())
        {
            if ( !isset($this->arrReplacements[$mixedAmount]) )
                return '';

            $mixedAmount = $this->arrReplacements[$mixedAmount];

            foreach((array)array_keys($arrOptions) as $mixedValue)
            {
                if (is_numeric($mixedValue) && is_numeric($mixedAmount) && $mixedValue >= $mixedAmount) {
                    return $arrOptions[$mixedValue];
                }
                else if (!is_numeric($mixedValue) && $mixedValue == $mixedAmount) {
                    return $arrOptions[$mixedValue];
                }
            }

            if (isset($arrOptions['default']))
                return $arrOptions['default'];
            else
                return '';
        }

        public static function get($ident, $html_tag='', $order_type=self::ORDER_RAND, $amount=1, $lang_id='default', $bAutoCreate = false)
        {
            $objText = new \lib\Text($ident, $html_tag, $order_type, $amount, $lang_id, $bAutoCreate);
            return "$objText";
        }

        public static function parseText($ident, $arrVars = array())
        {
            $objText = new \lib\Text($ident, '', self::ORDER_RAND);
            foreach((array)$arrVars as $strKey => $strValue) {
                $objText->addVar($strKey, $strValue);
            }
            return "$objText";
        }


        public static function addTString($ident, $content, $language='0', $order_index=0, $system='')
        {
            global $DB;
            $DB->insert('tstrings', array('ident'=>$ident, 'text'=>$content, 'lang_id'=>$language, 'order_index'=>$order_index));
            return $DB->getLastID();
        }

        public static function getPwdFromList($min_signs=4, $max_signs=12)
        {
            global $DB;

            $signs = 0;
            $arr_specialChars = array('!','?','(','§',')','[',']','{','}','/','@','<','>');
            do
            {
                $newPwd = '';
                $tmp_oRow = $DB->selectObj('pwdlist', ' zeichen>=\''.($min_signs-2).'\' AND zeichen<\''.($max_signs-3).'\' ', array(), ' ORDER BY RAND() LIMIT 1;');
                $newPwd = strtolower($tmp_oRow->wort);
                $newPwd .= mt_rand(10,99);
                shuffle($arr_specialChars);
                $newPwd .= $arr_specialChars[0];
                $signs = strlen($newPwd);
            } while ($signs < $min_signs || $signs > $max_signs);

            return $newPwd;
        }
    }
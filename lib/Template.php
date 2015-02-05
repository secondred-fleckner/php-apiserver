<?php

    namespace lib;

    class Template
    {
        var $arrDaten;

        public $template_errors;
        public function  __construct()
        {
            $this->arrDaten         = array();
            $this->template_errors  = array();
        }

        /**
         *
         * @example parsePattern('test_pattern', array('name'=>'Mahdi'), array('date'=>'echo date(\'d.m.Y\');'));
         * @global <type> $DB
         * @param <type> $strIdent
         * @param <type> $arrData
         * @param <type> $arrCode
         * @return <type>
         */
        public function parsePattern($strIdent, $arrData=array(), $arrCode=array()) {return $this->parse($strIdent, $arrData, $arrCode);}
        public function parse($strIdent, $arrData=array(), $arrCode=array())
        {
            global $DB;

            $html = '';

            $oRow = $DB->selectObj('templates', 'ident=\''.$strIdent.'\'');

            if($oRow != null && $oRow->id)
            {
                $html = $oRow->html;
                foreach($arrData as $k=>$v)
                {
                    $html = str_replace("[[$k]]", $v, $html);
                }

                foreach($arrCode as $k=>$v)
                {
                    $pos = strpos($html, "==$k==");

                    $html_old = $html;
                    if($pos !== false)
                    {
                        $html = substr($html_old, 0, $pos);
                        $v = str_replace('echo', '$html.=', $v);
                        eval ($v);
                        $html .= substr($html_old, $pos+(strlen("==$k==")), strlen($html_old)-($pos+(strlen("==$k=="))));
                    }
                }
                return $html;
            }
            else
            {
                return false;
            }
        }

        /**
         * Filtert content-referenz
         * sind weder start noch end limiter angegeben werden alles geklammerte entfernt (),{},[]
         * gibts nur ein end limiter so wir er für alle start limiter angewandt
         * gibts weniger endlimiter als startlimiter, gelten für die zusätzlichen start limiter der letzte endlimiter
         * gibt es gar keinen endlimiter so wird der jeweilige start limiter auch als end limiter fungieren
         * @param <type> &$content
         * @param <type> $start_limiters
         * @param <type> $end_limiters
         */
        public static function stripBracedContent(&$content, $start_limiters=array(), $end_limiters=array())
        {
            if (count($start_limiters) == 0)
            {
                $start_limiters = array('(','{','[');
                $end_limiters = array(')','}',']');
            }

            if (count($start_limiters) != count($end_limiters))
            {
                if (count($end_limiters) == 1)
                {
                    for ($n=1; $n<count($start_limiters); $n++)
                    {
                        array_push($end_limiters, $end_limiters[0]);
                    }
                }
                else if (count($end_limiters) == 0)
                {
                    for ($n=0; $n<count($start_limiters); $n++)
                    {
                        array_push($end_limiters, $start_limiters[$n]);
                    }
                }
                else if (count($end_limiters) < count($start_limiters))
                {
                    for ($n=count($end_limiters); $n<count($start_limiters); $n++)
                    {
                        array_push($end_limiters, $start_limiters[count($start_limiters)-1]);
                    }
                }
            }

            for ($n=0; $n<count($start_limiters); $n++)
            {
                $chk = false;
                do
                {
                    $start_pos = strpos($content, $start_limiters[$n]);
                    if (strlen($content) > $start_pos+1)
                    {
                        $end_pos = strpos($content, $start_limiters[$n], $start_pos+1);

                        if ($start_pos !== false)
                        {
                            $pre_text = substr($content, 0, $start_pos);
                            if ($end_pos !== false)
                                $post_text = substr($content, $end_pos+1, strlen($content)-($end_pos+1));
                            else
                                $post_text = '';
                            $content = $pre_text.$post_text;
                        }
                    }
                    else
                    {
                        $chk = true;
                    }
                } while (strpos($content, $start_limiters[$n]) !== false && !$chk);
            }
            $content = trim($content);
            return $content;
        }

        private function addError($error)
        {
            if ($error)
                array_push($this->template_errors, $error);
        }
    }
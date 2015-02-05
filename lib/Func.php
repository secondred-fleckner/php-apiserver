<?php

    namespace lib;

    class Func
    {
        public static function dice($floatChance = 0.166666666666666667, $floatLuckModifikator = 1, $intEntropy = 10000)
        {
            $floatChance *= $floatLuckModifikator;
            return (mt_rand(0,$intEntropy) < $floatChance*$intEntropy);
        }

        public static function explodeAmountList( $strList )
        {
            $arrReturn = array();

            $tmp = explode(',', $strList);
            foreach ($tmp as $strItem)
            {
                $tmp_arrItem = explode('x', $strItem, 2);

                $intAmount = 1;
                $mixedObject = null;

                if (count($tmp_arrItem) == 1 && $tmp_arrItem[0])
                {
                    $mixedObject = $tmp_arrItem[0];
                }
                else if (count($tmp_arrItem) == 2)
                {
                    $intAmount = $tmp_arrItem[0];
                    $mixedObject = $tmp_arrItem[1];
                }
                else
                {
                    continue;
                }

                if ( !isset($arrReturn[$mixedObject]) )
                    $arrReturn[$mixedObject] = $intAmount;
                else
                    $arrReturn[$mixedObject] += $intAmount;
            }

            return $arrReturn;
        }

        public static function array_add($arrSummaryA, $arrSummaryB)
        {
            if (!is_array($arrSummaryA))
                $arrSummaryA = array();

            if (!is_array($arrSummaryB))
                $arrSummaryB = array();

            foreach ($arrSummaryB as $strKey => $mixedValue)
            {
                if (is_numeric($mixedValue))
                {
                    if ( isset($arrSummaryA[$strKey]) )
                        $arrSummaryA[$strKey] += $mixedValue;
                    else
                        $arrSummaryA[$strKey] = $mixedValue;
                }
                else if (is_array($mixedValue))
                {
                    if ( isset($arrSummaryA[$strKey]) )
                        $arrSummaryA[$strKey] = self::array_add($arrSummaryA[$strKey], $mixedValue);
                    else
                        $arrSummaryA[$strKey] = $mixedValue;
                }
            }
            return $arrSummaryA;
        }

        public static function implodeTextSnippets($arrSnippets = array(), $mixedSeparators = ', ', $strFinalSeparator = ' und ') {
            $strReturn = '';

            $arrSnippets = array_values($arrSnippets);
            for($n=0; $n < count($arrSnippets); $n++)
            {
                $snip = $arrSnippets[$n];
                if ($n == 0) {
                    $strReturn = $snip;
                }
                else if ($strFinalSeparator != null && $n == count($arrSnippets)-1)
                {
                    $strReturn .= $strFinalSeparator.$snip;
                }
                else
                {
                    if (is_array($mixedSeparators)) {
                        self::array_shuffle($mixedSeparators);
                        $strReturn .= $mixedSeparators[0].$snip;
                    } else {
                        $strReturn .= $mixedSeparators.$snip;
                    }
                }
            }

            return $strReturn;
        }

        public static function array_min($array, $key = null, $bReturnPair = false)
        {
            if ($key == null)
                return min($array);

            $tmp_floatMin = null;
            $tmp_mixedPicked = null;
            foreach((array)$array as $strKey => $arrValue)
            {
                if (isset($arrValue[$key]) && (is_null($tmp_floatMin) || $tmp_floatMin > $arrValue[$key]))
                {
                    $tmp_floatMin = $arrValue[$key];

                    if ($bReturnPair)
                        $tmp_mixedPicked = array($strKey => $arrValue);
                    else
                        $tmp_mixedPicked = $arrValue[$key];
                }
            }

            return $tmp_mixedPicked;
        }

        public static function array_shuffle(&$array, $seed=null)
        {
            if ($seed)
                mt_srand($seed);

            for ($i = count($array) - 1; $i > 0; $i--)
            {
                $j = mt_rand(0, $i);
                $tmp = $array[$i];
                $array[$i] = $array[$j];
                $array[$j] = $tmp;
            }
        }

        public static function shuffle_assoc(&$list) {
          if (!is_array($list)) return $list;

          $keys = array_keys($list);
          shuffle($keys);
          $random = array();
          foreach ($keys as $key) {
            $random[$key] = $list[$key];
          }
          $list = $random;
        }

        public static function isValidEmail($strEmail)
        {
            if(preg_match('/^([a-z0-9])+([-a-z0-9\._])*([a-z0-9])*\@([a-z0-9])+([-a-z0-9\._]*([a-z0-9])\.([a-z]){2,})$/i', $strEmail))
                return true;
            else
                return false;
        }

        public static function generateToken()
        {
            return sha1(uniqid('token_'));
        }

        public static function getJSONResponse($arrData = null, $mixedErrors = null, $bPlain = false)
        {
            if (is_array($mixedErrors) && count($mixedErrors) == 0)
                $mixedErrors = null;

            header('Content-Type: application/json');
            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

            if ($bPlain)
                die( json_encode($arrData) );


            $arrReturn = array('ack' => ($mixedErrors ? false : true), 'errors' => $mixedErrors );

            if (is_array($arrData))
                $arrReturn = array_merge($arrReturn, $arrData);

            die( json_encode($arrReturn) );
        }

        public function array_merge_partial($array2, $arrFields)
        {
            $result = array();
            foreach((array)$array2 as $strKey => $value) {
                if ( in_array($strKey, $arrFields) )
                {
                    if ( is_array($value) && is_array($result[$strKey]) )
                    {
                        $result[$strKey] = array_merge($result[$strKey], $value);
                    }
                    else
                    {
                        if (is_numeric($value))
                            $result[$strKey] = floatval($value);
                        else
                            $result[$strKey] = $value;
                    }
                }
            }

            return $result;
        }

        public static function generateName($intMinParts = 2, $intMaxParts = 4)
        {
            $arrDominanteKonsonanten = array('b','n','t','r','k','s','ch','ts','sh','m','w','h','f','y', 'rh', 'st');
            $arrVokale = array('a','u','e','i','o');
            $arrFinaleKonsonanten = array('k','ll','n','x','ok','l','w');

            $intSilben = mt_rand($intMinParts, $intMaxParts);

            $arrName = array();
            while($intSilben > 0)
            {
                array_push($arrName, $arrDominanteKonsonanten[mt_rand(0, count($arrDominanteKonsonanten)-1)] );
                array_push($arrName, $arrVokale[mt_rand(0, count($arrVokale)-1)] );
                $intSilben--;
            }

            if (mt_rand(0,100)%3 == 0)
                array_push($arrName, $arrFinaleKonsonanten[mt_rand(0, count($arrFinaleKonsonanten)-1)] );

            return ( ucfirst( implode('', $arrName) ));
        }

        public static function multi_explode($arrDelimiters, $string)
        {
            if (is_array($arrDelimiters) && count($arrDelimiters) > 0)
            {
                $ary = explode($arrDelimiters[0], $string);
                array_shift($arrDelimiters);
                foreach($ary as $key => $val)
                {
                    $ary[$key] = func::multi_explode($arrDelimiters, $val);
                }
                return  $ary;
            }
            else if ( strlen($arrDelimiters) > 0 )
            {
                return explode($arrDelimiters, $string);
            }
            else
            {
                return array($string);
            }
        }

        public static function getNumericIP($strIP='')
        {
            if (strlen($strIP) < 8)
                $strIP = $_SERVER['REMOTE_ADDR'];
            $arrIP = explode('.', $strIP);
            $ip_numeric = 0;
            for ($n=0; $n<4; $n++)
                $ip_numeric += ($arrIP[$n] * pow(255, 3-$n));
            return $ip_numeric;
        }

        public static function getCountryISOCodeFromIP($strIP='')
        {
            global $DB;
            /*$ip_num = func::getNumericIP($strIP);
            $oCountryLink = $DB->selectObj('lnk_ip_country', ' ip_start_numeric<=\''.$ip_num.'\' AND ip_end_numeric>=\''.$ip_num.'\' ');
            return $oCountryLink->iso_code;*/
            return 'DE';
        }

        public static function getDateSinceAsString($intTS, $bShort=false, $arrUnits=array(), $intTSref=0)
        {
            if (!$intTSref)
                $intTSref = time();

            if (count($arrUnits) == 0)
                $arrUnits = array('y','m','d','H','i','s');

            if (!$bShort)
                $arrText = array(   'y_pl'=>' Jahren','y'=>' Jahr','m_pl'=>' Monaten','m'=>' Monat','d_pl'=>' Tagen','d'=>' Tag','H_pl'=>' Stunden','H'=>' Stunde','i_pl'=>' Minuten','i'=>' Minute','s_pl'=>' Sekunden','s'=>' Sekunde');
            else
                $arrText = array( 'y_pl'=>'J','J'=>'j','m_pl'=>'M','m'=>'M','d_pl'=>'T','d'=>'T','H_pl'=>'h','H'=>'h','i_pl'=>'m','i'=>'m','s_pl'=>'s','s'=>'s');

            $intDiff = $intTSref - $intTS;
            if ($intDiff > 1)
            {
                $y = 0;
                if ($intDiff > 365*86400 && in_array('y', $arrUnits))
                {
                    $y = floor($intDiff/(365*86400));
                    $intDiff -= ($y*365*86400);
                }
                $m = 0;
                if ($intDiff > 30.416666667*86400 && in_array('m', $arrUnits))
                {
                    $m = floor($intDiff/(30.416666667*86400));
                    $intDiff -= ($m*30.416666667*86400);
                }
                $d = 0;
                if ($intDiff > 86400 && in_array('d', $arrUnits))
                {
                    $d = floor($intDiff/86400);
                    $intDiff -= ($d*86400);
                }
                $H = 0;
                if ($intDiff > 3600 && in_array('H', $arrUnits))
                {
                    $H = floor($intDiff/3600);
                    $intDiff -= ($H*3600);
                }
                $i = 0;
                if ($intDiff > 60 && in_array('i', $arrUnits))
                {
                    $i = floor($intDiff/60);
                    $intDiff -= ($i*60);
                }
                $s = 0;
                if ($intDiff > 0 && in_array('s', $arrUnits))
                    $s = round($intDiff,0);

                $arrReturn = array();
                foreach ($arrUnits as $strUnit)
                {
                    if (${$strUnit} > 1)
                        array_push($arrReturn, ${$strUnit}.$arrText[$strUnit.'_pl']);
                    else if (${$strUnit} == 1)
                        array_push($arrReturn, ${$strUnit}.$arrText[$strUnit]);
                }

                if (!$bShort)
                {
                    $s = implode(', ', $arrReturn);
                    return substr($s, 0, strrpos($s, ',')).' und '.substr($s, strrpos($s, ',')+1);
                }
                else
                    return implode(' ', $arrReturn);
            }
            else
            {
                if (!$bShort)
                    return 'wenigen Augenblicken';
                else
                    return 'eben';
            }
        }


        /**
         * thanks to : http://stackoverflow.com/questions/1336776/xss-filtering-function-in-php#answer-1741568
         **/
        public static function xss_clean($data)
        {
            #return $data;
            // Fix &entity\n;
            $data = urldecode($data);
            $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
            $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
            $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
            $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

            // Remove any attribute starting with "on" or xmlns
            $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

            // Remove javascript: and vbscript: protocols
            $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
            $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
            $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

            // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
            $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
            $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
            $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

            // Remove namespaced elements (we do not need them)
            $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

            do
            {
            	// Remove really unwanted tags
            	$old_data = $data;
            	$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
            }
            while ($old_data !== $data);

            // we are done...
            return $data;
        }
    }
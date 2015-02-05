<?php

    namespace lib;

    class UserSettings
    {
        public $arrSettings;

        public function __construct($intUserId=0)
        {
            global $DB, $arrDefaultUserSettings;

            $this->arrSettings = $arrDefaultUserSettings;

            if ($intUserId > 0)
            {
                $objSQL = new dbQ();
                $objSQL->select('*')->from('user_settings')->add('user_id = '.intval($intUserId));
                while($arrRow = $objSQL->fetch())
                {
                    $this->arrSettings[$arrRow['ident']] = $arrRow['value'];
                }
            }
        }

        public function get($strIdent)
        {
            if (isset($strIdent))
                return $this->arrSettings[$strIdent];
            else
                return null;
        }

        public function set($strIdent, $strValue=1, $intUserId = 0)
        {
            global $DB, $AUTH;

            if ($intUserId == 0)
                $intUserId = $AUTH->id;

            $this->arrSettings[$strIdent] = $strValue;
            if ($DB->update('user_settings', array('ident'=>$strIdent,'value'=>$strValue), 'user_id = '.intval($intUserId)) == 0)
            {
                $DB->insert('user_settings', array('ident'=>$strIdent, 'value'=>$strValue, 'user_id'=>intval($intUserId)));
            }
        }
    }
<?php

    namespace lib;

    class User
    {
        public $id;             // user id
        public $nick;           // user nickname
        public $email;          // user email
        public $geschlecht;     // 0 = male, 1 = female
        public $last_action;    // timestamp of last action
        public $registration_date; // timestamp of registration

        public $socketToken;

        public $user_meta;      // array with the user meta information shaped like: $user_meta[$key] = $value;
        public $family_id;

        private $currentSessionId;
        private $superadmin;
        public $arrVisits;

        public $settings = null;

        public function  __construct($user_id, $unactive=false)
        {
            if(is_numeric($user_id) && $user_id > 0)
            {
                $this->initUser($user_id, $unactive);
            }
        }


        const ERROR_USERCREATION_NAMETOOSHORT    = 'usercreation_nametooshort';
        const ERROR_USERCREATION_NAMETOOLONG     = 'usercreation_nametoolong';
        const ERROR_USERCREATION_NAMEEXISTS      = 'usercreation_nameexists';
        const ERROR_USERCREATION_NAMEINVALID     = 'usercreation_nameinvalid';
        const ERROR_USERCREATION_EMAILINVALID    = 'usercreation_emailinvalid';
        const ERROR_USERCREATION_EMAILEXISTS     = 'usercreation_emailexists';
        public static function create( $strNickname, $strEmail, $strPwd, $intGeschlecht = 0, $intAlter = null )
        {
            global $DB, $TEMPLATE;

            $strNickname = trim($strNickname);

            // Email Valide?
            if (!\lib\Func::isValidEmail($strEmail))
                return new \lib\Error(self::ERROR_USERCREATION_EMAILINVALID);

            if ($DB->query_one('SELECT COUNT(*) FROM user WHERE email LIKE \''.$DB->escape($strEmail).'\';') > 0 )
                return new \lib\Error(self::ERROR_USERCREATION_EMAILEXISTS);

            // Username free?
            if ( mb_strlen($strNickname) < 3)
                return new \lib\Error(self::ERROR_USERCREATION_NAMETOOSHORT);

            if ( mb_strlen($strPwd) < 3)
                return new \lib\Error('Password to short.');


            if ( mb_strlen($strNickname) > 12)
                return new \lib\Error(self::ERROR_USERCREATION_NAMETOOLONG);

            if ($DB->query_one('SELECT COUNT(*) FROM user WHERE nick LIKE \''.$DB->escape($strNickname).'\';') > 0 )
                return new \lib\Error(self::ERROR_USERCREATION_NAMEEXISTS);

            if(!preg_match('/^[-a-z0-9ß \.äüößûîêâôáéíóúàìòùèåÅ]+$/i', $strNickname))
                return new \lib\Error(self::ERROR_USERCREATION_NAMEINVALID);

            $strKey = md5(uniqid().rand(0,10000));

            $arrUser = array(   'nick' => $strNickname,
                                'pwd' => $strKey,
                                'currentSessionId' => md5($strKey),
                                'socketToken' => md5(uniqid().rand(0,10000)),
                                'email' => $strEmail,
                                'geschlecht' => intval($intGeschlecht)  );
            if ($intAlter)
                $arrUser['gebdate'] = intval(date('Y')) - intval($intAlter);

            $intUserId = $DB->insert('user', $arrUser);

            $strHashedPassword = md5(strtolower($strNickname).md5($strPwd).( $intUserId%2 == 0 ? strtolower($strEmail) : str_rot13(strtolower($strEmail))));
            $DB->update('user', array('pwd' => $strHashedPassword), 'id = '.intval($intUserId));

            $objMail = new \lib\Email(  $strEmail,
                                        'Registration :: Nordwand App',
                                        "Hallo $strNickname,\ndeine Registrierung ist abgeschlossen.\n\nDein Benutzer: \t $strNickname \nDein Passwort: \t $strPwd \n\nIm Anhang findest du die Teilnahmebedinungen und AGB.\n\nFalls du die App im Browser benutzen willst kannst du sie unter der folgenden Adresse erreichen:\nhttp://app.racoon.io\n\nViel Freude beim Klettern!\ndas Nordwand-Team",
                                        'noreply@'.$_SERVER['HTTP_HOST']);
            $objMail ->attachfile(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Nordwand_App_TeilnahmebedingungenAGB.pdf', 'Nordwand_App_TeilnahmebedingungenAGB.pdf');
            $objMail ->send();

            return $intUserId;
        }



        public static function createUser($nickname, $strCharacterName, $pwd, $pwd2, $email, $gender, $securecodeId=0, $securecodeInput='')
        {
            global $DB, $TEMPLATE;

            // check data
            $ret_arrErrors = array();

            if(strlen($nickname) >= 4 && strlen($nickname) < 32)
            {
                if(preg_match('/^[-a-z0-9ß \.äüößûîêâôáéíóúàìòùèåÅ]+$/i', $nickname))
                {
                    // is nickname already in use?
                    $num = $DB->query('SELECT id FROM '.$DB->db_prefix.'user WHERE nick=\''.$nickname.'\';', 'num');
                    if($num > 0)
                    {
                        $oText1 = new \lib\Text('ajax_itemvalidation_username_exists1');    // UN schon vergeben
                        $oText2 = new \lib\Text('ajax_itemvalidation_username_exists2');    // UN schon vergeben
                        array_push($ret_arrErrors, "$oText1 &quot;$nickname&quot; $oText2");
                    }
                    else
                    {
                        /*$forbidden_phrases = array();
                        $results = $DB->select('banned_username');
                        while($tmp_oRow = mysql_fetch_object($results))
                        {
                            if(stripos($nickname, $tmp_oRow->text) !== false)
                            {
                                array_push($forbidden_phrases, $tmp_oRow->text);
                            }
                        }
                        if(count($forbidden_phrases) > 0)
                        {
                            $oText1 = new \lib\Text('ajax_itemvalidation_username_banned1');    // UN enthält verbotene Phrasen
                            $oText2 = new \lib\Text('ajax_itemvalidation_username_banned2');
                            array_push($ret_arrErrors, "$oText1 &quot;".join('&quot;, &quot;', $forbidden_phrases)."&quot; $oText2");
                        }*/
                    }
                }
                else
                {
                    $oText = new \lib\Text('ajax_itemvalidation_username_specialchars');    // UN enthält Sonder- oder Leerzeichen
                    array_push($ret_arrErrors, "$oText");
                }
            }
            else
            {
                $oText = new \lib\Text('ajax_itemvalidation_username_outofrange');    // UN muss 4 bis 32 Zeichen lang sein.
                array_push($ret_arrErrors, "$oText");
            }

            if ($pwd != $pwd2)
            {
                $oText = new \lib\Text('ajax_itemvalidation_pwdrpt');
                array_push($ret_arrErrors, "$oText");
            }
            if(!preg_match('/^([a-z0-9])+([-a-z0-9\._])*([a-z0-9])*\@([a-z0-9])+([-a-z0-9\._]*([a-z0-9])\.([a-z]){2,})$/i', $email))
            {
                $oText = new \lib\Text('ajax_itemvalidation_email');    // ungültige email adresse
                array_push($ret_arrErrors, "$oText");
            }


            // check securecode if its given
           /* if ($securecodeId > 0)
            {
                $oSecurecode = new securecode($securecodeId);
                if (!$oSecurecode->check($securecodeInput))
                {
                    $oText = new \lib\Text('ajax_itemvalidation_securecode');    // ungültige email adresse
                    array_push($ret_arrErrors, "$oText");
                }
            }*/

            if(count($ret_arrErrors) == 0)
            {
                $token = \lib\Func::generateToken();
                $DB->insert('user', array(  'nick' => $nickname,
                                            'pwd' => md5($pwd),
                                            'currentSessionId' => $token,
                                            'active' => 1,
                                            'email' => $email,
                                            'geschlecht' => $gender));
                $user_id = $DB->getLastID();

                $strHashedPassword = md5(strtolower($nickname).md5($pwd).( $user_id%2 == 0 ? strtolower($email) : str_rot13(strtolower($email))));
                $DB->update('user', array(  'pwd' => $strHashedPassword), 'id = '.$user_id);

                return \game\Character::create($strCharacterName, $gender);

                /*$oLink_confirm = new link($_SERVER['SCRIPT_URI'], array('page'=>'register','action'=>'confirm','id'=>$user_id,'token'=>$token), false);

                $oText_subject = new text('register_confirmmail_subject');
                $strBody = $TEMPLATE->parsePattern('register_confirmmail_body', array('NICKNAME'=>$nickname,'CONFIRM_LINK'=>$oLink_confirm->getLink(),'PWD'=>$pwd));
                $oText_from = settings::EMAIL_INFO;
                mailer::sendMail($email, $oText_subject, $strBody, $oText_from);*/

                #self::sendConfirmationLink($user_id, $pwd);
            }

            return $ret_arrErrors;
        }

        public function activate()
        {
            global $DB;
            $DB->update('user', array('active' => 1), 'id = '.$this->id);
        }

        public function setPassword($pwd)
        {
            global $DB;
            $strHashedPassword = md5(strtolower($this->nick).md5($pwd).( $this->id%2 == 0 ? strtolower($this->email) : str_rot13(strtolower($this->email))));
            $DB->update('user', array(  'pwd' => $strHashedPassword), 'id = '.$this->id);
        }

        /*public static function sendConfirmationLink($id, $pwd='******')
        {
            global $DB, $TEMPLATE;

            $oUser = new user($id, true);
            if ($oUser->id)
            {
                $oLink_confirm = new link($_SERVER['SCRIPT_URI'], array('page'=>'register','action'=>'confirm','id'=>$oUser->id,'token'=>$oUser->currentSessionId), false);
                $oText_subject = new text('register_confirmmail_subject');
                $strBody = $TEMPLATE->parsePattern('register_confirmmail_body', array('NICKNAME'=>$oUser->nick,'CONFIRM_LINK'=>$oLink_confirm->getLink(),'PWD'=>$pwd));
                mailer::sendMail($oUser->getMeta('email'), $oText_subject, $strBody, settings::EMAIL_INFO);
            }
        }

        public function sendAccessData($new_password=true, $nickname=true)
        {
            global $DB, $TEMPLATE;

            $oText_subject = new \lib\Text('login_forgotten_mail_subject');
            if ($new_password)
            {
                $newPwd = \lib\Text::getPwdFromList();
                $newToken = \lib\Func::generateToken();
                $DB->update('user', array('cache'=>md5($newPwd),'currentSessionId'=>$newToken), 'id=\''.$this->id.'\'');

                $oLink_confirm = new link($_SERVER['SCRIPT_URI'], array('page'=>'login','action'=>'forgottenConfirm','id'=>$this->id, 'token'=>substr($newToken, 0, 10)), false);
                $strBody = $TEMPLATE->parsePattern('login_forgottenmail_pwd_body', array('NICKNAME'=>$this->nick, 'NEWPWD'=>$newPwd, 'CONFIRM_LINK'=>$oLink_confirm->getLink()));
            }
            else
            {
                $oLink_confirm = new link($_SERVER['SCRIPT_URI'], array('page'=>'login'), false);
                $strBody = $TEMPLATE->parsePattern('login_forgottenmail_body', array('NICKNAME'=>$this->nick, 'CONFIRM_LINK'=>$oLink_confirm->getLink()));
            }
            \lib\Mailer::sendMail($this->getMeta('email'), $oText_subject, $strBody, settings::EMAIL_INFO);
        }*/

        public static function confirmUser($id, $token)
        {
            global $DB;

            $arrData = array('id'=>$_GET['id'],'token'=>$_GET['token']);
            $DB->securityFilter($arrData);
            $tmp_num = $DB->select('user', 'id=\''.$arrData['id'].'\' AND currentSessionId=\''.$arrData['token'].'\'', array(), 'num');
            if ($tmp_num == 1)
            {
                $DB->update('user', array('active'=>'1'), 'id=\''.$arrData['id'].'\' AND currentSessionId=\''.$arrData['token'].'\'');
                return true;
            }
            else
            {
                return false;
            }
        }

        public function initUser($user_id, $unactive=false)
        {
            global $DB;
            $this->user_meta = array();

            if($unactive)
            {
                $user_info = $DB->selectObj('user', 'id=\''.$user_id.'\'');
            }
            else
            {
                $user_info = $DB->selectObj('user', 'id=\''.$user_id.'\' AND (active=1 OR (active=0 AND deleted=1))');
            }

            if($user_info != null && $user_info->id)
            {
                $this->settings = new UserSettings($this->id);

                $this->id = $user_info->id;
                $this->nick = $user_info->nick;
                $this->email = $user_info->email;
                $this->last_action = $user_info->last_action;
                $this->registration_date = strtotime($user_info->reg_date);
                $this->superadmin = $user_info->superadmin;
                $this->geschlecht = $user_info->geschlecht;
                $this->socketToken = $user_info->socketToken;

                // get users meta information
                $results = $DB->query('SELECT meta_key, meta_value FROM '.$DB->db_prefix.'user_meta WHERE user_id=\''.$this->id.'\';');
                while($oKeyValuePair = mysqli_fetch_object($results))
                {
                    $this->user_meta[$oKeyValuePair->meta_key] = $oKeyValuePair->meta_value;
                }
                $this->currentSessionId = $user_info->currentSessionId;
            }
        }

        public function getId(){
            return $this->id;
        }

        public function isAdmin() {
            return ($this->superadmin) ? true : false;
        }

        public function getMeta($key)
        {
            if($this->user_meta[$key])
                return $this->user_meta[$key];
            else
                return false;
        }

        public function setMeta($key, $value)
        {
            global $DB;
            $tmp_arrData = array('key'=>$key, 'value'=>$value);
            $DB->securityFilter($tmp_arrData);
            if (array_key_exists($tmp_arrData['key'], $this->user_meta) && $this->id)
            {
                $DB->update('user_meta', array('meta_value'=>$tmp_arrData['value']), 'user_id=\''.$this->id.'\' AND meta_key=\''.$tmp_arrData['key'].'\'');
            }
            else if ($this->id)
            {
                $DB->insert('user_meta', array('user_id'=>$this->id,'meta_key'=>$tmp_arrData['key'],'meta_value'=>$tmp_arrData['value']));
            }
        }

        public function setVisit($metaKey, $mixedMetaVales=array())
        {
            global $DB;

            $arrVisit = $this->getVisit($metaKey, $mixedMetaVales, true);
            if ($arrVisit['id'])
                $DB->query('UPDATE '.$DB->db_prefix.'user_visits SET cnt=cnt+1 WHERE id='.$arrVisit['id']);
            else
            {
                $v2 = '';
                if (is_array($mixedMetaVales) && count($mixedMetaVales>0))
                {
                    $v1 = array_shift($mixedMetaVales);
                    $v2 = implode(',', $mixedMetaVales);
                }
                else
                    $v1 = $mixedMetaVales;

                $DB->insert('user_visits', array('user_id'=>$this->id,'meta_key'=>$metaKey,'meta_value1'=>$v1,'meta_value2'=>$v2));
            }
        }

        private function getVisits()
        {
            global $DB;
            $this->arrVisits = array();
            /*
            $tmp_res = $DB->select('user_visits', 'user_id=\''.$this->id.'\'');
            while( $arrRow = mysql_fetch_assoc($tmp_res) )
            {
                $vals = array();
                $vals = explode(',', $arrRow['meta_value2']);
                array_push($vals, $arrRow['meta_value1']);
                array_push ($this->arrVisits, array('id'=>$arrRow['id'], 'key'=>$arrRow['meta_key'],'values'=>$vals,'timestamp'=>strtotime($arrRow['last_change']) ));
            }  */
        }

        public function getAwayTime($asClassName=false)
        {
            $away =  time() - $this->last_action;

            if (!$asClassName)
                return $away;

            if ($away < 15*60)
                return 'online';
            else if($away < 45*60)
                return 'away';
            else
                return 'offline';
        }

        public function getVisit($key, $mixedMetaValues='', $completeInfo=false)
        {
            if (!is_array($mixedMetaValues))
                $mixedMetaValues = array($mixedMetaValues);
            foreach ($this->arrVisits as $arrVisit)
            {
                if ( $arrVisit['key'] == $key )
                {
                    $skip = false;
                    foreach ($mixedMetaValues as $v)
                    {
                        if (!in_array($v, $arrVisit['values']))
                        {
                            $skip = true;
                            continue;
                        }
                    }
                    if ($skip)
                        continue;

                    return ($completeInfo) ? $arrVisit : $arrVisit['timestamp'];
                }
            }
            return false;
        }

        public function getFullName()
        {
            return $this->getMeta('vorname').' '.$this->getMeta('nachname');
        }

        public static function getUserMeta($user_id, $key)
        {
            global $DB;
            $result = $DB->selectObj('user_meta', 'user_id=\''.$user_id.'\' AND meta_key=\''.$key.'\'');
            return $result->meta_value;
        }

        public static function getUserNick($user_id)
        {
            global $DB;
            $result = $DB->selectArray('user', 'id='.$user_id);
            return $result['nick'];
        }

        public function isPermitted($strPermissionShort) {return $this->isAllowed($strPermissionShort); }
        public function isAllowed($strPermissionShort) {
            global $DB;

            if ($this->isAdmin())
                return true;

            $arrPermission = $DB->selectArray('permission', (is_numeric($strPermissionShort) ? 'id = '.intval($strPermissionShort) : 'short = \''.$strPermissionShort.'\''));
            if ($arrPermission['id'] > 0 && $DB->select('lnk_user_permission', 'user_id = '.intval($this->id).' AND permission_id = '.intval($arrPermission['id']),array(),'num') > 0) {
                return true;
            }

            return false;
        }

        public function setPermission($intRechtId, $bState=true) {
            global $DB, $AUTH;

            if ( $AUTH->isAllowed('permissions')){
                if ($this->isAllowed($intRechtId) && !$bState) {
                    $DB->delete('lnk_user_permission', 'user_id = '.$this->id.' AND permission_id = '.$intRechtId);
                }
                if (!$this->isAllowed($intRechtId) && $bState) {
                    $DB->insert('lnk_user_permission', array('user_id'=>$this->id, 'permission_id'=>$intRechtId));
                }
            }
        }


        const LOGSTATE_LOGINFAIL = -1;
        const LOGSTATE_LOGOUT = 0;
        const LOGSTATE_LOGIN = 1;
        public function addLog($intState, $strMessage='', $intAlterUserId = 0)
        {
            global $DB;
            $DB->insert('user_log', array(  'user_id'   => ($intAlterUserId > 0 ? $intAlterUserId : $this->id),
                                            'ip'        => $_SERVER['REMOTE_ADDR'],
                                            'success'   => $intState,
                                            'message'   => $strMessage  )   );
        }
    }
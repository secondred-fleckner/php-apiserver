<?php

    namespace lib;

    class Auth extends User
    {
        var $sid;
        var $right_groups;
        var $rights;

        public function  __construct($sid='')
        {
            global $DB;

            if(!$sid && isset($_COOKIE['sid']))
            {
                $this->sid = $_COOKIE['sid'];
            }
            else
            {
                $this->sid = $sid;
            }
            $this->init();
        }

        public function getWebsocketToken($bNewToken = false) {
            global $DB;

            if (isset($this->socketToken) && $this->socketToken && !$bNewToken)
            {
                return $this->socketToken;
            }
            else
            {
                $strToken = md5($this->sid.rand(0,1000000000));
                $DB->update('user', array('socketToken' => $strToken), 'id = '.$this->id);
                return $strToken;
            }
        }

        private function init()
        {
            global $DB;
            $user_info = $DB->selectObj('user','currentSessionId=\''.$this->sid.'\' AND (active=1 OR (active=0 AND deleted=1))');
            if($user_info != null && is_numeric($user_info->id) && $user_info->id > 0)
            {
                parent::initUser($user_info->id);
                $DB->update('user', array('last_action'=>time()), 'id='.$this->id);
            }
            else
            {   // login as quest
                $this->nick = 'Gast';
                $this->user_groups = array(1);
            }
        }

        public function doLogin($username, $password)
        {
            global $DB;

            $objUser = $DB->selectObj('user', 'nick = \''.$DB->escape($username).'\'');
            if ($objUser != null && $objUser->id > 0)
            {
                $strHashedPassword = md5(strtolower($objUser->nick).md5($password).( $objUser->id%2 == 0 ? strtolower($objUser->email) : str_rot13(strtolower($objUser->email))));

                if ($DB->query_one('SELECT pwd FROM user WHERE id = '.$objUser->id) == $strHashedPassword)
                {
                    parent::initUser($objUser->id);
                    $new_sid = md5(uniqid().$strHashedPassword);
                    $this->sid = $new_sid;
                    $DB->update('user', array('currentSessionId'=>$new_sid,'last_login'=>time()), 'id=\''.$objUser->id.'\'');
                    setcookie('sid', $new_sid, time()+86400, '/');
                    $this->addLog(self::LOGSTATE_LOGIN);
                    return true;
                }
                else
                {
                    $this->addLog(self::LOGSTATE_LOGINFAIL, 'wrong pwd', $objUser->id);
                    return false;
                }
            }
            else
            {
                $this->addLog(self::LOGSTATE_LOGINFAIL, 'nickname "'.$DB->escape($username).'" not found');
                return false;
            }
        }

        public function doLogout()
        {
            global $DB, $AUTH;
            setcookie('sid', '', time()-1, '/');
            $new_sid = md5(uniqid().rand(0,9999999));
            $DB->update('user', array('currentSessionId'=>$new_sid), 'id=\''.$AUTH->id.'\'');
            $this->addLog(self::LOGSTATE_LOGOUT);
            $this->id = 0;
            $this->sid = '';
            $this->init();
        }

        public function register($strNickname, $strPassword, $strEmail, $bFemale, $strBirthday)
        {

        }
    }
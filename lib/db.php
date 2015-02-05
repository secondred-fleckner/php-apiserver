<?php

    namespace lib;

    class db
    {
        const DEFAULT_ENGINE = 'MyISAM';
        const DEFAULT_CHARSET = 'utf8';

        var $db_host;
        var $db_user;
        var $db_password;
        var $db_connection;
        var $db_database;
        var $db_charset;
        var $db_debug;
        var $db_lastQuery;

        public $db_errors;
        public $db_prefix;

        public function __construct($host, $user, $password, $database, $prefix='', $debug=false)
        {
            $debug = false;
            if($debug)
                file_put_contents(BASE.DIRECTORY_SEPARATOR.'~logs'.DIRECTORY_SEPARATOR.'db_'.$_SERVER['REMOTE_ADDR'].'.log', '----- '.date('d.m.Y H:i:s').' - '.$_SERVER['REQUEST_URI'].' -----'.PHP_EOL, FILE_APPEND);


            $this->db_errors        = array();
            $this->db_debug         = $debug;

            $this->db_host          = $host;
            $this->db_user          = $user;
            $this->db_password      = $password;
            $this->db_database      = $database;
            $this->db_prefix        = $prefix;
            $this->db_lastQuery     = '';

            $this->connect();
            $this->selectDatabase();
            $this->setCharset();

            $this->db_charset = mysqli_character_set_name($this->db_connection);
        }

        public function setLastQuery($query)
        {
            $this->db_lastQuery = $query;
            /*if($this->db_debug)
                file_put_contents(BASE.DIRECTORY_SEPARATOR.'~logs'.DIRECTORY_SEPARATOR.'db_'.$_SERVER['REMOTE_ADDR'].'.log', str_replace(array("\r", "\n"), '', date('H:i:s').'->'.$query).PHP_EOL, FILE_APPEND);*/
        }

        public function getLastQuery()
        {
            return $this->db_lastQuery;
        }

        public function query($query, $result_typ='mysql')
        {
            $this->setLastQuery($query);
            $result = $this->db_connection->query($query) or $this->addError();

            switch ($result_typ)
            {
                case 'sql':
                case 'res':
                case 'mysql':
                    return $result;
                    break;
                case 'arr':
                case 'arr_assoc':
                case 'array':
                case 'array_assoc':
                    return mysqli_fetch_array($result, MYSQLI_ASSOC);
                    break;
                case 'arr_both':
                case 'array_both':
                    return mysqli_fetch_array($result, MYSQLI_BOTH);
                    break;
                case 'arr_num':
                case 'array_numeric':
                    return mysqli_fetch_array($result, MYSQLI_NUM);
                    break;
                case 'obj':
                case 'object':
                    return mysqli_fetch_object($result);
                    break;
                case 'num':
                case 'num_fields':
                    return mysqli_num_rows($result);
                    break;
            }
        }

        public function query_row($query)
        {
            return $this->query($query, 'arr');
        }

        public function query_one($query)
        {
            $this->setLastQuery($query);
            $result = $this->db_connection->query($query) or $this->addError();
            $tmpArr = mysqli_fetch_row($result);
            return $tmpArr[0];
        }

        public function query_col($query)
        {
            $arrResult = array();

            $this->setLastQuery($query);
            $result = $this->db_connection->query($query) or $this->addError();

            while(list($value) = mysqli_fetch_row($result)) {
                array_push($arrResult, $value);
            }

            return $arrResult;
        }

        public function select($table, $where=" '1'='1' ", $arrData=array(), $return_type='res', $additional='', $debug=false)
        {
            $this->securityFilter($arrData);
            if(count($arrData) > 0)
            {
                $fields = ' '.join(', ', $arrData).' ';
            }
            else
            {
                $fields = '*';
            }

            if($debug)
                echo 'SELECT '.$fields.' FROM `'.$this->db_prefix.$table.'` WHERE '.$where.' '.$additional.';';

            return $this->query('SELECT '.$fields.' FROM `'.$this->db_prefix.$table.'` WHERE '.$where.' '.$additional.';', $return_type);
        }
        public function selectArray($table, $where=" '1'='1' ", $arrData=array(), $additional='')
        {
            return $this->select($table, $where, $arrData, 'array', $additional);
        }
        public function selectObj($table, $where=" '1'='1' ", $arrData=array(), $additional='')
        {
            return $this->select($table, $where, $arrData, 'obj', $additional);
        }

        public function insert($table, $arrData, $returnQuery=false, $bSecure=true)
        {
            if($bSecure)
                $this->securityFilter($arrData);

            foreach ($arrData as $strKey => $strValue)
            {
                if ( is_string($strValue) && strpos($strValue, 'mysql:') !== false )
                    $arrData[$strKey] = str_replace('mysql:', '', $strValue);
                else if ( is_null($strValue) )
                    $arrData[$strKey] = 'NULL';
                else if ( $strValue instanceof \DateTime )
                    $arrData[$strKey] = '\''.date_format($strValue, 'Y-m-d H:i:s').'\'';
                else if ( is_array($strValue) || is_object($strValue) )
                    $arrData[$strKey] = '\''.serialize($strValue).'\'';
                else if ( is_numeric($strValue) )
                    $arrData[$strKey] = str_replace(',', '.', $strValue);
                else
                    $arrData[$strKey] = '\''.($strValue).'\'';
            }

            $fields = '( `'.join('`, `', array_keys($arrData)).'` )';
            $values = '( '.join(', ', $arrData).' )';

            $this->setLastQuery('INSERT INTO `'.$this->db_prefix.$table.'` '.$fields.' VALUES '.$values.';');

            $this->db_connection->query($this->db_lastQuery) or $this->addError();

            if ($returnQuery)
                return 'INSERT INTO `'.$this->db_prefix.$table.'` '.$fields.' VALUES '.$values.';';
            else
                return $this->getLastID();
        }

        public function update($table, $arrData, $where=" '1'='1' ", $returnQuery=true, $bSecure=true)
        {
            if ($bSecure)
                $this->securityFilter($arrData);

            $sets = array();
            foreach($arrData as $k=>$v)
            {
                if ( is_string($v) && strpos($v, 'mysql:') !== false )
                    $v = str_replace('mysql:', '', $v);
                else if ( is_null($v) )
                    $v = 'NULL';
                else if ( $v instanceof \DateTime )
                    $v = '\''.date_format($v, 'Y-m-d H:i:s').'\'';
                else if ( is_array($v) || is_object($v) )
                    $v = '\''.serialize($v).'\'';
                else if ( is_numeric($v) && (strlen($v) <= 1 || !in_array($v[0], array('+','0'))) )
                    $v = str_replace(',', '.', $v);
                else
                    $v = '\''.($v).'\'';

                array_push($sets, "`$k`=$v");
            }

            $this->setLastQuery("UPDATE `".$this->db_prefix.$table."` SET ".join(',', $sets)." WHERE $where ;");
            $this->db_connection->query($this->db_lastQuery) or $this->addError();

            if ($returnQuery)
                return $this->db_lastQuery;
            else
                return $this->query_one('SELECT COUNT(*) FROM `'.$this->db_prefix.$table.'` WHERE '.$where.';');
        }

        public function delete($table, $where)
        {
            $this->setLastQuery('DELETE FROM `'.$this->db_prefix.$table.'` WHERE '.$where.';');
            $this->db_connection->query($this->db_lastQuery) or $this->addError();
        }

        public function getLastID()
        {
            return mysqli_insert_id($this->db_connection);
        }

        public function closeConnection()
        {
            mysqli_close($this->db_connection);
        }

        public function securityFilter(&$arrData)
        {
            foreach($arrData as $k=>$v)
            {
                if (is_string($v) && strpos($v, 'mysql:') === false)
                {
                    $arrData[$k] = trim(mysqli_real_escape_string($this->db_connection, $v));
                }
            }
        }
        /*public static function securityFilterGlobal(&$arrData)
        {
            foreach($arrData as $k=>$v)
            {
                $arrData[$k] = trim(mysqli_real_escape_string($v));
            }
        }*/

        private function connect()
        {
            $this->db_connection = new \mysqli($this->db_host, $this->db_user, $this->db_password);

            if ($this->db_connection->connect_error) {
                $this->addError();
            }

            #$this->db_connection = mysql_connect($this->db_host, $this->db_user, $this->db_password) or $this->addError();
        }

        private function selectDatabase()
        {
            $this->db_connection->select_db($this->db_database);
            #mysql_select_db($this->db_database, $this->db_connection) or $this->addError();
        }

        public function setCharset($charset=db::DEFAULT_CHARSET)
        {
            mysqli_set_charset($this->db_connection, $charset);
        }

        public function escape( $str )
        {
            return mysqli_real_escape_string($this->db_connection, $str);
        }

        private function addError($error=false)
        {
            if($error)
            {
                array_push($this->db_errors, $error);
            }
            else if ($this->db_connection->connect_error)
            {
                array_push($this->db_errors, $this->db_connection->connect_errno.': '.$this->db_connection->connect_error);
            }
            else
            {
                array_push($this->db_errors, $this->db_connection->errno.': '.$this->db_connection->error);
            }

            if(true || $this->db_debug)
            {
                $msg = $_SERVER['REMOTE_ADDR'].'#'.date('H:i:s').'->'.$this->db_lastQuery.PHP_EOL.'-> Error: '.str_replace(array("\r", "\n"), '', $this->lastError()).PHP_EOL;
                #file_put_contents(BASE.DIRECTORY_SEPARATOR.'~logs'.DIRECTORY_SEPARATOR.'db_'.date('Y_m_d').'.log', $msg, FILE_APPEND);
            }
        }

        public function lastError()
        {
            return $this->db_errors[count($this->db_errors)-1];
        }
    }

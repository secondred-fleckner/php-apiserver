<?php
    
    namespace lib;
    
    class Cache extends \Memcached 
    {
        static private $SERVERS = array( array('127.0.0.1', '11211') );
        static public $memcacheObj = NULL;
        
        static function cache() 
        {
            if (self::$memcacheObj == NULL) 
            {
                if (class_exists('Memcached')) 
                {
                    self::$memcacheObj = new \Memcached;
                    foreach(self::$SERVERS as $server){
                        self::$memcacheObj->addServer($server[0], (isset($server[1]) ? $server[1] : 11211) );
                    }
                } else {
                    return false;
                }
            }
            return self::$memcacheObj;
        }
    
        static function flushCache() 
        {
            if (self::$memcacheObj == NULL) {
                self::cache();
            }
            return self::$memcacheObj->flush();
        }
        
        static function stats() 
        {
            if (self::$memcacheObj == NULL) {
                self::cache();
            }
            return self::$memcacheObj->getExtendedStats();
        }
    }
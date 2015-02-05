<?php

    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

    setlocale(LC_ALL, 'de_DE.UTF-8');
    setlocale(LC_NUMERIC, 'en_US.UTF-8');

    mb_internal_encoding("UTF-8");

    session_start();

    define('BASE', __DIR__);
    define('LIB', BASE.DIRECTORY_SEPARATOR.'lib');
    define('API', BASE.DIRECTORY_SEPARATOR.'api');


    require_once('SplClassLoader.php');
    $objLoader  = new SplClassLoader('lib', '');
    $objLoader  ->setIncludePath(__DIR__);
    $objLoader  ->register();

    require_once('SplClassLoader.php');
    $objLoader  = new SplClassLoader('api', '');
    $objLoader  ->setIncludePath(__DIR__);
    $objLoader  ->register();

    include_once('defaults.php');
    
    $arrModules = array(
        'AUTH' => false,
        'DB' => true,
        'TEMPLATES' => false
    );
        
    $global['debug']		= false;
    $global['locale']		= 'de_DE';
    $global['api-version']  = '0.3';
    $global['title']        = 'API';
    $global['access-token'] = 'X-Api-Token';    // token based auth
    $global['mobile-token'] = 'X-Api-Mobile';   // set for mobile devices in vhost / client
    

    setlocale(LC_TIME, $global['locale']);

$BENCHMARK = new \lib\Benchmark();

    $BENCHMARK->start('total', true);

    if ($arrModules['DB']) {
        $DB = new \lib\db( 'localhost',
    				   'root',
    				   '',
    				   'tgp3_live',
    				   '', $global['debug']);
    }

if (!function_exists('getallheaders'))
{
    function getallheaders()
    {
           $headers = '';
       foreach ($_SERVER as $name => $value)
       {
           if (substr($name, 0, 5) == 'HTTP_')
           {
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
           }
       }
       return $headers;
    }
}

$sessionId = '';
$HEADERS = getallheaders();

if (isset($_REQUEST['session']))
    $sessionId = $_REQUEST['session'];
else if (isset($HEADERS[$global['access-token']]))
    $sessionId = $HEADERS[$global['access-token']];

if ($arrModules['AUTH'])
    $AUTH = new \lib\Auth( $sessionId );

if ($arrModules['TEMPLATES'])
    $TEMPLATE = new \lib\Template();

$MOBILE = isset($HEADERS[$global['mobile-token']]);

/* Language Pack */
$arrTStrings = require_once(BASE.DIRECTORY_SEPARATOR.'tstrings'.DIRECTORY_SEPARATOR.$global['locale'].'.php');

    function encrypt($sData, $secretKey){ $sResult = ''; for($i=0;$i<strlen($sData);$i++){ $sChar = substr($sData, $i, 1); $sKeyChar = substr($secretKey, ($i % strlen($secretKey)) - 1, 1); $sChar    = chr(ord($sChar) + ord($sKeyChar)); $sResult .= $sChar; } return encode_base64($sResult); }
    function decrypt($sData, $secretKey){ $sResult = ''; $sData   = decode_base64($sData); for($i=0;$i<strlen($sData);$i++){ $sChar    = substr($sData, $i, 1); $sKeyChar = substr($secretKey, ($i % strlen($secretKey)) - 1, 1); $sChar    = chr(ord($sChar) - ord($sKeyChar)); $sResult .= $sChar; } return $sResult; }
    function encode_base64($sData){
        return base64_encode($sData);
        $sBase64 = base64_encode($sData); return str_replace('=', '', strtr($sBase64, '+/', '-_'));
    }
    function decode_base64($sData){
        return base64_decode($sData);
        $sBase64 = strtr($sData, '-_', '+/'); return base64_decode($sBase64.'==');
    }

    function aes_encrypt($data_input,$key){
        require_once('crypt.aes.php');
        $objAES = new AES($key);
        return base64_encode( $objAES->encrypt($data_input) );
    }

    function aes_decrypt($data_input, $key){
        require_once('crypt.aes.php');
        $objAES = new AES($key);
        return $objAES->decrypt(base64_decode($data_input));
    }

    function _t($ident, $params = array())
    {
        return \lib\Text::parseText($ident, $params);
    }
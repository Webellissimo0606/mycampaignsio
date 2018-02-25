<?php
class RSA_Handler{

    private static $rsa;
    private static $public_key;
    private static $private_key;

    private static function init(){
        if( null !== self::$rsa && null !== self::$public_key && null !== self::$private_key ){ return; }
        if( ! class_exists('Crypt_RSA', false) ){ require_once APPPATH. 'third_party/phpseclib/Crypt/RSA.php'; }
        
        // require APPPATH . 'modules/auth/controlles/';

        // self::$private_key = file_get_contents( dirname(__FILE__) . '/private_key_1');
        // self::$public_key = file_get_contents( dirname(__FILE__) . '/public_key_2');

        self::$private_key = file_get_contents( APPPATH . 'modules/auth/controllers/private_key_1');
        self::$public_key = file_get_contents( APPPATH . 'modules/auth/controllers/public_key_2');

        self::$rsa = new Crypt_RSA();
        self::$rsa->setHash("sha256");
        self::$rsa->setSignatureMode(CRYPT_RSA_SIGNATURE_PKCS1);

        // Creates and display in screen new apir keys. Only for debugging.
        // $a = self::$rsa->createKey();
        // echo '<textarea cols="80" rows="20">' . $a['privatekey'] . '</textarea>';
        // echo '<textarea cols="80" rows="20">' . $a['publickey'] . '</textarea>';
        // die();
    }

    public static function sign($package){
        self::init();
        self::$rsa->loadKey( self::$private_key );
        return base64_encode( self::$rsa->sign($package) );
    }

    public static function verify($package, $signature){
        self::init();
        self::$rsa->loadKey( self::$public_key );
        $signature = base64_decode($signature);
        return self::$rsa->verify($package, $signature);
    }

    public static function encrypt($package){
        self::init();
        self::$rsa->loadKey( self::$public_key );
        return base64_encode( self::$rsa->encrypt($package) );
    }

    public static function decrypt($package){
        self::init();
        self::$rsa->loadKey( self::$private_key ); 
        return self::$rsa->decrypt(base64_decode($package));
    }
}
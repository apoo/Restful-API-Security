<?php

/**
 * Configuration class, setting up user's public and secret info
 *
 * @author Ali S
 * @version 1.0
 */
class Config{
    private static $credentials = array(
        'email' => 'apoo0@hotmail.com',
        'password' => 'secret'
    );

    public static function getUser(){
        return self::$credentials;
    }
}
?>

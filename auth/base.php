<?php

/**
 * Parent class for HTTP Authorization
 *
 * @author Ali S
 * @version 1.0
 */
class Base {

    /**
     * Method to check if Authorization header has been set
     * @return Boolean
     * @access private
     */
    protected function authHeader(){
        if(array_key_exists('Authorization', $_SERVER)){
            $this->authorization = $_SERVER['Authorization'];
            return true;
        }else if(function_exists('apache_request_headers')){
            $header = apache_request_headers();
            if(array_key_exists('Authorization',$header)){
                $this->authorization = $header['Authorization'];
                return true;
            }
        }
        return false;
    }

    /**
     * Setting HTTP Authorization header
     * @access private
     * @return void
     */
    protected function setAuthHeader($name){
        header("WWW-Authenticate: " . $name ." realm='protected resource'");
        exit();
    }

    /**
     * Decoding a string with Base 64
     * @param String $str;
     * @return String base64_decode($str)
     * @access private
     */
    protected function decode($str){
        return base64_decode($str);
    }
}
?>

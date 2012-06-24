<?php

/**
 * HTTP Basic Authentication class
 *
 * @author Ali S
 * @version 1.0
 */

include_once ('../config/user.php');
class Basic {

    protected $authorization = array();

    public function __construct(){
        $isAuthSet = $this->authHeader();
        if(empty($isAuthSet)){
            $this->setAuthHeader();
        }
    }

    private function setAuthHeader(){
        header("WWW-Authenticate: Basic realm='protected resource'");
        exit();
    }

    private function authHeader(){
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

    private function getBlah(){
        list($email,$secret) = explode(':', $authorization);
    }

    public function getUser(){
        return Config::getUser();
    }
}
?>

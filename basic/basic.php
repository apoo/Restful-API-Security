<?php

/**
 * HTTP Basic Authentication class
 *
 * @author Ali S
 * @version 1.0
 */

include_once ('../config/user.php');
class Basic {

    protected $authorization = null;

    public function __construct(){
        $isAuthSet = $this->authHeader();
        if(empty($isAuthSet)){
            $this->setAuthHeader();
        }
        $data = $this->getInfo();
        $user = $this->getUser($data);
        if(empty($user)){
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

    private function getInfo(){
        if(!empty($this->authorization)){
            list($email,$secret) = explode(':', $this->authorization);
            $data = array('email' => $email, 'secret' => $secret);
            return $data;
        }
        return false;
    }

    public function getUser($data){
        $user = Config::getUser();
        if($user['email'] === $data['email']){
            $secret = $user['secret'];
            $decodeStr = $this->decode($data['secret']);
            if($secret == $decodeStr){
                return true;
            }
        }
        return false;
    }

    public function decode($str){
        return base64_decode($str);
    }
}
?>

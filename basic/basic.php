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

    /**
     * Class initialization
     * @access public
     */
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

    /**
     * Setting HTTP Authorization header
     * @access private
     * @return void
     */
    private function setAuthHeader(){
        header("WWW-Authenticate: Basic realm='protected resource'");
        exit();
    }

    /**
     * Method to check if Authorization header has been set
     * @return Boolean
     * @access private
     */
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

    /**
     * Method to separate email and secret key from Authorization header
     * @return Mixed either $data or false
     * @access private
     */
    private function getInfo(){
        if(!empty($this->authorization)){
            list($email,$secret) = explode(':', $this->authorization);
            $data = array('email' => $email, 'secret' => $secret);
            return $data;
        }
        return false;
    }

    /**
     * Check the sent email and secret data with our user records
     * @param Array $data
     * @return Mixed either Array $user || Boolean false
     * @access private
     */
    private function getUser($data){
        $user = Config::getUser();
        if($user['email'] === $data['email']){
            $secret = $user['secret'];
            $decodeStr = $this->decode($data['secret']);
            if($secret == $decodeStr){
                return $user;
            }
        }
        return false;
    }

    /**
     * Decoding a string with Base 64
     * @param String $str;
     * @return String base64_decode($str)
     * @access private
     */
    private function decode($str){
        return base64_decode($str);
    }
}
?>

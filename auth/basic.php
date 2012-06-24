<?php

/**
 * HTTP Basic Authentication class
 *
 * @author Ali S
 * @version 1.0
 */

include_once ('../config/user.php');
include_once ('base.php');
class Basic extends Base {


    /**
     * Class initialization
     * @access public
     */
    public function __construct(){
        $isAuthSet = $this->authHeader();
        if(empty($isAuthSet)){
            $this->setAuthHeader("Basic");
        }
        $data = $this->getBasic();
        $user = $this->getUser($data);
        if(empty($user)){
            $this->setAuthHeader("Basic");
        }
    }

    public function __destruct() {
        unset($_SERVER['PHP_AUTH_USER']);
        unset($_SERVER['PHP_AUTH_PW']);
    }


    /**
     * Method to separate email and secret key from Authorization header
     * @return Mixed either $data or false
     * @access private
     */
    private function getBasic(){
        if(isset($_SERVER['PHP_AUTH_USER'])){
            $email = $_SERVER['PHP_AUTH_USER'];
            $password = $_SERVER['PHP_AUTH_PW'];
            $data = array('email' => $email, 'secret' => $password);
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
            if($secret == $data['secret']){
                return $user;
            }
        }
        return false;
    }
}
?>

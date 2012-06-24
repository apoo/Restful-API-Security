<?php

/**
 * HTTP Digest Authentication class
 *
 * @author Ali S
 * @version 1.0
 */
include_once ('base.php');
class Digest extends Base {

    public function __construct(){
        $isAuthSet = $this->authHeader();
        if(empty($isAuthSet)){
            $this->setAuthHeader("Digest");
        }
    }


}
?>

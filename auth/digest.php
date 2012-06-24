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
        if(empty($_SERVER['PHP_AUTH_DIGEST'])){
            $this->setAuthHeader("Digest");
        }
        $txt = $this->getDigest();
        $data = $this->parseDigest($txt);
        print_r($data);
        die();
    }

    private function getDigest(){
        if(isset($_SERVER['PHP_AUTH_DIGEST'])){
            $data = $_SERVER['PHP_AUTH_DIGEST'];
        }else{
            $digest = $this->authorization;
            if(strtolower(substr($this->authorization,0,6)) == 'digest'){
                list($digest,$data) = explode(' ', $this->authorization);
            }
        }
        if(empty($data)){
            return false;
        }
        return $data;
    }

    private function parseDigest($txt){
        $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
        $data = array();
        $keys = implode('|', array_keys($needed_parts));
        preg_match_all('@(' . $keys . ')=(?:([\'""])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);
        foreach ($matches as $m) {
            $data[$m[1]] = $m[3] ? $m[3] : $m[4];
            unset($needed_parts[$m[1]]);
        }
        return $needed_parts ? false : $data;
    }
}
?>

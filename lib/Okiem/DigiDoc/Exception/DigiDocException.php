<?php 

namespace Okiem\DigiDoc\Exception;

class DigiDocException extends \RuntimeException{

    const UNKONWN_ERROR = 101;
    const PHONE_NOT_REGISTERED = 201;
    const MID_NOT_READY = 301;
    const CERTIFICATE_REVOKED = 302;
    const NOT_ACTIVATED = 303;
    const NOT_VALID = 304;

    public function __construct($message, $code = 0, \Exception $previous = null){
        parent::__construct($message, $code, $previous);
    }
}
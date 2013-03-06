<?php 

namespace Okiem\DigiDoc\Mobile;

class MobileAuthenticateResult{

    private $sessionCode;
    private $status;
    private $userIDCode;
    private $userGivenname;
    private $userSurname;
    private $userCountry;
    private $userCN;
    private $certificateData;
    private $challengeID;
    private $challenge;
    private $revocationData;

    public function __construct($result = array()){
        $this->parseResult($result);
    }

    public function getSessionCode(){
        return $this->sessionCode;
    }

    public function getChallengeId(){
        return $this->challengeID;
    }

    public function isOK(){
        return ($this->status === "OK");
    }

    private function parseResult($result){
        if(is_array($result)){
            if(isset($result['Sesscode'])){
                $this->sessionCode = $result['Sesscode'];
            }
            if(isset($result['Status'])){
                $this->status = $result['Status'];
            }
            if(isset($result['UserIDCode'])){
                $this->userIDCode = $result['UserIDCode'];
            }
            if(isset($result['UserGivenname'])){
                $this->userGivenname = $result['UserGivenname'];
            }
            if(isset($result['UserSurname'])){
                $this->userSurname = $result['UserSurname'];
            }
            if(isset($result['UserCountry'])){
                $this->userCountry = $result['UserCountry'];
            }
            if(isset($result['UserCN'])){
                $this->userCN = $result['UserCN'];
            }
            if(isset($result['CertificateData'])){
                $this->certificateData = $result['CertificateData'];
            }
            if(isset($result['ChallengeID'])){
                $this->challengeID = $result['ChallengeID'];
            }
            if(isset($result['Challenge'])){
                $this->challenge = $result['Challenge'];
            }
            if(isset($result['RevocationData'])){
                $this->revocationData = $result['RevocationData'];
            }

        }
    }   
}
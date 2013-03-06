<?php 

namespace Okiem\DigiDoc;

use Okiem\Soap\SoapClientBuilder;
use Okiem\Util\PhoneNumberUtil;
use Okiem\Util\ChallengeUtil;
use Okiem\DigiDoc\Exception\DigiDocException;
use Okiem\DigiDoc\Mobile\MobileAuthenticateResult;

class DigiDoc{

    const MOBILE_AUTHENTICATE = "MobileAuthenticate";
    const GET_MOBILE_AUTHENTICATE_STATUS = "GetMobileAuthenticateStatus";

    private $defaults = array(
        /*'wsdl' => '',*/
        /*'server_ca_file' => '',*/
        'client_opts' => array(),
        /*'wsdl.service' => '',*/
        'wsdl.messaging_mode'=> 'asynchClientServer',
        'wsdl.async_configuration' => null,
        'wsdl.return_cert_data' => true,
        'wsdl.return_revocation_data' => false,
        'wsdl.mobile.message_to_display' => "",
        'language' => 'EST',
        'country' => 'EE',
        );

    private $WSDL = null;
    private $options = null;

    public function __construct($options){
        $options = array_merge($this->defaults, $options);
        $this->checkOptions($options);
        $clientOptions = array_merge($options['client_opts'], array('ssl'=>array('server_ca_file'=>$options['server_ca_file'])));
        $soapClientBuilder = new SoapClientBuilder($options['wsdl'], $clientOptions);
        $soapClient = $soapClientBuilder->build();
        $this->WSDL = $soapClient;
    }

    /**
     * Authenticate with Mobile ID
     * @param type $phoneNumber 
     * @param type $idCode
     * @param type $countryCode 
     * @return type
     */
    public function mobileAuthenticate($phoneNumber, $idCode="", $countryCode=""){
        $country = $this->options['country'];
        $phoneNumber = PhoneNumberUtil::addCountryPrefix($phoneNumber, constant("Okiem\Util\PhoneNumberUtil::$country"));
        $args = array(
            'IDCode' => $idCode,
            'CountryCode' => $countryCode,
            'PhoneNo' => $phoneNumber,
            'Language' => $this->options['language'],
            'ServiceName' => $this->options['wsdl.service'],
            'MessageToDisplay' => $this->options['wsdl.mobile.message_to_display'],
            'SPChallenge' => ChallengeUtil::generateChallenge(),
            'MessagingMode' => $this->options['wsdl.messaging_mode'],
            'AsyncConfiguration' => $this->options['wsdl.async_configuration'],
            'ReturnCertData' => $this->options['wsdl.return_cert_data'],
            'ReturnRevocationData' => $this->options['wsdl.return_revocation_data'],
        );
        $args = array_values($args);
        try {
            $result = $this->call(self::MOBILE_AUTHENTICATE, $args);    
        } catch (\Exception $e) {
            switch($e->getMessage()){
                case 301:
                    $message = "Phone number: $phoneNumber not registered in M-ID service";
                    break;
                case 302:
                    $message = "User certificates are revoked or suspended for number: $phoneNumber";
                    break;
                case 303:
                    $message = "M-ID not activated yet for number: $phoneNumber";
                    break;
                default:
                    $message = "Unknown error with number: $phoneNumber";
                    break;
            }
            throw new DigiDocException($message, $e->getCode(), $e);
            
        }
        if(isset($result['Status']) && $result['Status']!="OK" || !isset($result['Status'])){
            throw new DigiDocException($message, DigiDocException::UNKONWN_ERROR);
        }
        
        return new MobileAuthenticateResult($result);
    }


    public function getWSDL(){
        return $this->WSDL;
    }

    protected function call($method, $args){
        return call_user_func_array(array($this->WSDL, $method), $args);
    }

    private function checkOptions($options){
        $requiredOptions = array('wsdl', 'server_ca_file', 'wsdl.service');
        $keys = array_keys($options);
        $invalid = array();
        $isInvalid = false;
        foreach ($requiredOptions as $requiredOption) {
            if(!in_array($requiredOption, $keys)){
                $isInvalid = true;
                $invalid[] = $requiredOption;
            }
        }

        if($isInvalid){
            throw new \InvalidArgumentException(sprintf(
                'The "%s" requires the following options to be set: "\'%s\'".',
                get_class($this),
                implode('\', \'', $invalid)
            ));
        }

        $this->options = $options;

    }
}


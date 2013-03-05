<?php 

namespace Okiem\DigiDoc;

use Okiem\Soap\SoapClientBuilder;


class DigiDoc{

    private $defaults = array(
        /*'wsdl' => '',*/
        /*'server_ca_file' => '',*/
        'client_opts' => array(),
        /*'wsdl.service' => '',*/
        'wsdl.async_configuration'=> 'asynchClientServer',
        );

    private $WSDL = null;

    public function __construct($options){
        $options = array_merge($this->defaults, $options);
        $this->checkOptions($options);
        $clientOptions = array_merge($options['client_opts'], array('ssl'=>array('server_ca_file'=>$options['server_ca_file'])));
        $soapClientBuilder = new SoapClientBuilder($options['wsdl'], $clientOptions);
        $soapClient = $soapClientBuilder->build();
        $this->WSDL = $soapClient;
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

    }

    public function getWSDL(){
        return $this->WSDL;
    }
}


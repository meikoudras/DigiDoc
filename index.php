<?php 
include 'vendor/autoload.php';

use Okiem\Soap\SoapClientBuilder;
use Okiem\DigiDoc\DigiDoc;
#echo getcwd() . '/service_certs.crt';
$opts = array(
    'wsdl' => 'https://www.openxades.org:8443/?wsdl',
    'server_ca_file' => getcwd() . '/service_certs.crt',
    'wsdl.service' => "Testimine",
    'client_opts' => array('debug'=>true),
);
$dd = new DigiDoc($opts);
/*$soapClientBuilder = new SoapClientBuilder('https://www.openxades.org:8443/?wsdl', array('debug'=>true, 'ssl'=> array('server_ca_file'=>getcwd() . '/service_certs.crt')));
$soapClient = $soapClientBuilder->build();*/
try {
    //$result = $dd->getWSDL()->MobileAuthenticate("","","37200007",'EST',"Testimine","","823ffa6d74e4ccd346c5", "asynchClientServer", NULL, true, FALSE); 
    //$result = $dd->getWSDL()->MobileAuthenticate(); 
    $result = $dd->mobileAuthenticate("00007");
    var_dump($result);
} catch (Exception $e) {
    echo $e->getMessage();
    var_dump($e);
}

?>


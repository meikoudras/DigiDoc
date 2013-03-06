<?php 
namespace Okiem\Util;

class PhoneNumberUtil{

    const EE = "372";

    public static function addCountryPrefix($phoneNumber, $countryPrefix = self::EE){
        $phoneNumber = trim(trim($phoneNumber), '+');
        
        if(substr($phoneNumber, 0, 3) != $countryPrefix){
            $phoneNumber = $countryPrefix.$phoneNumber;
        }
        return $phoneNumber;
    }
}
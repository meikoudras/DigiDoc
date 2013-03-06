<?php 

namespace Okiem\Util;

class ChallengeUtil{

    public static function generateChallenge(){
        return substr(md5(rand()), 0, 20).'';
    }
}
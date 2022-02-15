<?php
namespace Extenstions;

use API\Schema\Database;
use API\RemoteDevice;
use API\Auth;
use Model\Token;
//use Model\Device;


class AuthController extends  Auth{

    private function generateRandomString($length = 200)
    {
        $characters = '!@#$%^&*()_+-0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    function createToken($type,$usr,$device){
        $vToken = $this->generateRandomString(100);
        $token = new Token();
        $token->device_id = $device->id;
        $token->type = $type;
        $token->type_id = $usr->id;
        $token->token = $vToken;
        $token->save();
        $this->token = $vToken;
        return $vToken;
    }


}


?>
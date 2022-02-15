<?php
namespace Controller\Authorization;

use API\providers\Request;
use API\providers\S3;
use API\Hash;
use API\providers\Validator;
use API\Schema\Database;
use API\Auth;

use API\providers\Env;
use API\providers\Notifier;

use Model\OTP;
use Model\Operator;
use Model\TokenOperator;

use Extenstions\Helper;

class AuthorizationController {

    
    function AuthTest(Request $request){
        echo "success!";
    }

    function LogOut(Request $request){   
        $authUser = Auth::guard('Operator');
        $token = $authUser->getToken();

        TokenOperator::delete("token='".$token."'");
        $data = array(
            "code" => 200,
            "status" => "success",
            "message" => "Successfully Logged Out!",
            "data"=> null
        );
        return $data;


    }

    function LogIn(Request $request){
      //  echo "Login!";
        $validator = Validator::Rule(function($validator){
            $validator->field("email")->max(100)->notNull();
            $validator->field("password")->max(20)->notNull();
        });
        $v = $validator->validate();
        if(!$v){
            $data = array(
                "code" => 401,
                "status" => "failed",
                "data" => $validator->error()
            );
            return $data;
        }
        else{

            $email = $request->get('email');

            $loginInfo = array(
                "email" => $email,
                "password" => Hash::generateHash($request->get('password'))
            );
           // return $loginInfo;
            $guard  = Auth::guard('Operator');
            $status = $guard->login($loginInfo);
            //return $status;
            if($status) {
                $AuthUser = Auth::guard('user');
                $authUser = $AuthUser->authUser;
                $authUser = array(
                    "id" => $authUser->id,
                    "name" => $authUser->name,
                    "phone" => $authUser->phone
                );

                $data = array(
                    "code" => 200,
                    "status" => "Success",
                    "message" => "Login Successfully",
                    "data" => array(
                        "token" => $guard->getToken(),
                        "data" => $authUser
                    )
                );
                return $data;
            }
            else{
                $data = array(
                    "code" => 400,
                    "status" => "Fail",
                    "message" => "incorrect login information",
                );
                return $data;
            }
        }
    }
}

?>
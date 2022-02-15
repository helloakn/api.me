<?php
namespace Extenstions;
use API\Schema\Database;
use API\providers\S3;
use API\providers\Env;

class Helper{
    static function upload($filepath,$name,$type){
        $key    = Env::get('AWS_S3_KEY');
        $secret = Env::get('AWS_S3_SECRET');
        $region = Env::get('AWS_S3_REGION');
        $bucket = Env::get('AWS_S3_BUCKET');
        $name   = Env::get('AWS_S3_BUCKET_DIRECTORY')."/".$name;

        //print_r($key);
        S3::setAuth($key, $secret);
        S3::setRegion($region);
        S3::setSignatureVersion('v4');
        S3::putObject(S3::inputFile($filepath), $bucket, $name, S3::ACL_PUBLIC_READ, array(), $type);

    }

    static function isTaken($table,$field,$value,$condition=""){
        
        $filter = gettype($value)=="string"?"'".$value."'":$value;
        $cmdString = "SELECT $field FROM $table WHERE $field=$filter ".$condition;
        $result = Database::query($cmdString);
        return ($result?($result->num_rows>0?true:false):false);
    }

    static function isValidEmail($email){
        return preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^",$email);
    }

    static function isValidPhone($ph)
    {
        $ph = str_replace("+959","",$ph);
        $ph = str_replace(" ","",$ph);
        
        
        if(substr($ph,0,2)=="09"){
            $ph  = substr($ph,2);
        }
        if(!is_numeric($ph)){
            return false;
        }
        $operatorDigit = substr($ph,0,2);
        //echo $operatorDigit;exit;
        //echo substr($ph,0,2);exit;
        $validCode = array(
            "20","25","26","40","42","41","44","45","73","88","89", // MPT Operator (9,10,11) digits
            "75","76","77","78","79", // TELENOR Operator
            "95","96","97","98", // OOREDOO Operator
            "66","67","68","69", // MYTEL Operator
            "34" // MEC Operator
        );
        
        if(!in_array($operatorDigit,$validCode)){
            return false;
        }
        else{
            $mptOperator = array(
                "20","25","26","40","42","41","44","45","73","88","89",// MPT Operator (9,10,11) digits  
            );
    
            $telenorOperator = array(
                "75","76","77","78","79" // TELENOR Operator
            );
    
            $ooredooOperator = array(
                "95","96","97","98" // OOREDOO Operator
            );
    
            $mytelOperator = array(
                "66","67","68","69" // MYTEL Operator
            );
    
            $mecOperator = array(
                "34" // MEC Operator
            );
            
            $ph = "09".$ph;
            if(in_array($operatorDigit, $mptOperator)){
                $len = strlen($ph);
                //9,10,11
                return (in_array(strlen($ph),array(9,10,11)));
            }
            else if(in_array($operatorDigit, $telenorOperator)){
                return (in_array(strlen($ph),array(11)));
            }
            else if(in_array($operatorDigit, $ooredooOperator)){
                return (in_array(strlen($ph),array(11)));
            }
            else if(in_array($operatorDigit, $mytelOperator)){
                return (in_array(strlen($ph),array(11)));
            }
            else if(in_array($operatorDigit, $mecOperator)){
                return (in_array(strlen($ph),array(11)));
            }
            else{
                return false;
            }
        }
        
        return "+959".$ph;
    }
    
}


?>
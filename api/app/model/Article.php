<?php

namespace Model;

use API\Schema\Table;

class Article extends Table {

    public static $tableName = "Article";

    protected static $columnName = ['id', 'title', 'image', 'intro', 'description','author_id'];

    public static $primaryKeys = ['id'];

    protected static $autoIncreaseKeys = ['id'];

    protected static $hiddenColumns = [];

    protected $softDelete = true;

    function __construct() {
        parent::__construct();
    }

    public function __call($name, $arguments) {
        
        $name = "_".$name;
        $functionList = get_class_methods($this);
        if(in_array($name,$functionList)){
            if(!$this->defaultTableName){
                $this->defaultTableName =static::$tableName;
                $this->staticPrimaryKey = static::$primaryKeys;
            }
            return $this->$name(
                $this->defaultTableName,
                $this->staticPrimaryKey,
                $arguments);
        }
        else{
            throw new ExceptionHandler(ExceptionHandler::MethodNotFound($this,$name));
        }
    }
    public static function __callStatic($name, $arguments) {
        if(in_array($name,array('find','select'))){
            self::$_instance = new self ;
        }
        else{
            self::$_instance = (self::$_instance === null ? new self : self::$_instance);
        }
        

        self::$_instance->database = Database::Instance();
        $name = "_".$name;
       // if(in_array($name,$functionList)){
            self::$_instance->defaultTableName =static::$tableName;
            self::$_instance->staticPrimaryKey = static::$primaryKeys;
           return self::$_instance->$name(self::$_instance->defaultTableName,
           self::$_instance->staticPrimaryKey,
           $arguments);
      //  }
      //  else{
           // throw new ExceptionHandler(ExceptionHandler::MethodNotFound($this,$name));
     //   }
    }
    

}

?>
<?php
namespace Model;
use API\Schema\Table;
use API\Schema\Database;
use API\providers\ExceptionHandler;

class MobileApp extends Table{

    public static $tableName="MobileApp";

    protected static $columnName=['id', 'name', 'image', 'description','available_on'];

    protected static $primaryKeys = ['id'];

    protected static $autoIncreaseKeys = ['id'];

    protected static $hiddenColumns = [];

    protected $softDelete = true;

    private static $_instance = null;

    function __construct() {
        parent::__construct();
    }

    function _getAllMobileApp(){
        $condition = "deleted_at IS NULL";
        $rows = self::select("id,title,image,description,available_on,created_at")->where($condition)->getAll();
        $data = [];
        if($rows){
            foreach($rows as $k=>$v){
                $v['available_on'] = json_decode($v['available_on']);
                $data[] = $v;
            }
            return $data;
        }
        else{
            return [];
        }
        
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
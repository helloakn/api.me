<?php

namespace Model;

use API\Schema\Table;

class Token extends Table {

    public static $tableName = "Token";

    protected static $columnName = ['id', 'device_id', 'author_id', 'token'];

    public static $primaryKeys = ['id'];

    protected static $autoIncreaseKeys = ['id'];

    protected static $hiddenColumns = [];

    protected $softDelete = true;

    function __construct() {
        parent::__construct();
    }

}

?>
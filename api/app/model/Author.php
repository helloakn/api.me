<?php

namespace Model;

use API\Schema\Table;

class Author extends Table {

    public static $tableName = "Author";

    protected static $columnName = ['id', 'name', 'profile_image', 'email', 'password','status'];

    public static $primaryKeys = ['id'];

    protected static $autoIncreaseKeys = ['id'];

    protected static $hiddenColumns = [];

    protected $softDelete = true;

    function __construct() {
        parent::__construct();
    }

}

?>
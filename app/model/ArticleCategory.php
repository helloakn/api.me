<?php

namespace Model;

use API\Schema\Table;

class ArticleCategory extends Table {

    public static $tableName = "ArticleCategory";

    protected static $columnName = ['id', 'article_id', 'category_id'];

    public static $primaryKeys = ['id'];

    protected static $autoIncreaseKeys = ['id'];

    protected static $hiddenColumns = [];

    protected $softDelete = true;

    function __construct() {
        parent::__construct();
    }

}

?>
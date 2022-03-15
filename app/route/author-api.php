<?php
$route->routePrefix('/author', function($route){
    $route->addroute('post','/article/add/','AuthorSection/article/articleController','Add');
});
?>
<?php
$route->routePrefix('/www', function($route){
    $route->addroute('get','/gethomedata/','homepage/homepageController','list');
    $route->addroute('get','/getarticledetail/{id}/','article/articleController','detail');
    $route->addroute('get','/getcategorydetail/{id}/','category/categoryController','detail');
});
?>
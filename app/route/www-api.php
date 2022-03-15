<?php
$route->routePrefix('/www', function($route){
    $route->addroute('get','/gethomedata/','wwwSection/homepage/homepageController','list');
    $route->addroute('get','/getarticledetail/{id}/','wwwSection/article/articleController','detail');
    $route->addroute('get','/getcategorydetail/{id}/','wwwSection/category/categoryController','detail');
});
?>
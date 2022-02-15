<?php


$route->routePrefix('/admin', function($route){
    #raw
    $route->routePrefix('/raw', function($route){
        $route->addroute('post','/test','raw/rawController','create');
    });
    
});
?>
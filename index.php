<?php

require_once("vendor/autoload.php");

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r)
{
    $r->addRoute("GET", "/", "App\Controllers\ListItemsController@index");
    $r->addRoute("POST", "/create", "App\Controllers\ListItemsController@create");
    $r->addRoute("POST", "/delete/{id}", "App\Controllers\ListItemsController@delete");
});

$httpMethod = $_SERVER["REQUEST_METHOD"];
$uri = $_SERVER["REQUEST_URI"];

if (false !== $pos = strpos($uri, "?"))
{
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0])
{
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        [$handler, $method] = explode("@", $routeInfo[1]);
        $vars = isset($_POST["text"]) ? $_POST : $routeInfo[2];
        $handler::$method(...array_values($vars));
        break;
}

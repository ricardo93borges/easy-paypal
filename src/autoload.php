<?php
spl_autoload_register(function ($class) {
    $path = explode('\\', $class);
    $className = end($path);

    //$path = __DIR__.'//'.str_replace("\\", "/", $class . ".php");
    $path = __DIR__.'/'.$className.".php";

    require_once($path);
});
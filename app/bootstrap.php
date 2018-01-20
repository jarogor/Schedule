<?php

//error_reporting(E_ALL);

date_default_timezone_set('Europe/Moscow');

set_include_path(
    get_include_path()
    .PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].DS.'app'
    .PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].DS.'app'.DS.'controllers'
    .PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].DS.'app'.DS.'models'
    .PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].DS.'app'.DS.'views'
);

spl_autoload_extensions('.php');

spl_autoload_register(function ($class) {
    include($class.'.php');
});

Route::init();

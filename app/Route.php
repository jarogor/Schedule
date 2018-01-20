<?php

class Route
{

    public static function init()
    {
        $path = explode('/', $_SERVER['REQUEST_URI']);

        if($path[1] == 'ajax') {
            $app = new AjaxController($_REQUEST);
        } else {
            $app = new IndexController();
        }
    }
}

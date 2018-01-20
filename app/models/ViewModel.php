<?php

class ViewModel
{

    public $ext;
    public $template;

    public function __construct()
    {
        $this->template = APPROOT.'app'.DS.'views'.DS;
        $this->ext = '.php';
    }


    public function render($layout, $data = null)
    {
        require_once($this->template . $layout . $this->ext);
    }

}

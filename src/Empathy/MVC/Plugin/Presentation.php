<?php

namespace Empathy\MVC\Plugin;

interface Presentation
{
    //  public function __construct();
    public function assign($name, $data);
    public function display($template);
}

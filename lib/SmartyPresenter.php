<?php
  // Copyright 2008 Mike Whiting (mail@mikejw.co.uk).
  // This file is part of the Empathy MVC framework.

  // Empathy is free software: you can redistribute it and/or modify
  // it under the terms of the GNU Lesser General Public License as published by
  // the Free Software Foundation, either version 3 of the License, or
  // (at your option) any later version.

  // Empathy is distributed in the hope that it will be useful,
  // but WITHOUT ANY WARRANTY; without even the implied warranty of
  // MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  // GNU Lesser General Public License for more details.

  // You should have received a copy of the GNU Lesser General Public License
  // along with Empathy.  If not, see <http://www.gnu.org/licenses/>.

namespace Empathy;
require("Smarty/Smarty.class.php");

class SmartyPresenter
{
  private $smarty;

  public function __construct()
  {
    $this->smarty = new \Smarty();
    $this->smarty->debugging = SMARTY_DEBUGGING;
    $this->smarty->template_dir = DOC_ROOT."/presentation";
    $this->smarty->compile_dir = DOC_ROOT."/tpl/templates_c";
    $this->smarty->cache_dir = DOC_ROOT."/tpl/cache";
    $this->smarty->config_dir = DOC_ROOT."/tpl/configs";   

    if(defined('SMARTY_CACHING') && SMARTY_CACHING == true)
      {
	$this->smarty->caching = 1; 
      }

    // assign constants
    $this->assign('NAME', NAME);
    $this->assign('WEB_ROOT', WEB_ROOT);
    $this->assign('PUBLIC_DIR', PUBLIC_DIR);
    $this->assign('DOC_ROOT', DOC_ROOT);
    $this->assign('MVC_VERSION', MVC_VERSION);
  } 

  public function templateExists($template)
  {
    return file_exists($this->smarty->template_dir.'/'.$template);
  }

  public function assign($name, $data)
  {
    $this->smarty->assign($name, $data);
  }

  public function clear_assign($name)
  {
    $this->smarty->clear_assign($name);
  }

  public function switchInternal($i)
  {
    if($i)
      {        
	$this->smarty->template_dir = realpath(dirname(__FILE__));
      }
  }

  public function display($template)
  {
    $this->smarty->display($this->smarty->template_dir.'/'.$template);
  }

  public function loadFilter($type, $name)
  {
    $this->smarty->load_filter($type, $name);
  }

}

?>
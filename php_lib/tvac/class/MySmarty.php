<?php
require_once 'lib/Smarty/Smarty.class.php';

class MySmarty extends Smarty {
    function __construct() {
        parent::__construct();
        $this->setTemplateDir(APP_ROOT_PATH.DS.'tpl')
        ->setCompileDir(APP_ROOT_PATH.DS.'tmp'.DS.'tpl_c');
    }
}
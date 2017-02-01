<?php
/*
 * moonfactory PHP Framework
 *
 * @name ユーザ管理(index)
 *
 * @version 0.1
 * @author kota
 * @copyright 2016-9999 moonfactory,inc
 */

/*
 * 共通INCLUDE
 */
require_once( APP_ROOT_PATH.DS.'controller'.DS.'app_controller.php');
require_once( APP_ROOT_PATH.DS.'dba'.DS.'admin_home.php');
require_once( APP_ROOT_PATH.DS.'class'.DS.'common.php');

class admin_home extends app_controller
{

    public $template;
    public $errors;
    public $frm;

    /**
     * 初期化
     */
    public function init()
    {
        common::checkLogin();

        // TVACニュース取得
        $this->smarty->assign("news", admin_homeDBA::fetchNews());
        $this->smarty->assign("meta_title","ホーム");
        $this->template = "admin_home.html";
    }

    /**
     * バリデーション
     */
    public function validate()
    {

    }

    /**
     * メインロジック
     */
    public function execute()
    {

    }


}

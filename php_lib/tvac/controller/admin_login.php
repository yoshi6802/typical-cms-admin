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
require_once( APP_ROOT_PATH.DS.'dba'.DS.'admin_login.php');
require_once( APP_ROOT_PATH.DS.'class'.DS.'common.php');

class admin_login extends app_controller
{

    public $template;
    public $errors;
    public $frm;

    /**
     * 初期化
     */
    public function init()
    {
        $_SESSION = array();
        $this->frm["username"] = filter_input(INPUT_POST,"username");
        $this->frm["password"] = filter_input(INPUT_POST,"password");
        $this->frm["clientId"] = "tvac";
        $this->frm["status"]   = filter_input(INPUT_GET,"status");

        $status = filter_input(INPUT_GET,"status");

        if($status === "expired") $this->errors[] = "ログイン有効期限が期限切れになりました。";

        $this->smarty->assign("status",$this->frm["status"]);
        $this->smarty->assign("meta_title","ログイン");


        $this->template = "admin_login.html";
    }

    /**
     * バリデーション
     */
    public function validate()
    {
        if($this->frm["username"] === "") $this->errors[] = "ユーザ名が入力されていません";
        if($this->frm["password"] === "") $this->errors[] = "パスワードが入力されていません";
        if(count($this->errors) === 0){
            return true;
        }else{
            return false;
        }
    }

    /**
     * メインロジック
     */
    public function execute()
    {
        // DATA APIへ接続
        $dataApi = new DataApi;

        if( $dataApi->login($this->frm["username"],$this->frm["password"]) ){
            header('Location: /admin_tvac/home/');// 管理サイトHOMEヘ
            exit();
        }else{
             $this->errors[] = "ログインに失敗しました。";
        }
    }


}

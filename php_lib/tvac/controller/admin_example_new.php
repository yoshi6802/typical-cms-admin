<?php
/*
 * moonfactory PHP Framework
 *
 * @name データ操作サンプル
 * @version 0.1
 * @author kota
 * @copyright 2016-9999 moonfactory,inc
 */

/*
 * 共通INCLUDE
 */
require_once( APP_ROOT_PATH.DS.'controller'.DS.'app_controller.php');
require_once( APP_ROOT_PATH.DS.'dba'.DS.'admin_example_new.php');
require_once( APP_ROOT_PATH.DS.'class'.DS.'common.php');

class admin_example_new extends app_controller
{

    public $template;
    public $errors;
    public $frm;

    /**
     * 初期化
     */
    public function init()
    {
        // ログインチェック
        common::checkLogin();

        // クエリ取得
        $this->frm["title"]  = trim(filter_input(INPUT_POST,"title") , " ");
        $this->frm["body"]   = trim(filter_input(INPUT_POST,"body") , " ");

        // テンプレートへデータをassign
        $detail = array();
        if(isset($_POST["title"])) $detail["long_title"] = $this->frm["title"];
        if(isset($_POST["body"]))  $detail["body"]       = $this->frm["body"];
        $this->smarty->assign("data",$detail);

        // テンプレート
        $this->template = "admin_example_new.html";
    }

    /**
     * バリデーション
     */
    public function validate()
    {
        if(Check::StrLength($this->frm["title"]) < 1 ) {
            $this->errors[] = "タイトルは必須です。";
        }
        if(Check::StrLength($this->frm["title"]) > 300 ) {
            $this->errors[] = "タイトルは300文字以内で入力してください。";
        }
        if(Check::StrLength($this->frm["body"]) < 1 ) {
            $this->errors[] = "本文は必須です。";
        }
        if(Check::StrLength($this->frm["body"]) > 5000) {
            $this->errors[] = "本文は5000文字以内で入力してください。";
        }

        return count($this->errors) < 1 ?   true : false;
    }

    /**
     * メインロジック
     */
    public function execute()
    {
        $uid = admin_example_newDBA::insertDetail($this->frm);
        if($uid){
            header("Location: ../edit/?uid={$uid}&status=inserted");
            exit();
        }else{
            $this->errors[] = "新規作成に失敗しました。";
            return false;
        }
    }
}

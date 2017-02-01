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
require_once( APP_ROOT_PATH.DS.'dba'.DS.'admin_example_edit.php');
require_once( APP_ROOT_PATH.DS.'class'.DS.'common.php');

class admin_example_edit extends app_controller
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
        $this->frm["uid"]    = trim(filter_input(INPUT_GET,"uid") , " ");
        $this->frm["status"] = trim(filter_input(INPUT_GET,"status") , " ");
        $this->frm["title"]  = trim(filter_input(INPUT_POST,"title") , " ");
        $this->frm["body"]   = trim(filter_input(INPUT_POST,"body") , " ");

        // uid形式チェック
        if($this->frm["uid"] === "" || !ctype_digit($this->frm["uid"]) ){
            $this->errors[] = "不正なURLです。";
            $this->template = "error.html";
            return false;
        }

        // DBから記事データを取得
        $detail = admin_example_editDBA::fetchDetail($this->frm["uid"]);
        if(!$detail){
            $this->errors[] = "この記事は存在しません。";
            $this->template = "error.html";
            return false;
        }

        // テンプレートへデータをassign
        if(isset($_POST["title"])) $detail["long_title"] = $this->frm["title"];
        if(isset($_POST["body"]))  $detail["body"]       = $this->frm["body"];


        $this->smarty->assign("data",$detail);

        switch($this->frm["status"]){
            CASE "updated" :
                $this->smarty->assign("resultMessage","更新が完了しました。");
                break;
            CASE "inserted" :
                $this->smarty->assign("resultMessage","新規追加が完了しました。");
                break;
        }


        // テンプレート
        $this->template = "admin_example_edit.html";
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
        if(admin_example_editDBA::updateDetail($this->frm)){
            header("Location: ./?uid={$this->frm['uid']}&status=updated");
            exit();
        }else{
            $this->errors[] = "更新に失敗しました。";
            return false;
        }
    }
}

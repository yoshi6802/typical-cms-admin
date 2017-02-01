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
require_once( APP_ROOT_PATH.DS.'dba'.DS.'example.php');
require_once( APP_ROOT_PATH.DS.'class'.DS.'common.php');

class example extends app_controller
{

    public $template;
    public $errors;
    public $post;

    /**
     * 初期化
     */
    public function init()
    {
        $this->post["text"] = filter_input (INPUT_POST,"text");
        $this->template = "example.html";
    }

    /**
     * バリデーション
     */
    public function validate()
    {
        if( mb_strlen($this->post["text"]) > 0){
            return true;
        }else{
            $this->errors[] = "テキストを入力してください。";
            return false;
        }
    }

    /**
     * メインロジック
     */
    public function execute()
    {
        $data["lawyers"] = exampleDBA::fetch_lawyers($this->post);
        $this->smarty->assign("data", $data);
    }


}

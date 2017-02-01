<?php
/**
 *  共有クラス
 */
class common{

    /*
     * SESSION破壊
     */
    public static function destroySession()
    {
        $_SESSION = NULL;
        // セッション用Cookieの破棄
        setcookie(session_name(), '', 1);
        // セッションファイルの破棄
        @session_destroy();
    }

    /*
     * HTTPヘッダ送信
     */
    public static function sendHttpHeader($method,$url,$data=array(),$headers=NULL){
        $query = $data;

        //URLエンコードされたクエリ文字列を生成
        $content = http_build_query($query, '', '&');

        //ヘッダ設定
        $header = array(
            'Content-Type: application/x-www-form-urlencoded', //form送信の際、クライアントがWebサーバーに送信するコンテンツタイプ
        );
        if(!empty($headers)){
            foreach($headers AS $h) $header[] = $h;
        }

        //ストリームコンテキスト設定
        $context = array(
            'http' => array(
                'ignore_errors' => true, //ステータスコードが失敗を意味する場合でもコンテンツを取得
                'header'        => implode("\r\n", $header), //ヘッダ設定
            )
        );
        if($method === "POST"){
            $context["http"]["method"] = "POST";
        }
        if(!empty($data)){
            $context["http"]["content"] =  $content;//送信したいデータ
        }else{
        }

        return file_get_contents($url, false, stream_context_create($context));

    }

    /*
     * PHP内でPOST送信
     * @param (string)送信先URL
     * @param (array)POSTデータ
     * @param (array)header
     * @return POST送信後の返り値
     */
    public static function sendPostData($url,$data=array(),$headers=NULL)
    {
        return self::sendHttpHeader("POST",$url,$data,$headers);

    }
    /*
     * PHP内でGET送信
     * @param (string)送信先URL
     * @param (array)GETデータ
     * @param (array)header
     * @return GET送信後の返り値
     */
    public static function sendGetData($url,$data=array(),$headers=NULL)
    {
        return self::sendHttpHeader("GET",$url,$data,$headers);
    }


    /* ----------------------------------------------------------------
     * このアプリ限定CLASS
     * ---------------------------------------------------------------- */

    /*
     * ログイン確認
     * @return URLリダイレクト OR TRUE
     */
    public static function checkLogin()
    {
        $expriesIn = $_SESSION["expiresIn"];
        $today     = date("Y/m/d H:i:s");

        if(!isset($expriesIn)){
            header('Location: /admin_tvac/?status=relogin');
            exit();
        }

        if( strtotime($today) > strtotime($expriesIn)) {
            header('Location: /admin_tvac/?status=expired');
            exit();
        }

        return true;
    }
}

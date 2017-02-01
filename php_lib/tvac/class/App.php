<?php

class App {
    /**
     * URIからAPP_PATH_NAME直後の文字列を見てコントローラーの文字列を返します。
     * @return string|null コントローラー文字列またはnull
     */
    public static function dispatch($uri) {
        return self::getControllerName($_SERVER["REQUEST_URI"]);
    }

    /**
     *
     */
    public static function referer(){
        return self::getControllerName($_SERVER["HTTP_REFERER"]);
    }

    public static function redirect($url){
        header("Location: {$url}");
        exit();
    }

    private static function getControllerName($uri){
        $uriParts = explode('/', $uri);
        foreach( $uriParts as $i => $e )
        {
            if( $e === APP_PATH_NAME && isset($uriParts[$i+2]) && $uriParts[$i+2] )
            {
                return self::replaceQuery($uriParts[$i+2]);
            }
        }
        return false;
    }

    private static function replaceQuery($s)
    {
        $s = explode('?',$s);
        return $s[0];
    }

    public static function error($errorMessage) {
        global $smarty;
        $smarty->assign('errorMessage', $errorMessage);
        $smarty->display('error.html');
        exit;
    }

    public static function createURL($path) {
        return $path;
    }

    /**
     *  セッション取得用。Warning抑止のため$_SESSIONは使用せずにこのメソッドを通じして取得して下さい。
     */
    public static function session($key,$default=null)
    {
        return isset($_SESSION[$key]) ?  $_SESSION[$key] : $default;
    }

    /**
     *  GETパラメータ取得用。Warning抑止のため$_GETは使用せずにこのメソッドを通じして取得して下さい。
     */
    public static function GetParam($key, $default=null)
    {
        return array_key_exists($key, $_GET) ?  $_GET[$key] : $default;
    }

    /**
     *  POSTパラメータ取得用。Warning抑止のため$_POSTは使用せずにこのメソッドを通じして取得して下さい。
     */
    public static function PostParam($key, $default=null)
    {
        return array_key_exists($key, $_POST) ?  $_POST[$key] : $default;
    }

    /**
     * 子画面から戻ってきた場合に検索条件を復元する
     *
     * 子画面から戻ってきた場合に検索条件を復元する
     * 子画面か否かは戻り元でurlに?back=onを付加して判断する
     *
     * @todo このフレームワークに強く依存しているので不適当であれば別の場所に移動すること
     * @todo 各画面ごとにsessionが保存されるので無駄であればsession削除をもう少しエレガントに
     */
    public static function ResumeWhenBack(){
        $controller = App::dispatch($_SERVER['REQUEST_URI']);
        $sessionKey = 'when_back_'.$controller;
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && App::GetParam('back')=='on'){
            // 戻りの場合
            $_POST = $_SESSION[$sessionKey];
            $_SERVER['REQUEST_METHOD'] = 'POST';
        }elseif($_SERVER['REQUEST_METHOD'] == 'POST'){
            // postの場合、普通にexecuteが動作しているのでその時の条件をsessionに保存
            $_SESSION[$sessionKey] = $_POST;
        }else{
            // 上記以外の場合はsessionを削除する
            unset($_SESSION[$sessionKey]);
        }
    }

    /**
     * CSRF対策用のTokenの発行、チェックを行います
     * Postフォームでは<input type'hidden' name='token' value='{token}'>が必須
     */
    public static function CheckCSRF($smarty)
    {
        //TODO emomoto
        // ブラウザバックした場合にエラーとなるので少し改良したほうがよい
        // CSRFエラーのロギング


        // POSTリクエストに場合はセッションのTokenとPostパラメータのトークン値をチェックする
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if( App::PostParam('token') != App::session('token') )
            {
                //App::error("正規のページより再度アクセスしてください");
                //TODO enomoto ロギング
                //exit;
            }
        }

        // Tokenを再発行する
        //$rand              = file_get_contents(APP_ROOT_PATH.'/config/urandom', false, NULL, 0, 24);
        //$token             = base64_encode($rand);
        $token             = base64_encode(md5(uniqid(rand(), true)));
        $_SESSION['token'] = $token;
        $smarty->assign('token', $token);
    }

    /**
     *  チェックボックス用のonをchecked(boolean)に変換します
     */
    public static function checkboxUtil( $list , $ids=null )
    {
        $hash = array();
        if($ids){
            foreach ($ids as $id)$hash[$id] = true;
        }

        foreach($list as $i => $d)
        {
            if(is_array($d) && isset($d[0]))
            {
                $list[$i] = self::checkboxUtil($d,$ids);
            }else{
                $d['checked'] = isset($hash[$d['id']]);
                $list[$i]     = $d;
            }
        }
        return $list;
    }

    /**
     *  日付のフォーマット
     */
    public static function formatDate($s)
    {
        $d    = new DateTime($s);
        return $d->format('m月d日');
    }

    /**
     *  曜日のフォーマット
     */
    public static function formatDay($s)
    {
        $week = array('日', '月', '火', '水', '木', '金', '土');
        $d    = new DateTime($s);
        $w    = $d->format('w');
        return $week[$w];
    }

    /*
     * 都道府県リスト
     */

    public static function get_prefs()
    {
        return array(
            "北海道",
            "青森県",
            "岩手県",
            "宮城県",
            "秋田県",
            "山形県",
            "福島県",
            "茨城県",
            "栃木県",
            "群馬県",
            "埼玉県",
            "千葉県",
            "東京都",
            "神奈川",
            "新潟県",
            "富山県",
            "石川県",
            "福井県",
            "山形県",
            "長野県",
            "岐阜県",
            "静岡県",
            "愛知県",
            "三重",
            "滋賀県",
            "京都府",
            "大阪府",
            "兵庫県",
            "奈良県",
            "和歌山",
            "鳥取県",
            "島根県",
            "岡山県",
            "広島県",
            "山口県",
            "徳島県",
            "香川県",
            "愛媛県",
            "高知県",
            "福岡県",
            "佐賀県",
            "長崎県",
            "熊本県",
            "大分県",
            "宮城県",
            "鹿児島県",
            "沖縄県",
            "海外"
        );
    }

    public static function formatTime($s)
    {
        return substr($s, 0,2).':'.substr($s,2);
    }

    public static function UID()
    {
        return self::session('login',999999999);
    }

    public static function selfURL()
    {
        return $_SERVER['REQUEST_URI'];
    }
}

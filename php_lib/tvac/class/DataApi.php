<?php
/**
 *  データAPI接続クラス
 */
class DataApi{
    public $user;

    function __construct()
    {

    }
    /*
     * ログイン
     * @param username
     * @param password
     * @return BOOL
     */
    public function login($username,$password)
    {
        if(empty($username) && empty($password)) return false;

        $auth = $this->fetchAuthentication($username,$password);
        if($auth === false && !isset($auth["accessToken"])) return false;

        // アクセストークンからユーザ情報取得
        $user = $this->fetchUser($auth["accessToken"]);

        if(isset($user["id"])) {
            $today = date("Y-m-d H:i:s");
            $_SESSION["auth"] = $auth;
            $_SESSION["user_id"]   = $user["id"];
            $_SESSION["expiresIn"] = date("Y-m-d H:i:s",strtotime($today ."+".$auth["expiresIn"]." second"));
            return true;
        }else{
            return false;
        }

    }
    /*
     * 認証情報取得
     * @param username
     * @param password
     * @return 認証情報 OR FALSE
     */
    function fetchAuthentication($username,$password)
    {
        $frm = array(
            "username" => $username,
            "password" => $password,
            "clientId" => "tvac"
        );
        $headers = array(
            "Content-Type: application/x-www-form-urlencoded",
        );

        $json  = common::sendPostData(DATA_API_URL."authentication",$frm,$headers);
        $auth= json_decode($json,TRUE );

        if(isset($auth["accessToken"])) {
            return $auth;
        }else{
            return false;
        }
    }

    /*
     * ユーザ情報取得
     * @param accessToken
     */
    function fetchUser($token)
    {
        $headers = array(
            "Content-Type: application/x-www-form-urlencoded",
            "X-MT-Authorization: MTAuth accessToken=".$token,
        );

        $data = array();
        $json  = common::sendGetData(DATA_API_URL."users/me",$data,$headers);
        $return = json_decode($json,TRUE );

        if(isset($return["error"])){
            return false;
        }else{
            return $return;
        }
    }


    /*
     * ログアウト
     */
    function logout(){
        common::destroySession();
        header('Location: /admin_tvac/?status=logout');
        exit();
    }


}

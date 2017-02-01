<?php
/*
 * moonfactory PHP Framework
 * PHP Version 5.5 or above
 *
 * init.php
 *
 * @version 0.3
 * @author kota
 * @copyright 2016-9999 moonfactory,inc
 */
date_default_timezone_set('Asia/Tokyo');
/**
 * このアプリケーションの基点となるパス。ドキュメントルートとは意味が異なります。
 * @var string
 */
define('APP_ROOT_PATH', dirname(__FILE__));

ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.APP_ROOT_PATH);
/**
 * セッション関連設定
 */
//session_cache_limiter('nocache');
session_save_path(APP_ROOT_PATH.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'session');
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 1);
ini_set('session.gc_maxlifetime', 24*60*60);
ini_set('session.cookie_lifetime',  24*60*60);
session_set_cookie_params( 24*60*60 ,'/');

session_start();



require_once 'class/MySmarty.php';
$smarty = new MySmarty();

/*
 * 開発・本番コンフィグ
 */
if ($_SERVER['SERVER_NAME'] === 'localhost') {
    /* * -----------------------------------------
     * 開発用コンフィグ
     * ----------------------------------------- */
    ini_set('display_errors',1);
    error_reporting(E_ALL);
    // 開発、テスト用ファイルの読み込み
    $CONFIG = require_once APP_ROOT_PATH.'/config/config_dev.php';
    define('DEV_MODE',true);
    // このアプリケーションのパス名。
    define('APP_PATH_NAME', 'TVAC_APP');
    // ルートURL
    define('ROOT_URL', 'http://localhost:8000/www/htdocs/');
    define('SITE_TITLE','サイトタイトルMETA');
    // Acunetix mode : true = メールの送信を無効化する
    define('ACUNETIX_MODE',false);

    // DATA API URL
    define('DATA_API_URL',ROOT_URL.'/mt/mt-data-api.cgi/v3/');

} elseif($_SERVER['SERVER_NAME'] === '') {
    /* * -----------------------------------------
     * 本番用コンフィグ
     * ----------------------------------------- */
    ini_set('display_errors',0);
    error_reporting(0);
    // 本番用configファイルの読み込み
    $CONFIG = require_once APP_ROOT_PATH.'/config/config.php';
    define('DEV_MODE',false);
    // このアプリケーションのパス名。
    define('APP_PATH_NAME', 'training_search');
    // ルートURL
    define('ROOT_URL', '/');
    define('SITE_TITLE','サイトタイトルMETA');
    // Acunetix mode
    // true = メールの送信を無効化する
    define('ACUNETIX_MODE',false);
}else{
    die('init config error!');
    exit();
}
//クリックジャッキング対策
header('X-Frame-Options: SAMEORIGIN');

$smarty->assign('site_title' , SITE_TITLE);

require_once 'class/App.php';
require_once 'class/DBAccessor.php';
require_once 'class/Check.php';
require_once 'dba/common.php';
require_once 'class/common.php';
require_once 'class/DataApi.php';


/*
 * ルーティング
 */
$routing     = require_once 'config/routing.php';
$uri         = $_SERVER['REQUEST_URI'];
$explodedUri = explode("?",$uri);
$explodedUri = explode("#",$explodedUri[0]);
$uri    = $explodedUri[0];


if(!empty($controller)){
    // コントローラ指定あり
}elseif(isset($routing[$uri])){
    $controller = $routing[$uri];
}else{
    $controller =  false;
}

// CSRFチェックを行います
APP::CheckCSRF($smarty);
// コントローラを実行します
try{
    if( !file_exists(APP_ROOT_PATH.DS.'controller'.DS.$controller.'.php'))
    {
        header('HTTP/1.1 404 Not Found');
        App::error('ページが存在しません');
        //throw new Exception('Not Found Action');
    }

    require_once APP_ROOT_PATH.DS.'controller'.DS.$controller.'.php';

    $ins = new $controller();

    $ins->smarty = $smarty;
    $ins->errors = array();

    $iRedirect = false;
    if($ins->init($smarty) !== false && $_SERVER['REQUEST_METHOD'] == 'POST' && $ins->validate($smarty) )
    {
        $isRedirect = $ins->execute($smarty);
    }else{
        $isRedirect = false;
    }

    // ブラウザ判定
    $ua = $_SERVER['HTTP_USER_AGENT'];
    // ブラウザ判別
    if(strpos($_SERVER['HTTP_USER_AGENT'],'Chrome')){
        $smarty->assign('browser' , 'chorome');
    }elseif(strpos($_SERVER['HTTP_USER_AGENT'],'Firefox')){
        $smarty->assign('browser' , 'firefox');
    }else{
        $smarty->assign('browser' , 'other');
    }

    // 共通パラメータを設定します。
    $smarty->assign(array
        (
             'errors'     => $ins->errors
            ,'message'    => $ins->message
        ));
    // テンプレートを描画します
    if( $isRedirect == false )
    {
        $smarty->display($ins->template);
    }
}catch(Exception $ex)
{
    var_dump($ex);
    $smarty->assign('trace' , $ex->getTraceAsString());
    $smarty->display('error.html');
}


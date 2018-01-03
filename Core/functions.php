<?php

/**
 * ログイン状態を返す
 * @global array $app_user_list
 * @return boolean
 */
function isLogin() {
    global $app_user_list;

    $result = false;

    if (isset($_SESSION[SESSION_KEY]['auth']['app_login_id'])) {
        $result = true;
    }

    return $result;
}

/**
 * セッションの値を取得
 * @param string $key
 * @return
 */
function getSession($key='') {
    $result = false;

    // キーが設定されていた場合
    if (!empty($key)) {
        if (isset($_SESSION[SESSION_KEY][$key]) && !empty($_SESSION[SESSION_KEY][$key])) {
            $result = $_SESSION[SESSION_KEY][$key];
        }
    } else {
        // 全て返す
        $result = $_SESSION[SESSION_KEY];
    }


    return $result;
}

/**
 * セッションに登録
 * @param string $key
 * @param string $value
 */
function setSession($key, $value) {
    $_SESSION[SESSION_KEY][$key] = $value;
}

/**
 * 画面スコープのデータを取得
 *
 * @param string $div
 * @param string $key
 * @return stiring | array
 */
function getFlush($div, $key='') {
    $result = false;

    if (!empty($key)) {
        if (isset($_SESSION[SESSION_KEY]['flush'][$div][$key])
                && !empty($_SESSION[SESSION_KEY]['flush'][$div][$key])) {
            $result = $_SESSION[SESSION_KEY]['flush'][$div][$key];
        }
    } elseif(isset($_SESSION[SESSION_KEY]['flush'][$div])) {
        $result = $_SESSION[SESSION_KEY]['flush'][$div];
    }

    return $result;
}

function setFlush($div, $key, $value) {
    $_SESSION[SESSION_KEY]['flush'][$div][$key] = $value;
}

/**
 * 画面スコープのデータを全て削除
 */
function fulshDestroy() {
    unset($_SESSION[SESSION_KEY]['flush']);
}

/**
 * 成功メッセージ登録
 * @param type $key
 * @param type $value
 */
function setSuccessMsg($key, $value) {
    setFlush('success', $key, $value);
}

function getSuccessMsg($key='') {
    return getFlush('success', $key);
}

function setErrorMsg($key, $value) {
    setFlush('error', $key, $value);
}

function getErrorMsg($key='') {
  if (getFlush('error', $key) !== false)
  {
    return getFlush('error', $key);
  } else {
    return 0;
  }
}

/**
 * URL作成
 * @param string $controller
 * @param string $action
 * @return string URL
 */
function url($controller, $action, $params = array()) {

    $url = 'index.php?controller=' . $controller . '&action=' . $action;

    foreach ($params as $key => $param) {
        $url .= '&'. $key . '=' . $param;
    }

    if (isset($_SESSION[SESSION_KEY]['token'])) {
        // tokenを追加
        $url .= '&token=' . $_SESSION[SESSION_KEY]['token'];
    }

    return $url;
}

/**
 * CSRFチェック
 * @param string $token
 * @return boolean
 */
function checkCsrf($token) {
    $result = false;

    if (isset($_SESSION[SESSION_KEY]['token'])
            && ($_SESSION[SESSION_KEY]['token'] === $token)) {
        $result = true;
    }

    return $result;
}

/**
 * CSRFチェック用のトークンを作成
 */
function createToken() {
    // 新たなトークンを生成
    $_SESSION[SESSION_KEY]['token']
            = str_shuffle(md5($_SESSION[SESSION_KEY]['auth']['app_password'] . SALT_KEY));
    $_SESSION[SESSION_KEY]['token']
            .= str_shuffle(md5($_SESSION[SESSION_KEY]['auth']['app_login_id'] . SALT_KEY));
}

/**
 * 選択状態
 * @param string $controller
 * @return string
 */
function active($controller) {
    $result = (string)'';

    if ($_GET['controller'] === $controller) {
        $result = 'active';
    }

    return $result;
}

/**
 * 共通部品の読み込み
 * @return void
 */
function element($element_name, $array=array()) {

    foreach ($array as $key => $value) {
        $$key = $value;
    }

    require(dirname(__FILE__) . '/View/Element/' . $element_name . '.php');
}

/**
 * モデルのインスタンスを取得
 * @param string $model_name
 * @return object
 */
function getModelInstans($model_name) {
    $model_name = ucwords($model_name) .'Model';

    require_once (dirname(__FILE__) . '/Model/' . ucwords($model_name) . '.php');

    return new $model_name;
}

/**
 * リダイレクト
 * @param string $url
 */
function redirect($url= '') {
    if (empty($url)) {
        $url = $_SERVER["REQUEST_URI"];
    }

    header('Location: ' . $url, true , 302);
    exit;
}

function getToken() {
    $token = getFlush('security', 'token');

    if (!isset($token) || empty($token)) {
            $token = getOriginalHash(str_shuffle($token));
            setFlush('security', array('token' => $token));
    }

    return $token;
}

/**
 * オリジナルのハッシュ値を生成する
 * @param string $str
 * @param string $salt_key  主にインストールの時利用
 * @return string
 */
function getOriginalHash($str, $salt_key='') {

    if (empty($salt_key)) {
        // 設定ファイルから取得
        $salt_key = SALT_KEY;
    }

    for ($i=0; $i < 5; $i++) {
        $str .= $salt_key;
    }

    return md5('sdm' . $str);
}

/**
 * IP許可リストのIPか
 * @return boolean
 */
function checkWhiteListIP() {
    global $ip_white_list;

    // アクセスしてきたIP
    $remote_ip = $_SERVER['REMOTE_ADDR'];

    foreach ($ip_white_list as $accept) {
        list($accept_ip, $mask) = explode('/', $accept);

        // サブネットマスクが指定されている場合
        if (isset($mask)) {
            $accept_long = ip2long($accept_ip) >> (32 - $mask);
            $remote_long = ip2long($remote_ip) >> (32 - $mask);

            if ($accept_long === $remote_long) {
                return true;
            }
        } elseif ($remote_ip === $accept_ip) {
            return true;
        }
    }

    return false;
}

/**
 * 多言語化を想定
 * @param  string $string
 * @return string
 */
function __($string) {
    return $string;
}

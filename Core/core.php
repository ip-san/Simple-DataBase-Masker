<?php
require_once('const.php');
// セッションをスタート
session_start();

// ベースクラス群を読み込み
require_once('Core/Controller/BaseController.php');
require_once('Core/Model/BaseModel.php');
require_once('functions.php');

$config_path = dirname(__FILE__) . '/../config.php';
$installe_path = dirname(__FILE__) . '/../installer.php';

// 設定ファイルが存在する場合は読み込み
if (file_exists($config_path)) {
    require_once($config_path);

    // インストーラーが存在すれば削除する
    if (file_exists($installe_path)) {
        if(@unlink($installe_path)) {
            setSuccessMsg('installer', __('インストーラー（installer.php）の削除に成功しました'));
        } else {
            setErrorMsg('installer', __('インストーラー（installer.php）の削除に失敗しました。手動削除して下さい'));
        }
    }
    
} elseif (file_exists($installe_path)) {
    // インストーラー起動
    require_once($installe_path);
    fulshDestroy();
    exit;
} else {
    echo 'no exist installer.php, please upload!';
    exit;
}

// アクセス元IP制限が有効な場合にホワイトリストをチェックする
if (IS_ACCESS_IP_WHITE_LIST_CHECK_ENABLE
        && !checkWhiteListIP()) {
    // 強制リダイレクト
    redirect('http://0.0.0.0/8');
    exit;
}

// ログインしている場合
if (isLogin()) {
    $controller = 'Email';
    $action = 'index'; 

    // コントローラー名を取得
    if(isset($_GET['controller']) && !empty($_GET['controller'])) {
        $controller = ucwords($_GET['controller']);
    }

    // アクション名を取得
    if(isset($_GET['action']) && !empty($_GET['action'])) {
        $action = ucwords($_GET['action']);
    }
    
} else {
    $controller = 'Auth';
    $action = 'login';
}

require_once('Core/Controller/' . $controller . 'Controller.php');

$className = $controller .'Controller';

$is404 = false;

$instance = new stdClass();

try {
    $instance = new $className;
} catch (Exception $e) {
    $is404 = true;
}

// 必要な値を共有
$instance->set('controller', strtolower($controller));
$instance->set('action', strtolower($action));

// 直接実行されたくないpublicメソッドは除く
if (!in_array($action, array('set', 'view', 'before', 'after'))) {
    // 前処理
    $instance->before();
    
    try { 
        // ロジックを実行
        $instance->$action();
    } catch (PDOException $e) {
        setErrorMsg(0, __('データベース接続エラー'));
    } catch (Exception $e) { 
        setErrorMsg(0, $e);
    }

    // ビューを表示
    $instance->view();

    // 後処理
    $instance->after();

    // メッセージ関連をクリア
    fulshDestroy();
} else {
    $is404 = true;
}

// ページが見つからない（現状ここは使われていない）
if ($is404) {
    require_once('Core/Controller/ErrorController.php');
    $errorController = new ErrorController();
    $errorController->page404();
}
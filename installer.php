<?php
if (strpos($_SERVER['REQUEST_URI'],'installer.php')) {
    echo 'direct access block';
    exit;
}

class Installer {

    
    function __construct() {
        $this->action();
    }

    /**
     * 処理本体
     * @return void
     */
    private function action() {
        // パーミッションをチェック
        if (!$this->checkPermission()) {
            return;
        }
        
        // POSTでない場合は処理終了
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            return;
        }
        
        // セッションに登録
        setSession('app_login_id', $_POST['app_login_id']); 
        setSession('app_password', $_POST['app_password']);
        setSession('re_app_password', $_POST['re_app_password']);
 
        // 入力チェック
        if ($this->validate()) {
            // 設定ファイルを作成
            if ($this->createConfingFile()) {
                // 作成に成功したらログイン画面に行く
                redirect();
            }
        }
    }
    
    /**
     * パーミッションチェック
     * @return boolean
     */
    private function checkPermission() {
        $result = true;
        
        if (!is_writable(dirname(__FILE__))) {
            $result = false;
            setErrorMsg(0, __('設定ファイルを書き出せるようにするために' . dirname(__FILE__) . 'に書き込み権限を与えて下さい')); 
        }
 
        return $result;
    }

    /**
     * 入力チェック
     */
    private function validate() {
        $result = true;
        
        // ユーザー名
        if (!isset($_POST['app_login_id']) || empty($_POST['app_login_id'])) {
            $result = false;
            setErrorMsg('app_login_id', __('ログインIDを決めて下さい'));
        } elseif (!$this->isAlnum($_POST['app_login_id'])) {
            // ユーザー名英数アルファベットチェック
            $result = false;
            setErrorMsg('app_login_id', __('ログインIDはアルファベット英数にして下さい'));
        }
        
        // パスワード
        if (!isset($_POST['app_password']) || empty($_POST['app_password'])) {
            $result = false;
            setErrorMsg('app_password', __('パスワードを決めて下さい'));        
        } elseif (!$this->isAlnum($_POST['app_password'])) {
            // パスワード英数アルファベットチェック  
            $result = false;
            setErrorMsg('app_password', __('パスワードはアルファベット英数にして下さい'));
        } elseif (isset($_POST['re_app_password']) 
               && ($_POST['app_password'] !== $_POST['re_app_password'])) {
           $result = false;
           setErrorMsg('re_app_password', __('パスワードは同一の文字列で確認して下さい'));
       }
       
       return $result;
    }
    
    /**
     * 設定ファイルを作成する
     */
    private function createConfingFile() {
        $string = $this->getConfigString();
 
        if (file_put_contents('config.php', $string)) {
            setSuccessMsg(0, 'ログインID' . $_POST['app_login_id'] . 'にてインストールに成功しました');
            return true;
        } else {
            return false;
        }
    }
 
    /**
     * saltのキーを作り出す
     * @param string $app_user_name ユーザー名を元にする
     * @return string
     */
    private function createSaltKey($app_user_name) {
        return str_shuffle(md5($app_user_name));
    }
    
    /**
     * 設定ファイルの中身の文字を取得
     * 
     * @return string
     */
    private function getConfigString() {
        
        $salt = $this->createSaltKey($_POST['app_login_id']);        
        
        $app_login_id = $_POST['app_login_id'];
        // パスワードハッシュ生成
        $app_password = getOriginalHash($_POST['app_password'], $salt);
        
        // 設定ファイル自動生成
        $config = '<?php' . PHP_EOL;
        $config .= '/* ユーザーリスト */' . PHP_EOL;
        $config .= '$app_user_list = array(' . PHP_EOL;
        $config .=<<<CONFIG
    array(
        'app_login_id' => '$app_login_id',
        'app_password' => '$app_password')
    );
/* セキュリティソルト */
define('SALT_KEY', '$salt');

/* CSRFチェックでワンタイムトークン　有効・無効 */
define('IS_CSRF_CHECK_ONETIME_TOKEN_ENABLE', false);

CONFIG;

$config .= '/* データベース名文字列のホワイトリスト */' . PHP_EOL;
    $config .= '$db_name_str_white_list = array(' . PHP_EOL;
        $config .=<<<CONFIG
        'stg',
        'staging',
        'test',
        'tst',
        'dev');

/* データベース名文字列のホワイトリストチェック　有効・無効 */
define('IS_DB_NAME_WHITE_LIST_CHECK_ENABLE', true);
CONFIG;

    $config .= PHP_EOL . '/* IP許可リスト */' . PHP_EOL;
    $config .= '$ip_white_list = ' . PHP_EOL;
    $config .=<<<CONFIG
    array(
    );

/* アクセス元IPのホワイトリストチェック　有効・無効 */
define('IS_ACCESS_IP_WHITE_LIST_CHECK_ENABLE', false);        
CONFIG;

        return $config;
    }
    
    /**
     * アルファベット英数チェック
     * @param string $string
     * @return boolean
     */
    private function isAlnum($string) {
        $result = false;
        
        if (preg_match('/^[a-zA-Z0-9]+$/', $string)) {
            $result = true;
        }  
        
        return $result;
    }
    
}

// インスタンス化
new Installer();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/base.css">
        <link rel="stylesheet" href="css/login.css">
        <script src="js/base.js"></script>
        <script src="js/login.js"></script>
        <title><?=__('インストール')?> | <?=APP_NAME?></title>
        <meta name="viewport" content="initial-scale=1.0,width=device-width,user-scalable=no">
    </head>
    <body>
        <div id="wrap">
            <main>
                <?php element('success_messages');?>
                <?php element('error_messages');?>
                <form id="login-form" action="index.php" method="post">
                    <table>
                        <thead>
                            <tr>
                                <th><?=APP_NAME?>&nbsp;(Version<?=VERSION?>)</th>
                            </tr>    
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <?php element('input_text', array('name'=> 'app_login_id', 'text_name' => __('ログインID')))?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php element('input_text', array('type' => 'password', 'name'=> 'app_password', 'text_name' => __('パスワード')))?>
                                </td>
                            </tr>
                             <tr>
                                <td>
                                    <?php element('input_text', array('type' => 'password', 'name'=> 're_app_password', 'text_name' => __('確認用パスワード')))?>
                                </td>
                            </tr>
                            <tr>
                                <td id="btn-wrap">
                                    <a id="login-btn" class="btn" href="javascript:void(0);"><?=__('インストール')?></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </main>
        </div>
    </body>
</html>
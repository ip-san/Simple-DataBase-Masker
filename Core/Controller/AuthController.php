<?php
/**
 * 認証に関わる処理
 *
 * @author Ippei Sesoko
 */
class AuthController extends BaseController
{
    /**
     * 前処理
     */
    public function before()
    {
        parent::before();
        // レイアウトをログイン用に変える
        $this->layout = 'login';
    }

    /**
     * ログイン画面
     */
    public function login()
    {
        // ログイン処理の場合
        if ($_SERVER["REQUEST_METHOD"] === "POST")
        {
            $this->auth();
        }
    }

    /**
     * 認証処理
     * @global array $app_user_list
     * @return void
     */
    private function auth()
    {
        global $app_user_list;

        if (isset($_POST['app_login_id']) && isset($_POST['app_password']))
        {

            foreach ($app_user_list as $app_user)
            {
                if (($app_user['app_login_id'] === $_POST['app_login_id'])
                        && ($app_user['app_password'] === getOriginalHash($_POST['app_password'])))
                {
                    // セッションを確保
                    setSession('auth', $app_user);
                    setSuccessMsg('auth_login', __('ログインに成功しました'));
                    // トークンを作成
                    createToken();
                    // 再描画
                    redirect($_SERVER["REQUEST_URI"] . '?token=' . $_SESSION[SESSION_KEY]['token']);
                    return;
                }
            }

            setErrorMsg(0, __('ログインID、またはパスワードに誤りがあります'));
        }

    }



    /**
     * ログアウト処理
     */
    public function logout()
    {
        // セッションをクリア
        session_destroy();
        setSuccessMsg('auth_logout', __('ログアウトしました'));
    }
}

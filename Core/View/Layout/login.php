<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/base.css">
        <link rel="stylesheet" href="css/login.css">
        <script src="js/base.js"></script>
        <script src="js/login.js"></script>
        <title><?=__('ログイン')?> | <?=APP_NAME?></title>
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
                                <td id="btn-wrap">
                                    <a id="login-btn" class="btn" href="javascript:void(0);"><?=__('ログイン')?></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </main>
        </div>
    </body>
</html>
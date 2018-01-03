<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/base.css">
        <link rel="stylesheet" href="css/<?=$controller?>.css">
        <script src="js/base.js"></script>
        <script src="js/<?=$controller?>.js"></script>
        <title><?=$title?>　| <?=APP_NAME?></title>
        <meta name="viewport" content="initial-scale=1.0,width=device-width,user-scalable=no">
    </head>
    <body data-token="<?=$_SESSION[SESSION_KEY]['token']?>">
        <div id="wrap">
            <header>
                <div class="container">
                    <div class="container-small">
                        <div id="logo">
                            <span><?=APP_NAME?></span>
                        </div>
                        <button id="menu">
                            MENU
                        </button>
                    </div>
                    <nav id="nav">
                        <ul id="nav-list">
                            <li class="<?=active('email')?>">
                                <a href="<?= url('email', 'index')?>">
                                    <?=Email_PAGE_NAME?>
                                </a>
                            </li>
                            <li class="<?=active('scramble')?>">
                                <a href="<?= url('scramble', 'index')?>">
                                    <?=SCRAMBLE_PAGE_NAME?>
                                </a>
                            </li>
                            <li>
                                <a id="logout-btn" href="javascript:void(0);"
                                   data-action="<?= url('auth', 'logout')?>"
                                   data-confirm="<?=__('本当にログアウトしますか？')?>"><?=__('ログアウト')?></a> 
                            </li>
                        </ul>
                    </nav>
                </div>
            </header>
            <main>
                <div class="container">
                    <h1><?=$title?></h1>
                    <?php element('success_messages');?>
                    <?php element('error_messages');?>
                    <?php require_once(dirname(__FILE__) . '/../' . ucwords($controller) . '/' . $view . '.php');?>
                </div>
            </main>
            <footer id="footer">
                <div class="container">
                    
                </div>
            </footer>
        </div>
    </body>
</html>
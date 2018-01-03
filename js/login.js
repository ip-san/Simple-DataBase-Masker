// DOMが構築されてから実行
document.addEventListener('DOMContentLoaded', function() {
    
    // エラーメッセージが存在すれば
    var elements = document.querySelectorAll('.error-messages p');
    
    // エラーが存在した場合
    if (elements.length > 0) {
        execCallbackFunctionForElements('input', function(element) {
            element.classList.add('error');
        });
    }

    // ログイン処理
    function login() {
        var form = document.getElementById('login-form');
        // 送信
        form.submit();
    }

    // ログインボタンクリック時
    eventQuery('click', '#login-btn', function(){
        login();
    });
    
    // エンターキー
    eventQuery('keypress', 'form > *', function(e){
        if (e.keyCode == '13') {
            login();
        }
    });
    
    // フォームのエラー色をクリア
    eventQuery('click', '.messages .close', function(){
        execCallbackFunctionForElements('input', function(inputElement) {
            inputElement.classList.remove('error');
        });
    });
});
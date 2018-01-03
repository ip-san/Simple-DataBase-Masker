/**
 * 配列で
 * @param object elements
 * @param function callbackFunction
 * @returns {undefined}
 */
function execCallbackFunctionForElements(queryString, callbackFunction) {
    var elements = document.querySelectorAll(queryString);
    for (var i = 0; i < elements.length; i++) {
        callbackFunction(elements[i]);
    }
}

/**
 * 簡易イベントリスナー
 * @param string eventName
 * @param string queryString
 * @param function callbackFunction
 * @returns {undefined}
 */
function eventQuery(eventName, queryString, callbackFunction) {
           var elements = document.querySelectorAll(queryString);

           for (var i = 0; i < elements.length; i++) {
               elements[i].addEventListener(eventName, callbackFunction);
           }
}

/**
 * フェードアウト
 * @param object element
 * @returns {undefined}
 */
function fadeOut(element) {
    element.classList.add('fadeout');
      setTimeout(function(){
        element.style.display = "none";
      }, 1000);
}

/**
 * フッターの表示
 * @returns {undefined}
 */
function activeFooter() {
    var height = document.body.clientHeight;

    var scrollTop =
        document.documentElement.scrollTop || // IE、Firefox、Opera
        document.body.scrollTop;              // Chrome、Safari

    var footerElement = document.getElementById('footer');

    if (footerElement !== null) {
        if ((height - scrollTop) <= 650) {
            footerElement.classList.add('bottom');
        } else {
            footerElement.classList.remove('bottom');
        }
    }
}

// DOMが構築されてから実行
document.addEventListener('DOMContentLoaded', function() {

    // メニュークリック
    eventQuery('click', '#menu', function() {
        var nav = document.getElementById('nav-list');

        if ((nav.style.display === 'none')
                || (nav.style.display === '')) {
            nav.style.display='block';
        } else {
            nav.style.display='none';
        }
    });

    // メッセージを消去
    eventQuery('click', '.messages .close', function(){
        fadeOut(this.parentNode);

        var id = this.getAttribute('data-form-id');
        var inputElement = document.getElementById(id);

        if ((inputElement !== null) && inputElement.classList) {
            inputElement.classList.remove('error');
        }

        // フッターの位置が変にならないように
        activeFooter();
    });

    // ボタンクリック時
    eventQuery('click', '.btns .btn', function(){
        var form = document.getElementById('database-form');

        var action = this.getAttribute('data-action');

        // 実行以外は確認無し
        if ((action !== 'do_replace')
                || ((action === 'do_replace')) && confirm(this.getAttribute('data-confirm'))) {
            form.action = 'index.php?controller=' + this.getAttribute('data-controller')
                    + '&action=' + action;

            form.action += '&token=' + document.body.getAttribute('data-token');

            // 送信
            form.submit();
        }
    });

    // ログアウトボタンクリック時
    eventQuery('click', '#logout-btn', function(){
        if (confirm(this.getAttribute('data-confirm'))) {
            location.href = this.getAttribute('data-action');
        }
    });

    // 初期表示
    activeFooter();

    // スクロール時
    window.onscroll = function() {
        activeFooter();
    };

    // リサイズ時
    window.onresize = function() {
        activeFooter();

        var windowWidth = window.innerWidth;

        // 横幅の変更に備える
        if (windowWidth > 799) {
            var nav = document.getElementById('nav-list');
            nav.style.display = '';
        }
    };

});

// DOMが構築されてから実行
document.addEventListener('DOMContentLoaded', function() {
    // 文字シャッフルのON OFFを実装
    eventQuery('change', '#is_str_shaffle', function() {
        if (this.value == 1) {
            this.value = 0;
        } else {
            this.value = 1;
        }
    });
});


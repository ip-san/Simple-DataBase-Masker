<ul class="db-conns">
    <li>
        <?php element('input_text', array('name'=> 'connect_host', 'text_name' => __('接続先ホスト')))?>
    </li>
    <li>
        <?php element('input_text', array('name'=> 'database_name', 'text_name' => __('データベース名')))?>
    </li>
    <li>
        <?php element('input_text', array('name'=> 'database_username', 'text_name' => __('ユーザー名')))?>        
    </li>                    
    <li>
        <?php element('input_text', array('name'=> 'database_password', 'text_name' => __('パスワード名')))?> 
    </li>
    <li>
        <?php element('input_text', array('name'=> 'table_name', 'text_name' => __('テーブル名'), 'data_list' => $table_name_list))?>          
    </li>
    <li>
        <?php element('input_text', array('name'=> 'column_name', 'text_name' => __('カラム名'), 'data_list' => $column_name_list))?> 
    </li>                        
</ul>
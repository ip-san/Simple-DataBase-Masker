<?php

/**
 * ベースのモデル
 *
 * @author Ippei Sesoko
 */
class BaseModel
{
    protected $pdo = false;
    protected $db = 'mysql';
    protected $charset = 'utf8';
    protected $connect_host;
    protected $database_name;
    protected $database_username;
    protected $database_password;
    protected $table_name;
    protected $column_name;
    protected $transaction_record_num;
    protected $offset;
    protected $limit;
    protected $primary_key;
    protected $table_name_list;
    protected $coloumn_name_list;

    /**
     * PDOオブジェクト取得（DB接続）
     * @return \PDO
     * @throws PDOException
     */
    public function getPDO()
    {

        try {
            // まだインスタンスが作成されていない場合
            if (!$this->pdo) {
                // データベースに接続
                $this->pdo = new PDO(
                        "{$this->db}:dbname={$this->database_name};host={$this->connect_host};charset={$this->charset}", $this->database_username, $this->database_password, array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        )
                );
            }
        } catch (PDOException $e) {
            throw $e;
        }

        return $this->pdo;
    }

    /**
     * 主キーのカラム名を取得
     * @param string $table_name
     * @return string 主キーのカラム名
     * @throws PDOException
     */
    public function getPrimaryKey($table_name = '')
    {
        if (!empty($this->primary_key)) {
            return $this->primary_key;
        }

        if (!$table_name) {
            $this->setTableName($table_name);
        }

        $this->pdo = $this->getPDO();

        try {
            $stmt = $this->pdo->prepare('SHOW COLUMNS FROM ' . $this->table_name,
                    array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

            $stmt->execute();
            while ($row = $stmt->fetch()) {
                if ($row['Key'] === 'PRI') {
                    return $this->primary_key = $row['Field'];
                }
            }
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * テーブル名の配列を取得
     * @param string $database_name データベース名
     * @return array                テーブル名のリスト
     * @throws PDOException
     */
    public function getTableNameList($database_name = '')
    {

        if (isset($this->table_name_list)
                && is_array($this->table_name_list)) {
            return $this->table_name_list;
        } else {
            $this->table_name_list = array();
        }

        if (!empty($database_name)) {
            $this->setDatabaseName($database_name);

            $this->pdo = $this->getPDO();

            try {
                $stmt = $this->pdo->prepare('SHOW TABLES',
                        array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

                $stmt->execute();
                while ($row = $stmt->fetch()) {
                    if (isset($row['Tables_in_' . $this->database_name])
                            && !empty($row['Tables_in_' . $this->database_name])) {
                        $this->table_name_list[] = $row['Tables_in_' . $this->database_name];
                    }
                }
            } catch (PDOException $e) {
                throw $e;
            }
        }

        return $this->table_name_list;
    }

    /**
     * カラム名のリストを取得する
     * @param string $table_name
     * @return array
     * @throws PDOException
     */
    public function getColumnNameList($table_name = '')
    {
        if (isset($this->coloumn_name_list)
                && is_array($this->coloumn_name_list )) {
            return $this->coloumn_name_list;
        } else {
            $this->coloumn_name_list = array();
        }

        if (!empty($table_name)) {
            $this->setTableName($table_name);

            $this->pdo = $this->getPDO();

            try {
                $stmt = $this->pdo->prepare('SHOW COLUMNS FROM ' . $this->table_name,
                        array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

                $stmt->execute();
                while ($row = $stmt->fetch()) {
                    if (isset($row['Field']) && !empty($row['Field'])) {
                        $this->coloumn_name_list[] = $row['Field'];
                    }
                }
            } catch (PDOException $e) {
                throw $e;
            }

        }

        return $this->coloumn_name_list;
    }

    /**
     * レコード数を取得
     * @param string $table_name
     * @return type
     * @throws PDOException
     */
    public function countRecords($table_name = '')
    {
        if (!$table_name) {
            $this->setTableName($table_name);
        }

        $this->pdo = $this->getPDO();

        try {
            $stmt = $this->pdo->prepare('SELECT count(*) as cnt FROM ' . $this->table_name);

            $stmt->execute();
            $result = $stmt->fetchAll();

            return $result[0]['cnt'];
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * テーブル名とカラム名から配列を取得
     * @param string    $table_name
     * @param string    $column_name
     * @param int       $offset
     * @param int       $limit
     * @return array
     * @throws PDOException
     */
    public function find($table_name = '', $column_name = '', $offset = 0, $limit = 0)
    {

        if (!empty($table_name)) {
            $this->setTableName($table_name);
        }

        if (!empty($column_name)) {
            $this->setColumnName($column_name);
        }

        if (!empty($offset)) {
            $this->setOffset($offset);
        }

        if (!empty($limit)) {
            $this->setLimit($limit);
        }

        $this->pdo = $this->getPDO();

        $primary_key = $this->getPrimaryKey($this->table_name);

        try {
            $sql= <<<SQL
                SELECT
                    `{$this->primary_key}`,
                    `{$this->column_name}`
                FROM  `{$this->table_name}`
SQL;
            if (!empty($this->offset) && !empty($this->limit)) {
                $sql .= 'LIMIT :offset , :limit';
            }

            $stmt = $this->pdo->prepare($sql);

            if (!empty($this->offset) && !empty($this->limit)) {
                $stmt->bindValue(':offset', (int)$this->offset, PDO::PARAM_INT);
                $stmt->bindValue(':limit', (int)$this->limit, PDO::PARAM_INT);
            }

            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * 配列が渡った分更新をかける
     * @param array  $datas
     * @param string $table
     * @param string $column
     */
    public function updateAll($datas, $table_name = '', $column_name = '')
    {

        if (!$table_name) {
            $this->setTableName($table_name);
        }

        if (!$column_name) {
            $this->setColumnName($column_name);
        }

        $this->pdo = $this->getPDO();

        try {
            foreach ($datas as $row) {
                $sql= <<<SQL
                    UPDATE
                        `{$this->table_name}`
                    SET
                        `{$this->column_name}` = :after_value
                    WHERE
                        `{$this->primary_key}` = :primary_key
SQL;

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(':after_value', $row['after_value']);
                $stmt->bindValue(':primary_key', $row[$this->primary_key]);
                $stmt->execute();
            }
        } catch (PDOException $e) {
            // ロールバック
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * 共通パラメーターセッター
     * @param string $param         パラメーター値
     * @param string $param_name    postされる時のキー等
     * @param string $error_message 値を取得できなかった時のエラーメッセージ
     */
    protected function setParam($param, $param_name, $error_message = '')
    {
        if (!empty($param)) {
            $this->$param_name = trim($param);
        } elseif (isset($_POST[$param_name])) {
            $this->$param_name = trim($_POST[$param_name]);
        }

        // 取得が行えなかった場合
        if ((!isset($this->$param_name) || $this->$param_name === '')
                && !empty($error_message)) {
            setErrorMsg($param_name, __($error_message));
        }

        setSession($param_name, $this->$param_name);
    }

    /**
     * 接続先ホスト
     * @param string $connect_host
     */
    public function setConnectHost($connect_host = '')
    {
        $this->setParam($connect_host, 'connect_host');

        // 空だった場合は同じサーバーにあるとみなす
        if (empty($this->connect_host)) {
            $this->connect_host = 'localhost';
            setSession('connect_host', 'localhost');
        }
    }

    /**
     * データベース名指定
     * @param string $dbname
     */
    public function setDatabaseName($database_name = '')
    {
        global $db_name_str_white_list;

        $this->setParam($database_name, 'database_name', 'データベース名が指定されていません');

        $is_mache = false;

        foreach ($db_name_str_white_list as $db_name_str) {

            if (strpos($this->database_name, $db_name_str) !== false) {
                $is_mache = true;
                break;
            }
        }

        // ホワイトリストに無い場合
        if (!empty($database_name) && !$is_mache) {
            setErrorMsg('database_name', __('データベース名から推測すると本番環境である可能性があります'));
        }
    }

    /**
     * データベースユーザー名
     * @param string $username
     */
    public function setDatabaseUsername($database_username = '')
    {
        $this->setParam($database_username, 'database_username', 'ユーザー名が指定されていません');
    }

    /**
     * データベースパスワード
     * @param string $password
     */
    public function setDatabasePassword($database_password = '')
    {
        $this->setParam($database_password, 'database_password', 'パスワードが指定されていません');
    }

    /**
     * テーブル名
     * @param string $table_name
     */
    public function setTableName($table_name = '')
    {
        $this->setParam($table_name, 'table_name', 'テーブル名が指定されていません');

        // 取得されたテーブル名が存在するテーブルかどうかをチェック
        if (!empty($this->table_name)
                && !in_array($this->table_name, $this->getTableNameList($this->database_name))) {
            setErrorMsg('table_name', __('指定されたテーブル名がデータベースに存在しません'));
        }
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    /**
     * カラム名
     * @param strin $column_name
     */
    public function setColumnName($column_name = '')
    {
        $this->setParam($column_name, 'column_name', 'カラム名が指定されていません');

        // 受け取った値が実際に存在しない場合
        if (!empty($this->column_name)
                && !in_array($this->column_name, $this->getColumnNameList($this->table_name))) {
            setErrorMsg('column_name', __('指定されたカラム名がテーブルに存在しません'));
        }
    }

    /**
     * 1回のデータベース接続で処理する件数
     * @param int $transaction_record_num
     */
    public function setTransactionRecordNum($transaction_record_num = 0)
    {
        $this->setParam($transaction_record_num, 'transaction_record_num');
    }

    public function getTransactionRecordNum()
    {
        $result = 0;

        if (!isset($this->transaction_record_num)
            || empty($this->transaction_record_num)) {
          $result = $this->transaction_record_num;
        }

        return $result;
    }

    /**
     * テーブルのどこから処理するか
     * @param int $offset
     */
    public function setOffset($offset = 0)
    {
        $this->setParam($offset, 'offset');
    }

    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * テーブルのどこまで処理するか
     * @param int $limit
     */
    public function setLimit($limit = 0)
    {
        $this->setParam($limit, 'limit');
    }

    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * 基本的に受けるデータ
     */
    public function catchPostDataBase()
    {
        $this->setConnectHost();
        $this->setDatabaseName();
        $this->setDatabaseUsername();
        $this->setDatabasePassword();
    }

    /**
     * POSTで渡ってくるデータを受け取る
     * @param　bool $isColumnUse カラムを使うかどうか
     */
    public function catchPostData($isColumnUse = false)
    {

        $this->catchPostDataBase();

        // 接続確認以外では実質必須
        if ($isColumnUse) {
            $this->setTableName();
            $this->setColumnName();
            $this->setTransactionRecordNum();
        }
    }
}

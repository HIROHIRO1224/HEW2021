<?php

/**
 * DBAクラス
 */

class DBA
{



    //プロパティ
    private $dsn = ''; //接続文字列
    private $user = ''; //データベースユーザー名
    private $pwd = ''; //データベースパスワード
    private $conn = ''; //PDOオブジェクトを格納するプロパティ


    //クラス内定数
    public const ALL = 1000;
    public const NUMVALUE = 1001;
    public const KEYVALUE = 1002;



    /**
     * インスタンス時メソッド
     * 
     * @param string $UserName PHPMyAdminにログインするときのユーザー名
     * @param string $Password PHPMyAdminにログインするときのパスワード
     * @param string $DBName 入りたいデータベース名
     * @param string $host zamppの場合→localhost、自分用(Dockerなど開発環境が異なる)の場合→db
     */
    public function __construct(string $UserName, string $Password, string $DBName, string $host)
    {
        //接続文字列の組立
        $this->dsn = "mysql:dbname={$DBName};host={$host};charset=utf8;";
        $this->user = $UserName;
        $this->pwd = $Password;

        //DBに接続する処理
        $this->conn = new PDO($this->dsn, $this->user, $this->pwd);

        //接続情報を呼び出し元に返却する
        return $this->conn;
    }

    /**
     * SELECTメソッド
     * 
     * テーブルの中での取り出したいカラム、レコードを指定してSELECT文を実行する関数
     * 
     * @param string $table 使用したいテーブル名
     * @param array|int $columnsName 取り出すカラム名の入った配列、もしくはDBA::ALLで全てを取り出す
     * @param int $mode DBA::ALL→テーブルにある全てのレコードを取り出す。DBA::NUMVALUE→疑問符プレースプレースホルダを用いて条件を指定する。
     * DBA::KEYVALUE→名前付けされたプレースホルダを用いて条件を指定する。条件を指定する場合は条件文、その後にデータを入れる。
     * @param string|null $condition sql文でwhere以降で指定する条件文
     * @param array|null $params プレースホルダにいれるデータの入った配列 $mode="NUMVALUE"→条件で指定した順番に並べられた配列
     * $mode="KEYVALUE"→カラム名と同一のキーで指定された連想配列
     * @return array 実行結果が格納された変数
     */

    public function SELECT(string $table, array|int $columnsName, int $mode, string $condition = null, array $params = null)
    {
        // 変数の初期化
        $sql = '';
        $ret = null;

        // 取り出したいカラム名を指定する
        if ($columnsName == 1000) { //全て取り出す場合

            // SQL文を組み立てる
            $sql = "SELECT * FROM {$table}";
        } else { //配列が送られてきた場合

            //SQL文を組み立てる変数
            $sql = "SELECT ";

            // SQl文を組み立てる
            $i = 0;
            do {
                $sql .= "{$columnsName[$i]}, ";
            } while ($i < count($columnsName) - 1);

            $sql .= "{$columnsName[$i]} ";
        }
        // 取り出すレコードを指定する
        switch ($mode) {
                //上で設定した定数を使って使いたいモードを切り替える
            case 1000: //全てのレコードを取り出す

                // SQL文を組み立ててセットし、実行
                $sql .= ";";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute();

                // カラム名の連想配列で格納する
                $ret = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;

                //DBA::NUMVALUEの場合
            case 1001: //疑問符プレースホルダで取り出したいレコードを指定する

                // SQL文を組み立ててセット
                $sql .= " where {$condition};";
                $stmt = $this->conn->prepare($sql);

                //設定したいパラメータを当てはめる
                $i = 1;
                foreach ($params as $param) {

                    $stmt->bindValue($i, $param);
                    $i++;
                }

                // SQL文を実行
                $stmt->execute();

                // カラム名の連想配列で格納する
                $ret = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;

                //DBA::KEYVALUEの場合
            case 1002: //名前つきプレースホルダで取り出したいレコードを指定する

                // SQL文を組み立ててセット
                $sql .= " where {$condition};";
                $stmt = $this->conn->prepare($sql);

                // カラム名のキー情報を配列でいれる
                $columns = array_keys($params);

                // 設定したいパラメータを当てはめる
                foreach ($columns as $column) {
                    $stmt->bindValue(":{$column}", $column);
                }

                // SQL文を実行
                $stmt->execute();

                // カラム名の連想配列で格納する
                $ret = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;
        }
        // 呼び出し元に結果を返す
        return $ret;
    }



    /**
     * INSERTを行うメソッド
     * 
     * 列を挿入したいテーブル名を指定し、そこにいれるデータをカラム名の連想配列を使ってデータを送る。
     * 連想配列でデータを送る時はキーの文字列とカラム名が完全一致になるようにする。

     * @param string $table テーブル名
     * @param array $data カラム名でキー設定された連想配列
     * @return bool 正常に実行された→true、実行に失敗した→false
     */
    public function INSERT(string $table, array $data)
    {
        //実行結果を格納する変数
        $ret = null;

        //INSERT文を組み立てる変数
        $sql = "INSERT INTO " . $table . " ( ";
        $sql2 = ") value (";

        //送られてきた配列のキーを格納する
        $columns = array_keys($data);

        //sql文のvalue部分を付け加える
        foreach ($columns as $column) {
            if ($data[$column] == $data[$columns[count($columns) - 1]]) {

                $sql .= $column . " ";
                $sql2 .= ":{$column} ";
            } else {
                $sql .= $column . ", ";
                $sql2 .= ":{$column}, ";
            }
        }

        //sqlの部品を一つにまとめる
        $sql .= $sql2 . ");";

        //組み立てたsqlを使ってprepare関数を実行
        $stmt = $this->conn->prepare($sql);

        //valueにデータを当てはめる
        foreach ($columns as $column) {
            $stmt->bindValue(":{$column}", $data[$column]);
        }

        //execute関数実行
        $ret = $stmt->execute();

        //実行できたかどうかtrueかfalseでかえす
        return $ret;
    }



    /**
     * DELETEメソッド
     * 
     * @param string $table テーブル名
     * @param string $idColumn 一位制約として使用してるカラム名
     * @param string $id 指定するために使用するidデータ
     * @return bool $ret 正常に実行出来たかどうか
     */
    public function DELETE(string $table, string $idColumn, string $id)
    {
        //sql文を組み立てる変数
        $sql = "DELETE FROM {$table} where {$idColumn} = ? ;";

        //作成したsql文でprepare関数を実行
        $stmt = $this->conn->prepare($sql);

        //パラメータを当てはめる
        $stmt->bindValue(1, $id);

        //execute関数を実行、実行結果を$retに格納する
        $ret = $stmt->execute();

        //実行結果を返す
        return $ret;
    }



    /**
     * UPDATEメソッド
     * 
     * テーブル内の指定したidデータの内容をキー名の連想配列を用いて更新する
     * 
     * @param string $table テーブル名
     * @param array $data 変更するデータがカラム名での連想配列で入った配列
     * @param string $idColumn 一位制約として使用してるカラム名
     * @param string $id 指定するために使用するidデータ
     * @return bool $ret 正常に実行できたか
     */
    public function UPDATE(string $table, array $data, string $idColumn, string $id)
    {

        // SQL文を組み立てる変数
        $sql = "UPDATE {$table} SET ";

        // カラム名のキー情報を配列でいれる
        $columns = array_keys($data);

        // SQL文を組み立てる
        foreach ($columns as $column) {
            if ($data[$column] == $data[$columns[count($columns) - 1]]) {
                $sql .= "{$column} = :{$column} ";
            } else {
                $sql .= "{$column} = :{$column}, ";
            }
        }
        $sql .= " WHERE {$idColumn} = :{$idColumn};";

        // SQL文をセット
        $stmt = $this->conn->prepare($sql);

        //valueにデータを当てはめる
        foreach ($columns as $column) {
            $stmt->bindValue(":{$column}", $data[$column]);
        }
        $stmt->bindValue(":{$idColumn}", $id);

        // SQL文を実行
        $ret = $stmt->execute();

        // 結果のbool値を返す
        return $ret;
    }
}

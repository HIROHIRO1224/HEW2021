<?php 

    /**
     * データベースアクセスクラス(ラッパークラス)
     * ラッパークラス：別のオブジェクトを自作のクラスでラッピングすること
     */

    class DBAccess{


        
        //プロパティ
        private $dsn = '';//接続文字列
        private $user = '';//データベースユーザー名
        private $pwd = '';//データベースパスワード
        private $conn = '';//PDOオブジェクトを格納するプロパティ



        //メソッド
        public function __construct()
        {
            //接続文字列の組立
            $this->dsn = 'mysql:dbname=HEW;host=localhost;charset=utf8;';
            $this->user = 'root';
            $this->pwd = '';

            //DBに接続する処理
            $this->conn = new PDO($this->dsn, $this->user, $this->pwd);

            //接続情報を呼び出し元に返却する
            return $this->conn;
        }

        /**
         * selectメソッド
         * 引数：実行するSQL
         * 戻り値：実行結果が格納された変数
         */

        public function select($sql, $params=null)
        {
            // PDO::FETCH_ASSOC=>配列の列名をテーブルの列名として保存

            $ret=null;
            //引数paramsが指定されているか
            if (is_null($params)) {//$params=nullの場合はtrueを返す

                $stmt=$this->conn->query($sql);

                $ret=$stmt->fetchAll(PDO::FETCH_ASSOC);

            }else{
                // SQLパラメータ月のSQLを実行する
                $stmt=$this->conn->prepare($sql);
                $i=1;

                // 引数paramsは配列でデータを送るため
                // 一つずつ取り出しながら処理をする
                foreach($params as $param){

                    // 送られてきたデータ数分bindValueメソッドで
                    // データをSQLに割り当てる
                    $stmt->bindValue($i,$param);

                    $i++;// インクリメントして割り当てる?の場所を指定
                }

                // SQL実行
                $stmt->execute();

                // 取り出したレコードを配列として保管
                $ret=$stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            // 呼び出し元に結果を返す
            return $ret;
        }



        /**
         * INSERT, UPDATE, DELETE文を実行するメソッド
         * 引数：sql->実行するSQL文
         *     ：params->SQLに割り当てるデータ(配列)
         * 戻り値：影響を受けたレコードの件数
         */
        public function execute($sql, $params)
        {
            // SQLパラメータ月のSQLを実行する
            $stmt=$this->conn->prepare($sql);
            $i=1;

            // 引数paramsは配列でデータを送るため
            // 一つずつ取り出しながら処理をする
            foreach($params as $param){

                // 送られてきたデータ数分bindValueメソッドで
                // データをSQLに割り当てる
                $stmt->bindValue($i,$param);

                $i++;// インクリメントして割り当てる?の場所を指定
            }

            // SQL実行
            return $stmt->execute();
        }
    }

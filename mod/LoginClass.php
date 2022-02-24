<?php

include_once('DBA.php');

class LoginClass
{

    /**
     * ログインメソッド
     * 
     * 引数：ユーザー名、またはパスワード、パスワード
     * 戻り値：ログイン成功=>true,ログイン失敗=>false
     */

    public function login($user, $pwd)
    {
        try {

            $dba = new DBA("root", "", "HEW", "localhost");

            $params = [$user, $user];
            $condition = "user_name=? or user_email=?;";

            $column = $dba->SELECT("t_users", DBA::ALL, DBA::NUMVALUE, $condition, $params);
            if (count($column) == 1) {

                if ($column[0]["user_pwd"] == $pwd) {
                    # code...
                    return $column[0];
                }
                return false;
            } else {
                return false;
            }
        } catch (PDOException $e) {

            throw $e->getMessage();
            return false;
        } finally {

            $dba = null;
        }
    }

    public function signUp($pwd, $name, $truename, $mailAddress)
    {
        # code...
        try {
            $dba = new DBA("root", "", "HEW", "localhost");

            $params = ["user_name" => $name, "user_tname" => $truename, "user_pwd" => $pwd, "user_email" => $mailAddress];

            $result = $dba->INSERT("t_users", $params);
            return $result;
        } catch (PDOException $e) {
            echo 'DBエラー' . $e->getMessage();
            return false;
        } finally {
            $dba = null;
        }
    }

    public function Logout()
    {
        # code...
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            # code...
            $param = session_get_cookie_params();
            setcookie(session_name(), '', time() - 60 * 60 * 24 * 30, $param['path'], $param['domain'], $param['secure'], $param['httponly']);
        }
        session_destroy();

        setcookie('user_data', '', time() - 60 * 60);
        setcookie('password', '', time() - 60 * 60);
    }
}

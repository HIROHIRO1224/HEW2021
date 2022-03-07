<?php

session_start();

include_once './mod/DBA.php';
include_once './mod/LoginClass.php';
include_once './mod/module.php';

$user = '';

try {
    //code...
    if (!isset($_SESSION['user_id']) && isset($_COOKIE['user_data'])) {
        # code...
        $loginclass = new LoginClass();
        $result = $loginclass->login($_COOKIE['user_data'], $_COOKIE['password']);

        if ($result != false) {
            # code...
            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['time'] = time();
            setcookie('user_data', $_POST['user_data'], time() + 60 * 60 * 24 * 30);
            setcookie('password', $_POST['password'], time() + 60 * 60 * 24 * 30);
        }
    } elseif (isset($_SESSION['user_id']) && $_SESSION['time'] + 3600 > time()) {
        # code...
        $_SESSION['time'] = time();
        $dba = new DBA('root', '', 'HEW', 'localhost');
        $condition = 'user_id = ?;';
        $params = [$_SESSION['user_id']];
        $columns = $dba->SELECT('t_users', DBA::ALL, DBA::NUMVALUE, $condition, $params);
        $user = $columns[0];
    } else {
        # code...
        header("Location: ./login/index.php?error=timeout");
        exit;
    }

    $cart = '';
    var_dump($user);
    if ($user['user_cart'] == null) {
        # code...
        $cart = array();
    } else {
        $cart = explode(
            ',',
            $user['user_cart']
        );
    }
    if (!empty($_REQUEST['action'])) {

        // カートに追加する処理
        if ($_REQUEST["action"] == 'add') {
            for ($i = 0; $i < count($cart); $i++) {
                # code...
                if ($cart[$i] == $_REQUEST['item_id']) {
                    $result = 'already';
                    header("Location: {$_REQUEST['sender']}?cart_result={$result}");
                    exit();
                }
            }
            array_push($cart, $_REQUEST['item_id']);
            $cart = implode(",", $cart);
            $params = ['user_cart' => $cart];
            $dba = new DBA('root', '', 'HEW', 'localhost');
            $result = $dba->UPDATE('t_users', $params, 'user_id', $user['user_id']);
            if ($result) {
                $result = 'success';
            } else {
                $result = 'miss';
            }
            header("Location: {$_REQUEST['sender']}?cart_result={$result}&cart_action=add");
            exit();
        } elseif ($_REQUEST['action'] == 'delete') {
            echo "消す処理";
            for ($i = 0; $i < count($cart); $i++) {
                # code...
                if ($_REQUEST['item_id'] == $cart[$i]) {
                    # code...
                    unset($cart[$i]);
                    $cart = implode(",", $cart);
                    $params = ['user_cart' => $cart];
                    $dba = new DBA('root', '', 'HEW', 'localhost');
                    $result = $dba->UPDATE('t_users', $params, 'user_id', $user['user_id']);
                    if ($result) {
                        $result = 'success';
                    } else {
                        $result = 'miss';
                    }
                    header("Location: {$_REQUEST['sender']}?cart_result={$result}&cart_action=delete");
                    exit();
                }
            }
        }
    }
    if (!empty($user['user_cart'])) {
        # code...
    } else {
        # code...
        echo 'カートが空っぽだよ';
    }
} catch (PDOException $e) {
    throw $e->getMessage();
}

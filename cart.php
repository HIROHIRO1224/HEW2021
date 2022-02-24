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

    if (!empty($user['user_cart'])) {
        # code...
        $cart = explode(
            ',',
            $user['user_cart']
        );
    } else {
        # code...
        echo 'カートが空っぽだよ';
    }
} catch (PDOException $e) {
    throw $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

</body>

</html>
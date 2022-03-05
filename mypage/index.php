<?php

session_start();

include_once '../mod/DBA.php';
include_once '../mod/LoginClass.php';
include_once '../mod/module.php';

$user = '';

try {
    //code...
    $dba = new DBA('root', '', 'HEW', 'localhost');

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
        $condition = 'user_id = ?;';
        $params = [$_SESSION['user_id']];
        $columns = $dba->SELECT('t_users', DBA::ALL, DBA::NUMVALUE, $condition, $params);
        $user = $columns[0];
    } else {
        # code...
        header("Location: /HEW/login/index.php?error=timeout");
        exit;
    }

    if (!empty($_POST)) {
        $data = [
            'user_name' => $_POST['user_name'],
            'user_tname' => $_POST['user_tname'],
            'user_email' => $_POST['user_email'],
            'user_card' => $_POST['user_card'],
            'user_card_limit' => $_POST['user_card_limit'],
            'user_birthday' => $_POST['user_birthday']
        ];
        $result = $dba->UPDATE('t_users', $data, 'user_id', $user['user_id']);

        $condition = 'user_id = ?;';
        $params = [$_SESSION['user_id']];
        $columns = $dba->SELECT('t_users', DBA::ALL, DBA::NUMVALUE, $condition, $params);
        $user = $columns[0];
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

    <!-- Favicon -->
    <link href="/HEW/img/Logo.png" rel="shortcut icon" type="image/png" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="/HEW/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/HEW/css/font-awesome.min.css" />
    <link rel="stylesheet" href="/HEW/css/owl.carousel.css" />
    <link rel="stylesheet" href="/HEW/css/animate.css" />



    <title>Playground マイページ</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3 clearfix">
        <div class="container">
            <a href="/HEW/" class="navbar-brand">
                <img src="/HEW/img/Logo.png" width="28" height="30" class="d-inline-block align-top" alt="">
                Playground
            </a>

            <!-- この下の行に mr-auto クラスを付与するだけ -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/HEW/search.php">Search</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <?php if ($user != 'guest') : ?>
                    <li class="dropdown">
                        <a class="btn btn-success nav-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo h($user["user_name"]) ?>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="/HEW/cart/">cart</a>
                            <a class="dropdown-item" href="#">setting</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="/HEW/login/logout.php">logout</a>
                        </div>
                    </li>
                <?php else : ?>
                    <a href="/HEW/login/index.php" class="btn btn-success">ログイン/登録</a>
                <?php endif; ?>
            </ul>

        </div>
    </nav>
    <div class="row container-fluid">
        <div class="col-2 py-5 border-right">
            <nav class="nav mt-3 flex-column">
                <a class="nav-link active" href="#">設定</a>
                <a class="nav-link" href="./purchased.php">購入済み</a>
            </nav>

        </div>
        <div class="row col-9 justify-content-left py-3 mx-auto">
            <h2 class="col-12 font-weight-bold mt-4">設定</h2>
            <div class=" col-10 ml-5 mt-3">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <div class="form-group mb-4">
                        <label for="user_name">ユーザーネーム</label>
                        <input type="text" name="user_name" id="user_name" class="form-control col-10" value="<?php echo h($user['user_name']) ?>">
                    </div>
                    <div class="form-group mb-4">
                        <label for="user_tname">本名</label>
                        <input type="text" name="user_tname" id="user_tname" class="form-control col-10" value="<?php echo h($user['user_tname']) ?>">
                    </div>
                    <div class="form-group mb-4">
                        <label for="user_email">メールアドレス</label>
                        <input type="mail" name="user_email" id="user_email" class="form-control col-10" value="<?php echo h($user['user_email']) ?>">
                    </div>
                    <div class="form-group mb-4">
                        <label for="user_email">生年月日</label>
                        <input type="date" name="user_birthday" id="user_birthday" class="form-control col-10" value="<?php if ($user['user_birthday'] != null) echo h($user['user_birthday']) ?>">
                    </div>
                    <div class="form-group mb-4">
                        <label for="user_card">クレジットカード番号</label>
                        <input type="text" name="user_card" id="user_card" class="form-control col-10" value="<?php if ($user['user_card'] != null) echo h($user['user_card']) ?>">
                    </div>
                    <div class="form-group mb-4">
                        <label for="user_card_limit">有効期限</label>
                        <input type="month" name="user_card_limit" id="user_card_limit" class="form-control col-10" value="<?php echo h($user['user_card_limit']); ?>">
                    </div>

                    <input type="submit" class="btn btn-primary ml-auto" value="変更">
                </form>

            </div>
        </div>

    </div>

    <!-- Footer section -->
    <footer class="footer mt-auto py-3 bg-dark clearfix">
        <div class="container">
            <span class="text-muted">
                Copyright &copy;<script>
                    document.write(new Date().getFullYear());
                </script> All rights reserved
            </span>
        </div>
    </footer>
    <!-- Footer section end -->


    <!--====== Javascripts & Jquery ======-->
    <script src="/HEW/js/jquery-3.2.1.min.js"></script>
    <script src="/HEW/js/popper.min.js"></script>
    <script src="/HEW/js/bootstrap.min.js"></script>
    <script src="/HEW/js/owl.carousel.min.js"></script>
    <script src="/HEW/js/jquery.marquee.min.js"></script>
    <script src="/HEW/js/main.js"></script>

</body>

</html>
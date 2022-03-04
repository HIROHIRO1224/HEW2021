<?php

session_start();
require_once './mod/DBA.php';
require_once './mod/LoginClass.php';
require_once './mod/module.php';

$user = 'guest';
$items = '';
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
    }
    $columns = '';
    if (!empty($_POST)) {
        # code...
        $condition = "item_name like ? or item_category like ? or item_corporate like ?;";
        $params = ['%' . $_POST['item_name'] . '%', '%' . $_POST['item_name'] . '%', '%' . $_POST['item_name'] . '%'];
        $columns = $dba->SELECT("t_items", DBA::ALL, DBA::NUMVALUE, $condition, $params);
    } else {
        $columns = $dba->SELECT('t_items', DBA::ALL, DBA::ALL);
    }
} catch (PDOException $e) {
    throw $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <title>Playground</title>
    <meta charset="UTF-8">
    <meta name="description" content="Game Warrior Template">
    <meta name="keywords" content="warrior, game, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon -->
    <link href="/HEW/img/Logo.png" rel="shortcut icon" type="image/png" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/font-awesome.min.css" />
    <link rel="stylesheet" href="css/owl.carousel.css" />
    <!-- <link rel="stylesheet" href="css/style.css" /> -->
    <link rel="stylesheet" href="css/animate.css" />


</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3">
        <div class="container">
            <a href="/HEW/" class="navbar-brand">
                <!-- <a class="navbar-brand pl-1" href="#" style="background-image: url('./img/Logo.png'); background-repeat: no-repeat; background-size: contain;"> -->
                <img src="./img/Logo.png" width="28" height="30" class="d-inline-block align-top" alt="">
                Playground
            </a>

            <!-- この下の行に mr-auto クラスを付与するだけ -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Search</a>
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
                    <a href="./login/index.php" class="btn btn-success">ログイン/登録</a>
                <?php endif; ?>
            </ul>

        </div>
    </nav>

    <main>
        <div class="container-fluid">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="form-row justify-content-center">
                    <div class="col-6 my-5">
                        <input type="text" name="item_name" class="form-control" placeholder="商品名" value="<?php if (!empty($_POST['item_name'])) echo h($_POST['item_name']) ?>">
                    </div>
                    <div class="col-1 my-5">
                        <input type="submit" value="検索" class="form-control btn btn-primary mb-2">
                    </div>
                </div>
            </form>
            <p class="text-center"><?php echo h(count($columns)) ?>件表示中</p>

            <div class="row justify-content-center">

                <?php foreach ($columns as $column) : ?>
                    <div class="card mx-3 mb-5" style="width: 18rem;">
                        <img class="card-img-top" src="./img/item/<?php echo h($column['item_image']) ?>" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title text-truncate"><?php echo h($column['item_name']) ?></h5>
                            <p class=""><?php echo h($column['item_category']) ?></p>
                            <p class="card-text">￥<?php echo h($column['item_price']) ?></p>
                            <a href="/HEW/itempage/item<?php echo h($column['item_id']) ?>/" class="btn btn-link">詳しくみる</a>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </main>
    <!-- Footer section -->
    <footer class="footer mt-auto py-3 bg-dark">
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
    <script src="js/popper.min.js"></script>
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.marquee.min.js"></script>
    <script src="js/main.js"></script>

</body>

</html>
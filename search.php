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

    if (isset($_SESSION['user_id']) && $_SESSION['time'] + 3600 > time()) { // 時間内に行動した場合
        # code...
        $_SESSION['time'] = time();
        $condition = 'user_id = ?;';
        $params = [$_SESSION['user_id']];
        $columns = $dba->SELECT('t_users', DBA::ALL, DBA::NUMVALUE, $condition, $params);
        $user = $columns[0];
    } elseif (isset($_SESSION['user_id']) && $_SESSION['time'] + 3600 < time()) { // セッションで指定した時間操作してない場合
        # code...
        $loginclass = new LoginClass();
        $result = $loginclass->login($_COOKIE['user_data'], $_COOKIE['password']);
        if (!$result) {
            # code...
            header("Location:/HEW/login/?error=timeout");
            exit;
        }
    } else { // その他
        header("Location:/HEW/login/");
        exit;
    }
    $columns = [];
    $user_cart = [];
    $user_purchased = [];

    $user_cart = explode(',', $user['user_cart']);
    $user_purchased = explode(',', $user['user_purchased']);

    if (!empty($_POST)) {
        # code...
        $condition = "item_name like ? or item_category like ? or item_corporate like ?;";
        $params = ['%' . $_POST['item_name'] . '%', '%' . $_POST['item_name'] . '%', '%' . $_POST['item_name'] . '%'];
        $t_items = $dba->SELECT("t_items", DBA::ALL, DBA::NUMVALUE, $condition, $params);

        $status = '';
        foreach ($t_items as $t) {
            $status = '';
            # code...
            if ($user_cart[0] != '') {
                # code...
                for ($i = 0; $i < count($user_cart); $i++) {
                    # code...
                    if ($user_cart[$i] == $t['item_id']) {
                        # code...
                        $status = 'cart_in';
                        break;
                    }
                }
            }
            if ($user_purchased[0] != '') {
                # code...
                for ($i = 0; $i < count($user_purchased); $i++) {
                    # code...
                    if ($user_purchased[$i] == $t['item_id']) {
                        # code...
                        $status = 'purchased';
                        break;
                    }
                }
            }
            if ($user_cart[0] == '' && $user_purchased[0] == '') {
                $status = '';
            }

            # code...

            array_push($columns, [
                'item_id' => $t['item_id'],
                'item_name' => $t['item_name'],
                'item_price' => $t['item_price'],
                'item_category' => $t['item_category'],
                'item_corporate' => $t['item_corporate'],
                'item_url' => $t['item_url'],
                'item_sold' => $t['item_sold'],
                'item_image' => $t['item_image'],
                'item_registered' => $t['item_registered'],
                'item_status' => $status,
            ]);
        }
    } else {
        $t_items = $dba->SELECT('t_items', DBA::ALL, DBA::ALL);
        $status = '';
        foreach ($t_items as $t) {
            $status = '';
            # code...
            if ($user_cart[0] != '') {
                # code...
                for ($i = 0; $i < count($user_cart); $i++) {
                    # code...
                    if ($user_cart[$i] == $t['item_id']) {
                        # code...
                        $status = 'cart_in';
                        break;
                    }
                }
            }
            if ($user_purchased[0] != '') {
                # code...
                for ($i = 0; $i < count($user_purchased); $i++) {
                    # code...
                    if ($user_purchased[$i] == $t['item_id']) {
                        # code...
                        $status = 'purchased';
                        break;
                    }
                }
            }
            if ($user_cart[0] == '' && $user_purchased[0] == '') {
                $status = '';
            }

            # code...

            array_push($columns, [
                'item_id' => $t['item_id'],
                'item_name' => $t['item_name'],
                'item_price' => $t['item_price'],
                'item_category' => $t['item_category'],
                'item_corporate' => $t['item_corporate'],
                'item_url' => $t['item_url'],
                'item_sold' => $t['item_sold'],
                'item_image' => $t['item_image'],
                'item_registered' => $t['item_registered'],
                'item_status' => $status,
            ]);
        }
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
                <img src="/HEW/img/Logo.png" width="28" height="30" class="d-inline-block align-top" alt="">
                Playground
            </a>

            <!-- この下の行に mr-auto クラスを付与するだけ -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="./search.php">Search</a>
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
                            <a href="/HEW/mypage/" class="dropdown-item">ユーザー設定</a>
                            <a class="dropdown-item" href="/HEW/cart/">カート</a>
                            <a class="dropdown-item text-dark" href="/HEW/mypage/purchased.php">購入済み</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="/HEW/login/logout.php">ログアウト</a>
                        </div>
                    </li>
                <?php else : ?>
                    <a href="/HEW/login/" class="btn btn-success">ログイン/登録</a>
                <?php endif; ?>
            </ul>

        </div>
    </nav>


    <main>
        <div class="container-fluid">
            <?php if (!empty($_REQUEST['cart_result'])) {
                if ($_REQUEST['cart_result'] == 'success') {
                    # code...
            ?>
                    <div class="row justify-content-center mt-3">
                        <div class="alert alert-success mx-auto col-6">
                            カートに追加しました
                            <a href="/HEW/cart/" class="btn btn-link">カートへ</a>
                        </div>
                    </div>
            <?php
                }
            } ?>
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
                    <div class="card mx-4 mb-5 rounded" style="width: 16rem;">
                        <img class="card-img-top rounded-top" src="./img/item/<?php echo h($column['item_image']) ?>" alt="Card image cap">
                        <div class="card-body">
                            <a href="/HEW/itempage/item<?php echo h($column['item_id']) ?>/" class="text-dark">
                                <h5 class="card-title text-truncate"><?php echo h($column['item_name']) ?></h5>
                            </a>
                            <p class="card-text"><?php echo h($column['item_category']) ?></p>
                            <p class="card-text">￥<?php echo h($column['item_price']) ?></p>
                            <?php if ($column['item_status'] == 'cart_in') : ?>
                                <a href="/HEW/cart.php?action=add&sender=/HEW/search.php&item_id=<?php echo h($column["item_id"]) ?>" class="btn btn-primary disabled">追加済み</a>
                            <?php elseif ($column['item_status'] == 'purchased') : ?>
                                <a href="/HEW/cart.php?action=add&sender=/HEW/search.php&item_id=<?php echo h($column["item_id"]) ?>" class="btn btn-success disabled">購入済み</a>
                            <?php elseif ($column['item_status'] == '') : ?>
                                <a href="/HEW/cart.php?action=add&sender=/HEW/search.php&item_id=<?php echo h($column["item_id"]) ?>" class="btn btn-primary">カートに入れる</a>
                            <?php endif; ?>
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
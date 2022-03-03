<?php

session_start();

require_once '../mod/DBA.php';
require_once '../mod/LoginClass.php';
require_once '../mod/module.php';
$user = 'guest';

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
    }

    $cart = explode(',', $user['user_cart']);


    $t_items = $dba->SELECT('t_items', DBA::ALL, DBA::ALL);

    $total_price = 0;
    for ($i = 0; $i < count($cart); $i++) {
        # code...
        $total_price += $t_items[$i + 1]["item_price"];
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

    <title>Document</title>
</head>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3">
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
                        <a class="dropdown-item" href="./login/logout.php">logout</a>
                    </div>
                </li>
            <?php else : ?>
                <a href="./login/index.php" class="btn btn-success">ログイン/登録</a>
            <?php endif; ?>
        </ul>

    </div>
</nav>

<body>
    <main>
        <div class="container-fluid my-5 clearfix">
            <div class="row justify-content-left">
                <a class="col-2 font-weight-bold btn btn-link mb-3" href="/HEW/search.php">＜ 戻る</a>
            </div>
            <div class="row col-12 justify-content-center">
                <div class="col-10">
                    <h2 class="font-weight-bold">ショッピングカート</h2>
                </div>

            </div>
            <div class="row col-8 justify-content-center float-left">
                <?php if ($cart == false) : ?>
                    <h2>カートに何も入ってません</h2>
                <?php else : ?>
                    <?php for ($i = 1; $i < count($cart) + 1; $i++) : ?>

                        <div class="col-11 mt-5 row justify-content-center ml-5">
                            <div class="row col-12 py-4 border rounded my-1">
                                <img class="img-fluid col-4" src="/HEW/img/item/<?php echo h($t_items[$i]['item_image']) ?>" alt="">
                                <div class="col-6 row">
                                    <h4 class="col-12 py-3"><?php echo h($t_items[$i]['item_name']) ?></h4>
                                    <h6 class="col-12 mx-auto"><?php echo h($t_items[$i]['item_corporate']) ?></h6>
                                </div>
                                <div class="col-1 py-3">
                                    <h5>￥<?php echo h($t_items[$i]['item_price']) ?></h5>
                                </div>
                                <button type="button" class="close col-1 ml-auto" data-toggle="modal" data-target="#item_id<?php echo h($t_items[$i]['item_id']) ?>">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>

                    <?php endfor; ?>
                <?php endif; ?>


            </div>
            <div class="justify-content-center float-left col-3 p-3 border ml-5 my-5">
                <div class="col-12">
                    <h6 class="">小計 (<?php echo h(count($cart)) ?>個の商品) (税込)</h6>
                    <h2 class="font-weight-bold">￥<?php echo h($total_price); ?></h4>
                </div>
                <hr class="col-10">
                <div href="" class="col-12 btn btn-primary">
                    レジへ進む
                </div>

            </div>
        </div>

        </div>

    </main>

    <!-- modal area -->
    <?php if ($cart != false) : ?>
        <?php for ($i = 1; $i < count($cart) + 1; $i++) : ?>
            <div class="modal" id="item_id<?php echo h($t_items[$i]['item_id']) ?>" tabindex="-1" role="dialog" aria-labelledby="label2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title" id="label1">確認</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            カートから削除しますか？
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <a class="btn btn-primary" href="/HEW/cart.php?action=delete&sender=/HEW/cart/">OK</a>
                        </div>
                    </div>
                </div>
            </div>

        <?php endfor; ?>
    <?php endif; ?>
    <!-- modal area end -->


    <!-- Footer section -->
    <footer class=" footer mt-auto py-3 bg-dark clearfix">
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
    <script src="/HEW/js/popper.min.js"></script>
    <script src="/HEW/js/jquery-3.2.1.min.js"></script>
    <script src="/HEW/js/bootstrap.min.js"></script>
    <script src="/HEW/js/owl.carousel.min.js"></script>
    <script src="/HEW/js/jquery.marquee.min.js"></script>
    <script src="/HEW/js/main.js"></script>
</body>

</html>
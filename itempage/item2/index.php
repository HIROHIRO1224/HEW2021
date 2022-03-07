<?php

session_start();
include_once '../../mod/DBA.php';
include_once '../../mod/LoginClass.php';
include_once '../../mod/module.php';

$user = 'guest';

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
    $column = [];
    $columns = $dba->SELECT('t_items', DBA::ALL, DBA::NUMVALUE, 'item_id = ?', [2]);


    $user_cart = explode(',', $user['user_cart']);
    $user_purchased = explode(',', $user['user_purchased']);


    $status = '';
    # code...
    if ($user_cart[0] != '') {
        # code...
        for ($j = 0; $j < count($user_cart); $j++) {
            # code...
            if ($user_cart[$j] == $columns[0]['item_id']) {
                # code...
                $status = 'cart_in';
                break;
            }
        }
    }
    if ($user_purchased[0] != '') {
        # code...
        for ($j = 0; $j < count($user_purchased); $j++) {
            # code...
            if ($user_purchased[$j] == $columns[0]['item_id']) {
                # code...
                $status = 'purchased';
                break;
            }
        }
    }
    if ($user_cart[0] == '' && $user_purchased[0] == '') {
        $status = '';
    }


    $column = [
        'item_id' => $columns[0]['item_id'],
        'item_name' => $columns[0]['item_name'],
        'item_price' => $columns[0]['item_price'],
        'item_category' => $columns[0]['item_category'],
        'item_corporate' => $columns[0]['item_corporate'],
        'item_url' => $columns[0]['item_url'],
        'item_sold' => $columns[0]['item_sold'],
        'item_image' => $columns[0]['item_image'],
        'item_registered' => $columns[0]['item_registered'],
        'item_status' => $status,
    ];
} catch (PDOException $e) {
    throw $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <title>ナビつき! つくってわかる はじめてゲームプログラミング</title>
    <meta charset="UTF-8">
    <meta name="description" content="Game Warrior Template">
    <meta name="keywords" content="warrior, game, creative, html">
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
        <div class="container-fluid my-4">
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

            <div class="row justify-content-left">
                <a class="col-2 font-weight-bold btn btn-link mb-3" href="/HEW/search.php">＜ 戻る</a>
            </div>
            <div class="row justify-content-center">
                <img class="img-fluid col-3 mx-3" src="./img/<?php echo h($column['item_image']) ?>" alt="">
                <div class="col-6 row mx-3">
                    <h2 class="font-weight-bold col-12"><?php echo h($column['item_name']) ?></h2>
                    <h6 class="col-12"><?php echo h($column['item_corporate']) ?></h6>
                    <h6 class="col-12"><?php echo h($column['item_category']) ?></h6>
                    <h4 class="col-6">☆ <?php echo h($column['item_sold']) ?></h4>
                    <h4 class="col-1">￥<?php echo h($column['item_price']) ?></h4>
                    <div class="col-3 mx-5">
                        <?php if ($column['item_status'] == 'cart_in') : ?>
                            <a href="/HEW/cart.php?action=add&sender=/HEW/mypage/purchased.php&item_id=<?php echo h($column["item_id"]) ?>" class="btn btn-primary disabled">追加済み</a>
                        <?php elseif ($column['item_status'] == 'purchased') : ?>
                            <a href="/HEW/cart.php?action=add&sender=/HEW/mypage/purchased.php&item_id=<?php echo h($column["item_id"]) ?>" class="btn btn-success disabled">購入済み</a>
                        <?php elseif ($column['item_status'] == '') : ?>
                            <a href="/HEW/cart.php?action=add&sender=/HEW/mypage/purchased.php&item_id=<?php echo h($column["item_id"]) ?>" class="btn btn-primary">カートに入れる</a>
                        <?php endif; ?>
                    </div>
                </div>
                <hr class="col-11">
                <h2 class="font-weight-bold col-11">商品説明</h2>
                <h3 class="col-9 mt-5 font-weight-bold mb-3">
                    任天堂の開発室から生まれたプログラミングソフト
                </h3>
                <img class="col-8 my-3" src="./img/aeac9ccf-29ca-4cfc-816d-37f95dcb9949.__CR0,0,1280,792_PT0_SX970_V1___.jpg" alt="">
                <p class="col-9" style="line-height:2rem">
                    任天堂の開発室から生まれたプログラミングソフト<br>
                    ゲームをあそぶのは楽しいけど、つくるのも楽しい。<br>
                    ナビがついているから、あんしん、カンタン。だれでもつくってあそべる、ゲームプログラミングを体験してみませんか？<br>
                    ナビゲーションに従って、不思議な生き物「ノードン」をつなげるだけで、ゲームプログラミングがお楽しみいただけるソフトです。<br>
                    「ナビつきレッスン」と「フリープログラミング」の2つのモードを収録しています。
                </p>
                <h3 class="col-9 mt-5 font-weight-bold mb-3">
                    「ナビつきレッスン」でだれでもゲームプログラミング体験！
                </h3>
                <img class="col-8 my-3" src="./img/9fd768a1-fa6b-4995-884f-af703119cff6.__CR174,0,1746,1080_PT0_SX970_V1___.jpg" alt="">
                <p class="col-9" style="line-height:2rem">
                    「ナビつきレッスン」では、7つのレッスンを通じていろんなゲームをプログラミングすることができます。<br>
                    ナビゲーションがついているので、だれでもゲームを完成させることができ、<br>
                    プログラミングのしくみやテクニックを楽しく身に着けられます。<br>
                </p>
                <h3 class="col-9 mt-5 font-weight-bold mb-3">
                    フリープログラミング
                </h3>
                <img class="col-8 my-3" src="./img/e81e64d4-6a21-4f8c-90f3-73b7e37830eb.__CR61,0,1164,720_PT0_SX970_V1___.jpg" alt="">
                <p class="col-9" style="line-height:2rem">
                    「フリープログラミング」で自由にゲームづくり<br>
                    「フリープログラミング」では、「ナビつきレッスン」で身に着けたテクニックを使って、<br>
                    自分で描いたキャラクターを操作できるようにしたり、絵を描いて背景にしたり、BGMをつけたりと、<br>
                    自由にゲームをプログラミングすることができます。<br>
                    また、つくったゲームはインターネットやローカル通信で友だちに共有することもできます。<br>
                </p>
            </div>
        </div>
    </main>

    <!-- Footer section -->
    <footer class=" footer mt-auto py-3 bg-dark">
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
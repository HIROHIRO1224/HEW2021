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

    $item_purchased = [];
    $user_cart = explode(',', $user['user_cart']);
    $user_purchased = explode(',', $user['user_purchased']);


    if (!empty($user['user_purchased'])) {
        # code...
        for ($i = 0; $i < count($user_purchased); $i++) {
            # code...            
            $columns = $dba->SELECT('t_items', DBA::ALL, DBA::NUMVALUE, 'item_id = ?', [$user_purchased[$i]]);



            $item_purchased[$i] = $columns[0];
        }
    }


    $item_recommend = [];
    for ($i = 0; $i < 3; $i++) {
        # code...
        $columns = $dba->SELECT('t_items', DBA::ALL, DBA::NUMVALUE, 'item_id = ?', [rand(1, 8)]);
        $t_item = $columns[0];

        $status = '';
        # code...
        if ($user_cart[0] != '') {
            # code...
            for ($j = 0; $j < count($user_cart); $j++) {
                # code...
                if ($user_cart[$j] == $t_item['item_id']) {
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
                if ($user_purchased[$j] == $t_item['item_id']) {
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

        array_push($item_recommend, [
            'item_id' => $t_item['item_id'],
            'item_name' => $t_item['item_name'],
            'item_price' => $t_item['item_price'],
            'item_category' => $t_item['item_category'],
            'item_corporate' => $t_item['item_corporate'],
            'item_url' => $t_item['item_url'],
            'item_sold' => $t_item['item_sold'],
            'item_image' => $t_item['item_image'],
            'item_registered' => $t_item['item_registered'],
            'item_status' => $status,
        ]);
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
    <div class="row container-fluid">
        <div class="col-2 py-5 border-right">
            <nav class="nav mt-3 flex-column">
                <a class="nav-link active" href="./">設定</a>
                <a class="nav-link" href="#">購入済み</a>
            </nav>

        </div>
        <div class="row col-9 justify-content-left py-3 mx-auto">
            <h2 class="col-12 font-weight-bold mt-4">購入済み</h2>
            <div class=" col-12 ml-5 mt-3">

                <?php if (!empty($user_purchased[0])) : ?>
                    <div class="row col-11 justify-content-center float-left">

                        <?php for ($i = 0; $i < count($item_purchased); $i++) : ?>

                            <div class="col-11 mb-3 row justify-content-center ml-5">
                                <div class="row col-12 py-4 border rounded mb-1">
                                    <img class="img-fluid col-4" src="/HEW/img/item/<?php echo h($item_purchased[$i]['item_image']) ?>" alt="">
                                    <div class="col-6 row">
                                        <a href="<?php echo h($item_purchased[$i]['item_url']); ?>/">
                                            <h4 class="col-12 py-3"><?php echo h($item_purchased[$i]['item_name']) ?></h4>
                                        </a>
                                        <h6 class="col-12 mx-auto"><?php echo h($item_purchased[$i]['item_corporate']) ?></h6>
                                    </div>
                                    <div class="col-1 py-3">
                                        <h5>￥<?php echo h($item_purchased[$i]['item_price']) ?></h5>
                                    </div>
                                </div>
                            </div>

                        <?php endfor; ?>
                    </div>

                    <div class="row my-3 col-12 justify-content-center float-left">
                        <h4 class="col-12 text-center font-weight-bold mt-5 mb-3">おすすめ商品</h4>
                        <div class="card mx-3 mb-5" style="width: 18rem;">
                            <img class="card-img-top" src="/HEW/img/item/<?php echo h($item_recommend[0]['item_image']) ?>" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title text-truncate">
                                    <a href="/HEW/itempage/item<?php echo h($item_recommend[0]['item_id']); ?>/">
                                        <?php echo h($item_recommend[0]['item_name']) ?>
                                    </a>
                                </h5>
                                <p class=""><?php echo h($item_recommend[0]['item_category']) ?></p>
                                <p class="card-text">￥<?php echo h($item_recommend[0]['item_price']) ?></p>
                                <?php if ($item_recommend[0]['item_status'] == 'cart_in') : ?>
                                    <a href="/HEW/cart.php?action=add&sender=/HEW/mypage/purchased.php&item_id=<?php echo h($item_recommend[0]["item_id"]) ?>" class="btn btn-primary disabled">追加済み</a>
                                <?php elseif ($item_recommend[0]['item_status'] == 'purchased') : ?>
                                    <a href="/HEW/cart.php?action=add&sender=/HEW/mypage/purchased.php&item_id=<?php echo h($item_recommend[0]["item_id"]) ?>" class="btn btn-success disabled">購入済み</a>
                                <?php elseif ($item_recommend[0]['item_status'] == '') : ?>
                                    <a href="/HEW/cart.php?action=add&sender=/HEW/mypage/purchased.php&item_id=<?php echo h($item_recommend[0]["item_id"]) ?>" class="btn btn-primary">カートに入れる</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card mx-3 mb-5" style="width: 18rem;">
                            <img class="card-img-top" src="/HEW/img/item/<?php echo h($item_recommend[1]['item_image']) ?>" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title text-truncate">
                                    <a href="/HEW/itempage/item<?php echo h($item_recommend[1]['item_id']); ?>/">
                                        <?php echo h($item_recommend[1]['item_name']) ?>
                                    </a>
                                </h5>
                                <p class=""><?php echo h($item_recommend[1]['item_category']) ?></p>
                                <p class="card-text">￥<?php echo h($item_recommend[1]['item_price']) ?></p>
                                <?php if ($item_recommend[1]['item_status'] == 'cart_in') : ?>
                                    <a href="/HEW/cart.php?action=add&sender=/HEW/mypage/purchased.php&item_id=<?php echo h($item_recommend[1]["item_id"]) ?>" class="btn btn-primary disabled">追加済み</a>
                                <?php elseif ($item_recommend[1]['item_status'] == 'purchased') : ?>
                                    <a href="/HEW/cart.php?action=add&sender=/HEW/mypage/purchased.php&item_id=<?php echo h($item_recommend[1]["item_id"]) ?>" class="btn btn-success disabled">購入済み</a>
                                <?php elseif ($item_recommend[1]['item_status'] == '') : ?>
                                    <a href="/HEW/cart.php?action=add&sender=/HEW/mypage/purchased.php&item_id=<?php echo h($item_recommend[1]["item_id"]) ?>" class="btn btn-primary">カートに入れる</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card mx-3 mb-5" style="width: 18rem;">
                            <img class="card-img-top" src="/HEW/img/item/<?php echo h($item_recommend[2]['item_image']) ?>" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title text-truncate">
                                    <a href="/HEW/itempage/item<?php echo h($item_recommend[2]['item_id']); ?>/">
                                        <?php echo h($item_recommend[2]['item_name']) ?>
                                    </a>
                                </h5>
                                <p class=""><?php echo h($item_recommend[2]['item_category']) ?></p>
                                <p class="card-text">￥<?php echo h($item_recommend[2]['item_price']) ?></p>
                                <?php if ($item_recommend[2]['item_status'] == 'cart_in') : ?>
                                    <a href="/HEW/cart.php?action=add&sender=/HEW/mypage/purchased.php&item_id=<?php echo h($item_recommend[2]["item_id"]) ?>" class="btn btn-primary disabled">追加済み</a>
                                <?php elseif ($item_recommend[2]['item_status'] == 'purchased') : ?>
                                    <a href="/HEW/cart.php?action=add&sender=/HEW/mypage/purchased.php&item_id=<?php echo h($item_recommend[2]["item_id"]) ?>" class="btn btn-success disabled">購入済み</a>
                                <?php elseif ($item_recommend[2]['item_status'] == '') : ?>
                                    <a href="/HEW/cart.php?action=add&sender=/HEW/mypage/purchased.php&item_id=<?php echo h($item_recommend[2]["item_id"]) ?>" class="btn btn-primary">カートに入れる</a>
                                <?php endif; ?>
                            </div>
                        </div>


                    </div>

                <?php else : ?>
                    <div class="row col-12 mx-auto my-3 justify-content-center">
                        <div class="col-12 border mx-auto justify-content-center">
                            <h6 class="col-12 text-center mt-5">まだ何も購入していません</h6>
                            <h6 class="col-12 text-center mb-3">あなたにお気に入りのゲームを見つけにいきましょう</h6>
                            <div class="col-2 mx-auto mt-4 mb-5">
                                <a class="btn btn-primary mb-3" href="/HEW/search.php">買い物を続ける</a>
                            </div>

                        </div>
                        <div class="row my-3 col-12 justify-content-center float-center">
                            <h4 class="col-12 text-center font-weight-bold mt-5 mb-3">おすすめ商品</h4>
                            <div class="card mx-3 mb-5" style="width: 15rem;">
                                <img class="card-img-top" src="/HEW/img/item/<?php echo h($item_recommend[0]['item_image']) ?>" alt="Card image cap">
                                <div class="card-body">
                                    <h5 class="card-title text-truncate">
                                        <?php echo h($item_recommend[0]['item_name']) ?>
                                    </h5>
                                    <p class=""><?php echo h($item_recommend[0]['item_category']) ?></p>
                                    <p class="card-text">￥<?php echo h($item_recommend[0]['item_price']) ?></p>
                                    <a href="/HEW/itempage/item<?php echo h($item_recommend[0]['item_id']) ?>/" class="btn btn-link">詳しくみる</a>
                                </div>
                            </div>
                            <div class="card mx-3 mb-5" style="width: 15rem;">
                                <img class="card-img-top" src="/HEW/img/item/<?php echo h($item_recommend[1]['item_image']) ?>" alt="Card image cap">
                                <div class="card-body">
                                    <h5 class="card-title text-truncate">
                                        <?php echo h($item_recommend[1]['item_name']) ?>
                                    </h5>
                                    <p class=""><?php echo h($item_recommend[1]['item_category']) ?></p>
                                    <p class="card-text">￥<?php echo h($item_recommend[1]['item_price']) ?></p>
                                    <a href="/HEW/itempage/item<?php echo h($item_recommend[1]['item_id']) ?>/" class="btn btn-link">詳しくみる</a>
                                </div>
                            </div>
                            <div class="card mx-3 mb-5" style="width: 15rem;">
                                <img class="card-img-top" src="/HEW/img/item/<?php echo h($item_recommend[2]['item_image']) ?>" alt="Card image cap">
                                <div class="card-body">
                                    <h5 class="card-title text-truncate">
                                        <?php echo h($item_recommend[2]['item_name']) ?>
                                    </h5>
                                    <p class=""><?php echo h($item_recommend[2]['item_category']) ?></p>
                                    <p class="card-text">￥<?php echo h($item_recommend[2]['item_price']) ?></p>
                                    <a href="/HEW/itempage/item<?php echo h($item_recommend[2]['item_id']) ?>/" class="btn btn-link">詳しくみる</a>
                                </div>
                            </div>


                        </div>
                    </div>
                <?php endif; ?>


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
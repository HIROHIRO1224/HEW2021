<?php

session_start();

require_once '../mod/DBA.php';
require_once '../mod/LoginClass.php';
require_once '../mod/module.php';

$user = 'guest';
try {
    //code...
    $dba = new DBA('root', '', 'HEW', 'localhost');
    $t_items = $dba->SELECT('t_items', DBA::ALL, DBA::ALL);

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
    $cart = explode(',', $user['user_cart']);
    if (!empty($cart[0])) {

        $total_price = 0;
        for ($i = 0; $i < count($cart); $i++) {
            # code...
            $total_price += $t_items[$cart[$i] - 1]["item_price"];
        }
    }
    $j = 0;
    $randitem = [rand(0, count($t_items) - 1), rand(0, count($t_items) - 1), rand(0, count($t_items) - 1)];
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

    <title>Playground</title>
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
    <main>
        <div class="container-fluid my-5 pl-5 clearfix">
            <!-- <div class="row justify-content-left">
                <a class="col-2 font-weight-bold btn btn-link mb-3" href="/HEW/search.php">＜ 戻る</a>
            </div> -->
            <div class="row col-12 justify-content-center">
                <div class="col-10">
                    <h2 class="font-weight-bold">ショッピングカート</h2>
                </div>

            </div>
            <?php if (!empty($cart[0])) : ?>
                <div class="row col-8 justify-content-center float-left">
                    <h4 class="mt-3 col-10"><?php echo h(count($cart)) ?>件表示中</h4>

                    <?php for ($i = 0; $i < count($cart); $i++) : ?>

                        <div class="col-11 mb-3 row justify-content-center ml-5">
                            <div class="row col-12 py-4 border rounded mb-1">
                                <img class="img-fluid col-4" src="/HEW/img/item/<?php echo h($t_items[$cart[$i] - 1]['item_image']) ?>" alt="">
                                <div class="col-6 row">
                                    <a href="/HEW/itempage/item<?php echo h($t_items[$cart[$i] - 1]['item_id']); ?>/">
                                        <h4 class="col-12 py-3"><?php echo h($t_items[$cart[$i] - 1]['item_name']) ?></h4>
                                    </a>
                                    <h6 class="col-12 mx-auto"><?php echo h($t_items[$cart[$i] - 1]['item_corporate']) ?></h6>
                                </div>
                                <div class="col-1 py-3">
                                    <h5>￥<?php echo h($t_items[$cart[$i] - 1]['item_price']) ?></h5>
                                </div>
                                <button type="button" class="close col-1 ml-auto" data-toggle="modal" data-target="#item_id<?php echo h($t_items[$cart[$i] - 1]['item_id']) ?>">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>

                    <?php endfor; ?>
                </div>
                <div class="justify-content-center float-left col-3 p-3 border ml-5 mt-5">
                    <div class="col-12">
                        <h6 class="">小計 (<?php echo h(count($cart)) ?>個の商品) (税込)</h6>
                        <h2 class="font-weight-bold">￥<?php echo h($total_price); ?></h4>
                    </div>
                    <hr class="col-10">
                    <div href="" class="col-12 btn btn-primary">
                        レジへ進む
                    </div>

                </div>

                <div class="row my-3 col-12 justify-content-center float-left">
                    <h4 class="col-12 text-center font-weight-bold mt-5 mb-3">おすすめ商品</h4>
                    <div class="card mx-3 mb-5" style="width: 18rem;">
                        <img class="card-img-top" src="/HEW/img/item/<?php echo h($t_items[$randitem[0]]['item_image']) ?>" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title text-truncate">
                                <?php echo h($t_items[$randitem[0]]['item_name']) ?>
                            </h5>
                            <p class=""><?php echo h($t_items[$randitem[0]]['item_category']) ?></p>
                            <p class="card-text">￥<?php echo h($t_items[$randitem[0]]['item_price']) ?></p>
                            <a href="/HEW/itempage/item<?php echo h($t_items[$randitem[0]]['item_id']) ?>/" class="btn btn-link">詳しくみる</a>
                        </div>
                    </div>

                    <div class="card mx-3 mb-5" style="width: 18rem;">
                        <img class="card-img-top" src="/HEW/img/item/<?php echo h($t_items[$randitem[1]]['item_image']) ?>" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title text-truncate">
                                <?php echo h($t_items[$randitem[1]]['item_name']) ?>
                            </h5>
                            <p class=""><?php echo h($t_items[$randitem[1]]['item_category']) ?></p>
                            <p class="card-text">￥<?php echo h($t_items[$randitem[1]]['item_price']) ?></p>
                            <a href="/HEW/itempage/item<?php echo h($t_items[$randitem[1]]['item_id']) ?>/" class="btn btn-link">詳しくみる</a>
                        </div>
                    </div>
                    <div class="card mx-3 mb-5" style="width: 18rem;">
                        <img class="card-img-top" src="/HEW/img/item/<?php echo h($t_items[$randitem[2]]['item_image']) ?>" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title text-truncate">
                                <?php echo h($t_items[$randitem[2]]['item_name']) ?>
                            </h5>
                            <p class=""><?php echo h($t_items[$randitem[2]]['item_category']) ?></p>
                            <p class="card-text">￥<?php echo h($t_items[$randitem[2]]['item_price']) ?></p>
                            <a href="/HEW/itempage/item<?php echo h($t_items[$randitem[2]]['item_id']) ?>/" class="btn btn-link">詳しくみる</a>
                        </div>
                    </div>
                </div>

            <?php else : ?>
                <div class="row col-8 mx-auto my-3 justify-content-center">
                    <h4 class="col-12">0件表示中</h4>
                    <div class="col-12 border justify-content-center">
                        <h6 class="col-12 text-center mt-5">カートに何も入っておりません</h6>
                        <h6 class="col-12 text-center mb-3">あなたに会うゲームを見つけにいきましょう</h6>
                        <div class="col-2 mx-auto mt-3 mb-5">
                            <a class="btn btn-primary mb-3" href="">買い物を続ける</a>

                        </div>

                    </div>
                    <div class="row my-3 col-12 justify-content-center">
                        <h4 class="col-12 text-center font-weight-bold">おすすめ商品</h4>
                        <div class="card mx-3 mb-5" style="width: 15rem;">
                            <img class="card-img-top" src="/HEW/img/item/<?php echo h($t_items[$randitem[0]]['item_image']) ?>" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title text-truncate">
                                    <?php echo h($t_items[$randitem[0]]['item_name']) ?>
                                </h5>
                                <p class=""><?php echo h($t_items[$randitem[0]]['item_category']) ?></p>
                                <p class="card-text">￥<?php echo h($t_items[$randitem[0]]['item_price']) ?></p>
                                <a href="/HEW/itempage/item<?php echo h($t_items[$randitem[0]]['item_id']) ?>/" class="btn btn-link">詳しくみる</a>
                            </div>
                        </div>

                        <div class="card mx-3 mb-5" style="width: 15rem;">
                            <img class="card-img-top" src="/HEW/img/item/<?php echo h($t_items[$randitem[1]]['item_image']) ?>" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title text-truncate">
                                    <?php echo h($t_items[$randitem[1]]['item_name']) ?>
                                </h5>
                                <p class=""><?php echo h($t_items[$randitem[1]]['item_category']) ?></p>
                                <p class="card-text">￥<?php echo h($t_items[$randitem[1]]['item_price']) ?></p>
                                <a href="/HEW/itempage/item<?php echo h($t_items[$randitem[1]]['item_id']) ?>/" class="btn btn-link">詳しくみる</a>
                            </div>
                        </div>
                        <div class="card mx-3 mb-5" style="width: 15rem;">
                            <img class="card-img-top" src="/HEW/img/item/<?php echo h($t_items[$randitem[2]]['item_image']) ?>" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title text-truncate">
                                    <?php echo h($t_items[$randitem[2]]['item_name']) ?>
                                </h5>
                                <p class=""><?php echo h($t_items[$randitem[2]]['item_category']) ?></p>
                                <p class="card-text">￥<?php echo h($t_items[$randitem[2]]['item_price']) ?></p>
                                <a href="/HEW/itempage/item<?php echo h($t_items[$randitem[2]]['item_id']) ?>/" class="btn btn-link">詳しくみる</a>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endif; ?>

        </div>



    </main>

    <!-- modal area -->
    <?php if ($cart[0] != false) : ?>
        <?php for ($i = 0; $i < count($cart); $i++) : ?>
            <div class="modal" id="item_id<?php echo h($t_items[$cart[$i] - 1]['item_id']) ?>" tabindex="-1" role="dialog" aria-labelledby="label2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content zindex-modal">
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
                            <a class="btn btn-primary" href="/HEW/cart.php?action=delete&sender=/HEW/cart/&item_id=<?php echo h($t_items[$cart[$i] - 1]['item_id']) ?>">OK</a>
                        </div>
                    </div>
                </div>
            </div>

        <?php endfor; ?>
    <?php endif; ?>
    <!-- modal area end -->


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
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
    $columns = '';
    $columns = $dba->SELECT('t_items', DBA::ALL, DBA::NUMVALUE, 'item_id = ?', [22]);

    // 追加部分
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

    // 追加部分終わり    
} catch (PDOException $e) {
    throw $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="ja">

<head>

    <!-- ここに商品名 -->
    <title>ペルソナ5 スクランブル ザ ファントム ストライカーズ</title>

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
                    <a href="/HEW/login/" class="btn btn-success">ログイン/登録</a>
                <?php endif; ?>
            </ul>

        </div>
    </nav>

    <main>
        <!-- 追加部分 -->
        <div class="container-fluid my-5">
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
            <!-- 追加部分　終わり -->
            <div class="container-fluid my-4">
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
                                <a href="/HEW/cart.php?action=add&sender=/HEW/itempage/item<?php echo h($column['item_id']) ?>/&item_id=<?php echo h($column["item_id"]) ?>" class="btn btn-primary disabled">追加済み</a>
                            <?php elseif ($column['item_status'] == 'purchased') : ?>
                                <a href="/HEW/cart.php?action=add&sender=/HEW/itempage/item<?php echo h($column['item_id']) ?>/&item_id=<?php echo h($column["item_id"]) ?>" class="btn btn-success disabled">購入済み</a>
                            <?php elseif ($column['item_status'] == '') : ?>
                                <a href="/HEW/cart.php?action=add&sender=/HEW/itempage/item<?php echo h($column['item_id']) ?>/&item_id=<?php echo h($column["item_id"]) ?>" class="btn btn-primary">カートに入れる</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <hr class="col-11">
                    <h2 class="font-weight-bold col-11">商品説明</h2>

                    <!-- ここから編集部分 -->
                    <h3 class="col-9 mt-5 font-weight-bold mb-3">
                        『ペルソナ５』の後日談が楽しめる、ペルソナシリーズ初のアクションRPG
                    </h3>
                    <img class="col-8 my-3" src="./img/perusona1.jpg" alt="">
                    <p class="col-9" style="line-height:2rem">
                        全世界累計セールス320万本を突破し、数多くのゲームアワードを受賞したピカレスク・ジュブナイルRPG『ペルソナ５（P5）』。<br>
                        TVアニメや音楽ライブ、舞台や漫画など様々なメディア展開も行われ更に拡大を続けるP5が、ファン待望のアクションRPGとなって登場
                    </p>


                    <h3 class="col-9 mt-5 font-weight-bold mb-3">
                        『ペルソナ５』の後日談を描く完全新作ストーリー
                    </h3>
                    <img class="col-8 my-3" src="./img/perusona2.jpg" alt="">
                    <p class="col-9" style="line-height:2rem">
                        『ペルソナ５』のエンディングから約半年後、夏休みに再集結した心の怪盗団は、新たな事件に巻き込まれていく…<br>
                        日本各地で謎の怪事件が発生。歪んだ大人の認知世界、パレスが復活？<br>
                        再び怪盗服を身にまとい、一同は新たな事件の解決に乗り出す…
                    </p>


                    <h3 class="col-9 mt-5 font-weight-bold mb-3">
                        好きなキャラクターで華麗にアクション
                    </h3>
                    <img class="col-8 my-3" src="./img/perusona3.jpg" alt="">
                    <p class="col-9" style="line-height:2rem">
                        本作では主人公（ジョーカー）だけでなく、ナビ以外の怪盗団メンバーを直接操作可能。<br>
                        戦況に応じてキャラクターを切り替えて戦おう。ペルソナを使った攻撃はもちろん、弱点を突いての1moreや総攻撃など、P5ならではのシステムも搭載。ペルソナを駆使したダイナミックなアクションで、シャドウだらけの街を華麗に駆け抜けろ。
                    </p>


                    <h3 class="col-9 mt-5 font-weight-bold mb-3">
                        合体や育成要素も
                    </h3>
                    <img class="col-8 my-3" src="./img/perusona4.jpg" alt="">
                    <p class="col-9" style="line-height:2rem">
                        シリーズお馴染みの「ペルソナ合体」も搭載。さらにストーリー進行やバトルを重ねることで、様々な要素でキャラクターが成長していく。<br>
                        敵を倒して経験を積むことでレベルアップするのはもちろん、その他にも様々な成長要素があるので、それらを駆使してお気に入りのキャラクターを自由に育てることが可能。RPGとアクションの二つの魅力を併せ持つ本作を隅々まで楽しもう。<br>
                        ※掲載されている画面写真は開発中のPlayStation4版のものです。
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
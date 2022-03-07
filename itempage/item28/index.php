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
    $columns = $dba->SELECT('t_items', DBA::ALL, DBA::NUMVALUE, 'item_id = ?', [28]);

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
    <title>真・女神転生III NOCTURNE HD REMASTER</title>

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
                        いま、ふたたび神話がよみがえる
                    </h3>
                    <img class="col-8 my-3" src="./img/megami1.jpg" alt="">
                    <p class="col-9" style="line-height:2rem">
                        シリーズ屈指の名作として伝説化したあの『真・女神転生III -NOCTURNE』が、Nintendo SwitchとPlayStation4で遂に復活。<br>
                        本作は『真・女神転生Ⅲ -NOCTURNE マニアクスクロニクルエディション』を基に“原作尊重”を前提に、グラフィックスやゲーム性を向上。<br>
                        見るものを惹きつけるアート性、現代社会にも通じる物語のメッセージ性、そしてRPGとしての完成度は、今もまったく色あせていない。<br>
                        ファンの方も、本作初プレイの方も、この圧倒的な悪魔体験を、いま是非。
                    </p>


                    <h3 class="col-9 mt-5 font-weight-bold mb-3">
                        ストーリー
                    </h3>
                    <img class="col-8 my-3" src="./img/megami2.jpg" alt="">
                    <p class="col-9" style="line-height:2rem">
                        200X年、東京。その日は、これからも続くありふれた日常の一片に過ぎないはずであった。<br>
                        主人公は、都内の高校に通う普通の少年。クラスメイトの新田勇、橘千晶と共ともに、入院する担任教師の高尾祐子を見舞うため、新宿にある病院を訪れた。不気味なほど人の気配が無く、異様な静寂が横たわる病院内で祐子を探すうち、少年は、面妖な装置の前に座す謎の男・氷川とはち合わせ、命の危険を覚える。その氷川を制止し、少年を守ったのは祐子だった。祐子は、悲しげな表情でこう告げる。<br>
                        間もなく、この世界は混沌に沈むの。<br>
                        それが「受胎」……<br>
                        人がかつて経験したことのない世界の転生よ。<br>
                        世界に終末をもたらす『東京受胎』。突然起こったその大異変に巻き込まれ、主人公、勇、千晶たちは散り散りになってしまう。<br>
                        東京受胎により、日本の首都は、謎の光を中心として地平が内球状にひしゃげた異界へと姿を変えた。そこは、神話・伝承上の存在、『悪魔』の住まう世界だった。意識を失った少年はその眠りの中、謎の金髪の子どもと老婆から、これからを生き延びるための力、『マガタマ』を授かる。<br>
                        ほどなく病院の一室で独り目覚めた時、少年の身体は『悪魔』として新生していた。<br>
                        少年は、変わり果てた“トウキョウ”で、変わり果てた己の身を戦いの旅路へと投じ、『新世界創造』の選択と葛藤の物語を紡いでいく。
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
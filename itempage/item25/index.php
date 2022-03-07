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
    $columns = $dba->SELECT('t_items', DBA::ALL, DBA::NUMVALUE, 'item_id = ?', [25]);

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
    <title>レッド・デッド・リデンプション2</title>

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
                        無法者たちの新たなる物語が始まる
                    </h3>
                    <img class="col-8 my-3" src="./img/red1.jpg" alt="">
                    <p class="col-9" style="line-height:2rem">
                        西部の町、ブラックウォーターで大掛かりな強盗に失敗した後、アーサー・モーガンとダッチギャングは逃亡を余儀なくされる。 連邦捜査官と国中の賞金稼ぎに追われる中、ギャングが生き延びるためにはアメリカの荒れた土地で強奪、暴力、盗みを働くしかない。 抗争に関わるほど、ギャングはバラバラにされる危機に見舞われる。アーサーは自らの理想と、自分を育ててくれたギャングへの忠誠、そのどちらかの選択を迫られる。
                    </p>


                    <h3 class="col-9 mt-5 font-weight-bold mb-3">
                        ゲームマップ
                    </h3>
                    <img class="col-8 my-3" src="./img/red2.jpg" alt="">
                    <p class="col-9" style="line-height:2rem">
                        本作のフィールドは、ロックスター・ゲームスがこれまでに作った中で最も広大かつ細かく作りこまれています。雪に覆われた山道から泥道、沼地、近代化の進む町、南西部に広がるフロンティアなど、アメリカ各地の多様な景色がシームレスに並存しています。また、各ロケーションではそれぞれ固有のアクティビティを楽しむことができ、様々なチャンスが眠っています。
                    </p>


                    <h3 class="col-9 mt-5 font-weight-bold mb-3">
                        アーサー・モーガンとダッチギャング
                    </h3>
                    <img class="col-8 my-3" src="./img/red3.jpg" alt="">
                    <p class="col-9" style="line-height:2rem">
                        アーサー・モーガンはダッチギャングの中核を担う古参メンバーです。アーサーはダッチが正しい判断でギャングを導いてくれると信じており、たびたびダッチの計画を実行に移す役目を任せられます。 まだ若い頃にダッチにギャングへと迎え入れられたアーサーにとって、ギャングは家族のような存在です。多くの意味でギャングはアーサーの人生に光を与えてくれる拠り所であり、深い忠誠を誓っています。アーサーはギャングと共に無法者として生きる道しか知らず、仲間を守り、養うためには手段を選びません。 アーサーはギャングと深い関係を築いており、彼らと共に生きて無法者としての生活のあらゆる側面を体験します。アーサーと他のギャングメンバーに関係性は、本作のストーリーとゲームプレイの両方で重要なキーポイントとなります。プレイヤーはアーサーとして、ゲーム市場いまだかつてないアプローチでこうした人物達を知ることとなるでしょう。 プレイヤーは他のギャングメンバーといつでもインタラクトでき、仲間と会話することで、新たなチャンスが生まれます。
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
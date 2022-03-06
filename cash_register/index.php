<?php

session_start();

include_once '../mod/DBA.php';
include_once '../mod/module.php';
include_once '../mod/LoginClass.php';

$user = '';

try {
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

  $cart = explode(',', $user['user_cart']);

  $condition = ' item_id = ? ';

  if (count($cart) > 1) {
    # code...
    for ($i = 1; $i < count($cart); $i++) {
      # code...
      $condition .= ' or item_id = ? ';
    }
  }
  $cart_items = $dba->SELECT('t_items', DBA::ALL, DBA::NUMVALUE, $condition, $cart);

  $total_price = 0;

  foreach ($cart_items as $cart_item) {
    # code...
    $total_price = $total_price + $cart_item['item_price'];
  }


  if ($user['user_card_limit'] != null) {
    # code...
    $card_limit = explode('-', $user['user_card_limit']);
    $card_limit_year = $card_limit[0];
    $card_limit_month = $card_limit[1];
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

  <link rel="stylesheet" href="style.css">
  <!-- Favicon -->
  <link href="/HEW/img/Logo.png" rel="shortcut icon" type="image/png" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i" rel="stylesheet">

  <!-- Stylesheets -->
  <link rel="stylesheet" href="/HEW/css/bootstrap.min.css" />
  <link rel="stylesheet" href="/HEW/css/font-awesome.min.css" />
  <link rel="stylesheet" href="/HEW/css/owl.carousel.css" />
  <link rel="stylesheet" href="/HEW/css/animate.css" />

  <title>購入画面</title>

  <link href="https://fonts.googleapis.com/css?family=Source+Sans+3:200,300,regular,500,600,700,800,900,200italic,300italic,italic,500italic,600italic,700italic,800italic,900italic" rel="stylesheet" />
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-5" style="height: 2px;">
    <div class="container-fluid">
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


  <main class="container">

    <h1 class="heading">
      <ion-icon name="cart-outline"></ion-icon> カート
    </h1>

    <div class="item-flex">
      <div class="row justify-content-left">
        <a class="col-2 font-weight-bold btn btn-link mb-3" href="/HEW/cart/">＜ 戻る</a>
      </div>

      <section class="checkout">

        <h2 class="section-heading">支払詳細</h2>

        <div class="payment-form">

          <div class="payment-method">

            <button class="method selected">
              <ion-icon name="card"></ion-icon>

              <span>クレジットカード</span>

              <ion-icon class="checkmark fill" name="checkmark-circle"></ion-icon>
            </button>

            <button class="method">
              <ion-icon name="logo-paypal"></ion-icon>

              <span>PayPay</span>

              <ion-icon class="checkmark" name="checkmark-circle-outline"></ion-icon>
            </button>

          </div>

          <form action="<?php echo $_SERVER['PHP_SELF']; ?>">

            <div class="cardholder-name">
              <label for="cardholder-name" class="label-default">カード名義人</label>
              <input type="text" name="cardholder-name" id="cardholder-name" class="input-default">
            </div>

            <div class="card-number">
              <label for="card-number" class="label-default">カード番号</label>
              <input type="number" name="card-number" id="card-number" class="input-default" value="<?php if ($user['user_card'] != null) echo h($user['user_card']) ?>">
            </div>

            <div class="input-flex">

              <div class="expire-date">
                <label for="expire-date" class="label-default">有効期限</label>

                <div class="input-flex">

                  <input type="number" name="day" id="expire-date" value="<?php if ($user['user_card_limit'] != null) echo h($card_limit_year) ?>" min="1" max="31" class="input-default">
                  /
                  <input type="number" name="month" id="expire-date" value="<?php if ($user['user_card_limit'] != null) echo h($card_limit_month) ?>" min="1" max="12" class="input-default">

                </div>
              </div>

              <div class="cvv">
                <label for="cvv" class="label-default">CVV</label>
                <input type="number" name="cvv" id="cvv" class="input-default">
              </div>

            </div>

          </form>

        </div>

        <button class="btn btn-primary">
          購入する
        </button>

      </section>


      <section class="cart">

        <div class="cart-item-box">

          <h2 class="section-heading">ご注文</h2>
          <?php foreach ($cart_items as $cart_item) : ?>

            <div class="product-card">

              <div class="card">
                <div class="img-box">
                  <img src="/HEW/img/item/<?php echo h($cart_item['item_image']) ?>" alt="Green tomatoes" width="80px" class="product-img">
                </div>

                <div class="detail">

                  <h4 class="product-name"><?php echo h($cart_item['item_name']) ?></h4>

                  <div class="wrapper">


                    <div class="price">
                      ￥ <span id="price"><?php echo h($cart_item['item_price']) ?></span>
                    </div>

                  </div>

                </div>

              </div>

            </div>
          <?php endforeach; ?>


        </div>

        <div class="wrapper">

          <div class="discount-token">

            <label for="discount-token" class="label-default">ギフトコード</label>

            <div class="wrapper-flex">

              <input type="text" name="discount-token" id="discount-token" class="input-default">

              <button class="btn btn-outline">Apply</button>

            </div>

          </div>

          <div class="amount">


            <div class="total">
              <span>合計</span> <span>￥ <span id="total"><?php echo h($total_price) ?></span></span>
            </div>

          </div>

        </div>

      </section>

    </div>

  </main>



  <!-- Footer section -->
  <footer class="footer mt-auto py-3 bg-dark">
    <div class="container-fluid">
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



<script src="./script.js"></script>

<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>
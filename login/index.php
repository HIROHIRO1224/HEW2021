<?php

include_once("../mod/LoginClass.php");
session_start();

$loginclass = new LoginClass();
$error = '';

if (isset($_COOKIE['user_data'])) {
    # code...
    $_POST['user_data'] = $_COOKIE['user_data'];
    $_POST['password'] = $_COOKIE['password'];
}

if (isset($_REQUEST['error'])) {
    # code...
    $error = $_REQUEST['error'];
}

if (!empty($_POST)) {
    # code...
    $result = $loginclass->login($_POST["user_data"], $_POST["password"]);
    if ($result != false) {
        # code...
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['time'] = time();
        setcookie('user_data', $_POST['user_data'], time() + 60 * 60 * 24 * 30);
        setcookie('password', $_POST['password'], time() + 60 * 60 * 24 * 30);
        header("Location: ../index.php");
        exit;
    } elseif (!$result) {
        $error = "typomiss";
    }
}
?>



<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css" />

    <title>Document</title>
</head>

<body>
    <div class="bg-image pt-5" style="background-image: url('./img/sean-do-EHLd2utEf68-unsplash.jpg'); background-size:cover; background-repeat:no-repeat; height:100vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 col-sm-12 border p-3 rounded shadow-lg bg-white">
                    <div class="site-logo navbar-brand mb-3 pl-3" style="background-image: url('../img/Logo.png'); background-repeat: no-repeat; background-size: contain;">
                        <div class="ml-4">Playground</div>
                    </div>
                    <p class="h2 font-weight-bold">ログイン</p>
                    <p class="mb-3">初めてご利用の方は<a href="./signup.php">アカウントを作成</a>してください</p>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <?php if ($error == 'typomiss') : ?>
                            <div class="alert alert-danger" role="alert">ユーザー情報、またはパスワードが違います</div>
                        <?php endif; ?>

                        <?php if ($error == 'timeout') : ?>
                            <div class="alert alert-danger" role="alert">一定時間経過いたしました。パスワードを再入力してください。</div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="user_data" class="form-label">ユーザー名もしくはメールアドレス</label>
                            <input type="text" name="user_data" id="user_data" class="form-control" value="<? if (!empty($_POST['user_data'])) echo $_POST["user_data"]; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">パスワード</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <input type="submit" value="続行" class="btn btn-primary mb-3">
                    </form>

                </div>
            </div>
        </div>

    </div>
</body>

</html>
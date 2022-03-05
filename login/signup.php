<?php


include_once("../mod/LoginClass.php");
include_once("../mod/DBA.php");
include_once("../mod/module.php");

$result = false;
$error = '';
if (!empty($_POST)) {
    # code...
    $loginclass = new LoginClass();

    try {
        //code...
        $dba = new DBA("root", "", "HEW", "localhost");
        $params = [$_POST["username"]];
        $condition = "user_name=?";
        $column = $dba->SELECT("t_users", DBA::ALL, DBA::NUMVALUE, $condition, $params);
        if (count($column) == 0) {
            # code...
            $signup_result = $loginclass->signUp($_POST["password"], $_POST["username"], $_POST["truename"], $_POST["email"]);
            if ($signup_result) {
                # code...
                $result = true;
            } else {
                $result = false;
                $error = "unknown";
            }
        } else {
            $error = 'Duplicate';
            $result = false;
        }
    } catch (PDOException $e) {
        throw $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="/HEW/img/Logo.png" rel="shortcut icon" type="image/png" />

    <!-- Stylesheets -->
    <link rel="stylesheet" href="../css/bootstrap.min.css" />


    <title>新規登録</title>
</head>

<body>

    <div class="bg-image pt-5" style="background-image: url('./img/sean-do-EHLd2utEf68-unsplash.jpg'); background-size:cover; background-repeat:no-repeat; height:100vh">

        <?php if ($result) : ?>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-sm-12 border p-3 rounded shadow-lg bg-white">
                        <p>登録が完了いたしました</p>
                        <a href="./index.php" class="btn btn-link">ログイン画面</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (empty($_POST) || !$result) : ?>
            <div class="container">
                <div class=" row justify-content-center">
                    <div class="col-lg-6 col-md-9 col-sm-12 border p-3 rounded shadow-lg bg-white">
                        <div class="site-logo navbar-brand mb-3 pl-3" style="background-image: url('../img/Logo.png'); background-repeat: no-repeat; background-size: contain;">
                            <div class="ml-4">Playground</div>
                        </div>
                        <p class="h2 font-weight-bold">新規登録</p>
                        <p class="mb-3">既にアカウントをお持ちの場合は、<a href="./index.php">ログインしてください。</a></p>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <?php if (!$result && $error == "unknown") : ?>
                                <div class="alert alert-danger" role="alert">登録に失敗しました。再度お試しください。</div>
                            <?php endif; ?>

                            <div class="mt-3 mb-3">
                                <label for="username" class="form-label">ユーザー名</label>
                                <input type="text" name="username" id="username" class="form-control" value="<? if (!empty($_POST)) echo h($_POST["username"]); ?>">
                            </div>
                            <?php if (!$result && $error == "Duplicate") : ?>
                                <div class="alert alert-danger" role="alert">そのユーザー名は既に存在します</div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label for="truename" class="form-label">本名</label>
                                <input type="text" name="truename" id="truename" class="form-control" value="<? if (!empty($_POST)) echo h($_POST["truename"]); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">メールアドレス</label>
                                <input type="mail" name="email" id="email" class="form-control" value="<? if (!empty($_POST)) echo h($_POST["email"]); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">パスワード</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <input type="submit" value="続行" class="btn btn-primary mb-3 ">
                        </form>
                    </div>
                </div>
            </div>
        <?php endif ?>

    </div>
</body>

</html>
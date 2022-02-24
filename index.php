<?php

session_start();

include_once './mod/DBA.php';
include_once './mod/LoginClass.php';
include_once './mod/module.php';

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
		$dba = new DBA('root', 'secret', 'HEW', 'db');
		$condition = 'user_id = ?;';
		$params = [$_SESSION['user_id']];
		$columns = $dba->SELECT('t_users', DBA::ALL, DBA::NUMVALUE, $condition, $params);
		$user = $columns[0];
	}
} catch (PDOException $e) {
	throw $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="ja">

<head>
	<title>Game Shop</title>
	<meta charset="UTF-8">
	<meta name="description" content="Game Warrior Template">
	<meta name="keywords" content="warrior, game, creative, html">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Favicon -->
	<link href="img/Logo.png" rel="shortcut icon" type="image/png" />

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i" rel="stylesheet">

	<!-- Stylesheets -->
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="css/font-awesome.min.css" />
	<link rel="stylesheet" href="css/owl.carousel.css" />
	<link rel="stylesheet" href="css/style.css" />
	<link rel="stylesheet" href="css/animate.css" />


</head>

<body>
	<!-- Page Preloder -->
	<div id="preloder">
		<div class="loader"></div>
	</div>

	<!-- Header section -->
	<header class="header-section">
		<div class="container">
			<!-- logo -->
			<a class="site-logo navbar-brand pl-1" href="index.html" style="background-image: url('./img/Logo.png'); background-repeat: no-repeat; background-size: contain;">
				<div class="ml-5" style="color: whitesmoke;">Playground</div>
			</a>
			<div class=" user-panel">
				<?php if ($user != 'guest') : ?>
					<a href="./mypage.php"><? echo h($user["user_name"]) ?></a>
				<?php else : ?>
					<a href="./login/index.php">ログイン/登録</a>
				<?php endif; ?>
			</div>
			<!-- responsive -->
			<div class="nav-switch">
				<i class="fa fa-bars"></i>
			</div>
			<!-- site menu -->
			<nav class="main-menu">
				<ul>
					<li><a href="index.html">ホーム</a></li>
					<li><a href="review.html">ゲーム</a></li>
					<li><a href="categories.html">ブログ</a></li>
					<li><a href="community.html">フォーラム</a></li>
					<li><a href="contact.html">コンタクト</a></li>
				</ul>
			</nav>
		</div>
	</header>
	<!-- Header section end -->


	<!-- Hero section -->
	<section class="hero-section">
		<div class="hero-slider owl-carousel">
			<div class="hs-item set-bg" data-setbg="img/slider-1.jpg">
				<div class="hs-text">
					<div class="container">
						<h2>最高の <span>ゲーム</span>がそこにある</h2>
						<p>サンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプル <br> サンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプル
							<br>サンプルサンプルサンプルサンプルサンプル
						</p>
						<a href="#" class="site-btn">もっと見る</a>
					</div>
				</div>
			</div>
			<div class="hs-item set-bg" data-setbg="img/slider-2.jpg">
				<div class="hs-text">
					<div class="container">
						<h2>最高の <span>ゲーム</span> がそこにある</h2>
						<p>サンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプル <br>
							サンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプル<br>サンプルサンプルサンプルサンプルサンプル</p>
						<a href="#" class="site-btn">もっと見る</a>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Hero section end -->


	<!-- Latest news section -->
	<div class="latest-news-section">
		<div class="ln-title">最新ニュース</div>
		<div class="news-ticker">
			<div class="news-ticker-contant">
				<div class="nt-item"><span class="new">new</span>サンプルサンプルサンプルサンプルサンプル </div>
				<div class="nt-item"><span class="strategy">strategy</span>サンプルサンプルサンプルサンプルサンプル </div>
				<div class="nt-item"><span class="racing">racing</span>サンプルサンプルサンプルサンプルサンプル </div>
			</div>
		</div>
	</div>
	<!-- Latest news section end -->


	<!-- Feature section -->
	<section class="feature-section spad">
		<div class="container">
			<div class="row">
				<div class="col-lg-3 col-md-6 p-0">
					<div class="feature-item set-bg" data-setbg="img/features/1.jpg">
						<span class="cata new">new</span>
						<div class="fi-content text-white">
							<h5><a href="#">新キャラ追加！！！</a></h5>
							<p>サンプルサンプルサンプルサンプルサンプル</p>
							<a href="#" class="fi-comment">? コメント</a>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 p-0">
					<div class="feature-item set-bg" data-setbg="img/features/2.jpg">
						<span class="cata strategy">strategy</span>
						<div class="fi-content text-white">
							<h5><a href="#">新イベント登場！！</a></h5>
							<p>サンプルサンプルサンプルサンプルサンプル </p>
							<a href="#" class="fi-comment">? コメント</a>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 p-0">
					<div class="feature-item set-bg" data-setbg="img/features/3.jpg">
						<span class="cata new">new</span>
						<div class="fi-content text-white">
							<h5><a href="#">新シーズンスタート</a></h5>
							<p>サンプルサンプルサンプルサンプルサンプル </p>
							<a href="#" class="fi-comment">? コメント</a>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 p-0">
					<div class="feature-item set-bg" data-setbg="img/features/4.jpg">
						<span class="cata racing">racing</span>
						<div class="fi-content text-white">
							<h5><a href="#">新マップ追加</a></h5>
							<p>サンプルサンプルサンプルサンプルサンプル </p>
							<a href="#" class="fi-comment">? コメント</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Feature section end -->


	<!-- Recent game section  -->
	<section class="recent-game-section spad set-bg" data-setbg="img/recent-game-bg.png">
		<div class="container">
			<div class="section-title">
				<div class="cata new">new</div>
				<h2>最新のゲーム</h2>
			</div>
			<div class="row">
				<div class="col-lg-4 col-md-6">
					<div class="recent-game-item">
						<div class="rgi-thumb set-bg" data-setbg="img/recent-game/1.jpg">
							<div class="cata new">new</div>
						</div>
						<div class="rgi-content">
							<h5>ApexLegends</h5>
							<p>サンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプル </p>
							<a href="#" class="comment">? コメント</a>
							<div class="rgi-extra">
								<div class="rgi-star"><img src="img/icons/star.png" alt=""></div>
								<div class="rgi-heart"><img src="img/icons/heart.png" alt=""></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="recent-game-item">
						<div class="rgi-thumb set-bg" data-setbg="img/recent-game/2.jpg">
							<div class="cata racing">racing</div>
						</div>
						<div class="rgi-content">
							<h5>グランツーリスモ</h5>
							<p>サンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプル</p>
							<a href="#" class="comment">? コメント</a>
							<div class="rgi-extra">
								<div class="rgi-star"><img src="img/icons/star.png" alt=""></div>
								<div class="rgi-heart"><img src="img/icons/heart.png" alt=""></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="recent-game-item">
						<div class="rgi-thumb set-bg" data-setbg="img/recent-game/3.jpg">
							<div class="cata adventure">Adventure</div>
						</div>
						<div class="rgi-content">
							<h5>Halo</h5>
							<p>サンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプル</p>
							<a href="#" class="comment">? コメント</a>
							<div class="rgi-extra">
								<div class="rgi-star"><img src="img/icons/star.png" alt=""></div>
								<div class="rgi-heart"><img src="img/icons/heart.png" alt=""></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Recent game section end -->


	<!-- Tournaments section -->
	<section class="tournaments-section spad">
		<div class="container">
			<div class="tournament-title">大会情報</div>
			<div class="row">
				<div class="col-md-6">
					<div class="tournament-item mb-4 mb-lg-0">
						<div class="ti-notic">プレミアムトーナメント</div>
						<div class="ti-content">
							<div class="ti-thumb set-bg" data-setbg="img/tournament/1.jpg"></div>
							<div class="ti-text">
								<h4>ApexLegends</h4>
								<ul>
									<li><span>トーナメント開始日:</span>2022年 7月7日</li>
									<li><span>トーナメント終了日:</span>2022年 7月10日</li>
									<li><span>参加者:</span> 10 チーム</li>
									<li><span>大会主催者:</span> ?</li>
								</ul>
								<p><span>Prizes:</span> 1位 place $2000, 2位 place: $1000, 3位 place: $500</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="tournament-item">
						<div class="ti-notic">プレミアムトーナメント</div>
						<div class="ti-content">
							<div class="ti-thumb set-bg" data-setbg="img/tournament/2.jpg"></div>
							<div class="ti-text">
								<h4>Valorant</h4>
								<ul>
									<li><span>トーナメント開始日:</span> 2022年 7月7日</li>
									<li><span>トーナメント終了日:</span> 2022年 7月10日</li>
									<li><span>参加者:</span> 10 チーム</li>
									<li><span>大会主催者:</span> ?</li>
								</ul>
								<p><span>Prizes:</span> 1位 place $2000, 2位 place: $1000, 3位 place: $500</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Tournaments section bg -->


	<!-- Review section -->
	<section class="review-section spad set-bg" data-setbg="img/review-bg.png">
		<div class="container">
			<div class="section-title">
				<div class="cata new">new</div>
				<h2>最近のレビュー</h2>
			</div>
			<div class="row">
				<div class="col-lg-3 col-md-6">
					<div class="review-item">
						<div class="review-cover set-bg" data-setbg="img/review/1.jpg">
							<div class="score yellow">9.3</div>
						</div>
						<div class="review-text">
							<h5>Valorant</h5>
							<p>サンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプル</p>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="review-item">
						<div class="review-cover set-bg" data-setbg="img/review/2.jpg">
							<div class="score purple">9.5</div>
						</div>
						<div class="review-text">
							<h5>GTA</h5>
							<p>サンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプル</p>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="review-item">
						<div class="review-cover set-bg" data-setbg="img/review/3.jpg">
							<div class="score green">9.1</div>
						</div>
						<div class="review-text">
							<h5>ApexLegends</h5>
							<p>サンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプル</p>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="review-item">
						<div class="review-cover set-bg" data-setbg="img/review/4.jpg">
							<div class="score pink">9.7</div>
						</div>
						<div class="review-text">
							<h5>Overwatch</h5>
							<p>サンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプル</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Review section end -->


	<!-- Footer top section -->
	<section class="footer-top-section">
		<div class="container">
			<div class="footer-top-bg">
				<img src="img/footer-top-bg.png" alt="">
			</div>
			<div class="row">
				<div class="col-lg-4">
					<div class="footer-logo text-white">
						<img src="img/footer-logo.png" alt="">
						<p>サンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプル</p>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="footer-widget mb-5 mb-md-0">
						<h4 class="fw-title">最新記事</h4>
						<div class="latest-blog">
							<div class="lb-item">
								<div class="lb-thumb set-bg" data-setbg="img/latest-blog/1.jpg"></div>
								<div class="lb-content">
									<div class="lb-date">2022年 7月10日</div>
									<p>サンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプル</p>
									<a href="#" class="lb-author">By SampleMan</a>
								</div>
							</div>
							<div class="lb-item">
								<div class="lb-thumb set-bg" data-setbg="img/latest-blog/2.jpg"></div>
								<div class="lb-content">
									<div class="lb-date">2022年 7月10日</div>
									<p>サンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプル</p>
									<a href="#" class="lb-author">By SampleMan</a>
								</div>
							</div>
							<div class="lb-item">
								<div class="lb-thumb set-bg" data-setbg="img/latest-blog/3.jpg"></div>
								<div class="lb-content">
									<div class="lb-date">2022年 7月10日</div>
									<p>サンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプル</p>
									<a href="#" class="lb-author">By SampleMan</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="footer-widget">
						<h4 class="fw-title">トップコメント</h4>
						<div class="top-comment">
							<div class="tc-item">
								<div class="tc-thumb set-bg" data-setbg="img/authors/1.jpg"></div>
								<div class="tc-content">
									<p><a href="#">SampleMan</a> <span>on</span> サンプルサンプルサンプルサンプル</p>
									<div class="tc-date">2022年 7月10日</div>
								</div>
							</div>
							<div class="tc-item">
								<div class="tc-thumb set-bg" data-setbg="img/authors/2.jpg"></div>
								<div class="tc-content">
									<p><a href="#">SampleMan</a> <span>on</span> サンプルサンプルサンプルサンプル</p>
									<div class="tc-date">2022年 7月10日</div>
								</div>
							</div>
							<div class="tc-item">
								<div class="tc-thumb set-bg" data-setbg="img/authors/3.jpg"></div>
								<div class="tc-content">
									<p><a href="#">SampleMan</a> <span>on</span> サンプルサンプルサンプルサンプル</p>
									<div class="tc-date">2022年 7月10日</div>
								</div>
							</div>
							<div class="tc-item">
								<div class="tc-thumb set-bg" data-setbg="img/authors/4.jpg"></div>
								<div class="tc-content">
									<p><a href="#">SampleMan</a> <span>on</span> サンプルサンプルサンプルサンプル</p>
									<div class="tc-date">2022年 7月10日</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Footer top section end -->


	<!-- Footer section -->
	<footer class="footer-section">
		<div class="container">
			<ul class="footer-menu">
				<li><a href="index.html">Home</a></li>
				<li><a href="review.html">Games</a></li>
				<li><a href="categories.html">Blog</a></li>
				<li><a href="community.html">Forums</a></li>
				<li><a href="contact.html">Contact</a></li>
			</ul>
			<p class="copyright">
				Copyright &copy;<script>
					document.write(new Date().getFullYear());
				</script> All rights reserved

			</p>
		</div>
	</footer>
	<!-- Footer section end -->


	<!--====== Javascripts & Jquery ======-->
	<script src="js/jquery-3.2.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/owl.carousel.min.js"></script>
	<script src="js/jquery.marquee.min.js"></script>
	<script src="js/main.js"></script>
</body>

</html>
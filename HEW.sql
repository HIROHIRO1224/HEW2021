-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost
-- 生成日時: 2022 年 3 月 07 日 15:08
-- サーバのバージョン： 10.4.21-MariaDB
-- PHP のバージョン: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `HEW`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `t_items`
--

CREATE TABLE `t_items` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_price` int(11) NOT NULL,
  `item_category` varchar(255) NOT NULL,
  `item_corporate` varchar(255) NOT NULL,
  `item_url` varchar(255) NOT NULL,
  `item_sold` int(11) NOT NULL,
  `item_image` varchar(255) NOT NULL,
  `item_registered` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `t_items`
--

INSERT INTO `t_items` (`item_id`, `item_name`, `item_price`, `item_category`, `item_corporate`, `item_url`, `item_sold`, `item_image`, `item_registered`) VALUES
(1, 'WebTETRIS', 980, 'パズル', 'unkonow', '/HEW/items/WebTETRIS', 126, 'neave-tetris.jpg', '2022-02-28 12:35:01'),
(2, 'ナビつき! つくってわかる はじめてゲームプログラミング', 2790, 'その他', '任天堂', '#', 1280, 'maxresdefault.jpg', '2022-02-28 13:27:18'),
(3, '太鼓の達人　ドンダフルフェスティバル', 2560, '音楽ゲーム', 'バンダイナムコ', '#', 100, '42167_820_461.jpg', '2022-02-28 14:40:51'),
(4, 'FORTNITE', 980, 'バトルロイヤル', 'Epic Games', '#', 6012, '15BPL_YouTube_Cover-770x434.jpg', '2022-02-28 14:46:26'),
(5, 'APEX -LEGENDS- CHAMPION edition', 480, 'バトルロイヤル', 'レクトロニック・アーツ', '#', 10201, '09.jpg', '2022-02-28 14:49:07'),
(6, 'ぷよクエ', 1480, 'パズル', 'バンダイナムコ', '#', 670, 'd5397-4166-0ae3a7a5bc8c223fe779-0.jpg', '2022-02-28 14:49:07'),
(7, 'VALORANT', 980, 'FPS', 'Riot Games', '#', 556, 'maxresdefault-1.jpg', '2022-02-28 14:51:45'),
(8, 'ASPHALT 8', 2180, 'レーシング', 'Gameloft', '#', 350, 'unnamed.jpg', '2022-02-28 14:55:55'),
(9, 'Days Gone', 4257, 'サバイバル', 'ソニー・インタラクティブエンタテインメント', '#', 208, 'days_title.jpg', '2022-03-06 03:56:59'),
(10, 'Minecraft', 3500, 'アクション', ' Mojang Studios', '#', 7872, 'Micra_title.jpg', '2022-03-06 04:13:35'),
(11, 'Fallout 76', 8770, 'RPG', 'ベセスダ・ソフトワークス', '#', 503, 'fallout_title.jpg', '2022-03-06 03:59:19'),
(12, 'METAL GEAR SOLID V: GROUND ZEROES + THE PHANTOM PAIN - PS4', 2580, 'アクション', 'コナミデジタルエンタテインメント', '#', 792, 'metal_title.jpg', '2022-03-06 04:04:16'),
(13, 'Horizon Forbidden West', 6161, 'RPG', 'ソニーインタラクティブエンタテインメント', '#', 401, 'Horizon_title.jpg', '2022-03-06 04:02:06'),
(14, 'クラッシュ・バンディクー ブッとび3段もり!ボーナスエディション', 2980, 'アクション', 'アクティビジョン・ブリザード', '#', 407, 'bandhi_title.jpg', '2022-03-06 04:17:25'),
(15, 'Star Wars ジェダイ:フォールン・オーダー', 2757, 'アクション', 'レクトロニック・アーツ', '#', 893, 'jedi_title.jpg', '2022-03-06 04:15:39'),
(16, 'サイバーパンク2077', 6569, 'アクション', 'CD Projekt RED', '#', 972, 'cyber_title.jpg', '2022-03-06 04:29:54'),
(17, 'Splatoon 2', 5788, 'FPS', '任天堂', '#', 6290, 'supura_title.jpg', '2022-03-06 04:31:37'),
(18, 'ゼルダの伝説 ブレス オブ ザ ワイルド', 6609, 'アクション', '任天堂', '#', 5021, 'zeruda_title.jpg', '2022-03-06 04:33:06'),
(19, 'ドラゴンクエストビルダーズ2 破壊神シドーとからっぽの島', 3700, 'アクション', 'スクウェア・エニックス', '#', 1202, 'draque_title.jpg', '2022-03-06 05:45:06'),
(20, 'ドラゴンボールZ KAKAROT\r\n', 5280, '格闘', 'バンダイナムコ', '#', 1091, 'db_title.jpg', '2022-03-06 05:46:48'),
(21, 'バイオミュータント', 5000, 'アクション', 'Experiment 101', '#', 621, 'bio_title.jpg', '2022-03-06 05:49:22'),
(22, 'ペルソナ5 スクランブル ザ ファントム ストライカーズ', 6400, 'アクション', 'アトラス', '#', 1372, 'perusona_title.jpg', '2022-03-06 05:56:43'),
(23, 'マリオカート8 デラックス', 5730, 'レーシング', '任天堂', '#', 5098, 'mariocart_title.jpg', '2022-03-06 06:14:17'),
(24, 'モンスターハンター:ワールド\r\n', 1940, 'アクション', 'Capcom', '#', 7043, 'monhan_title.jpg', '2022-03-06 06:17:56'),
(25, 'レッド・デッド・リデンプション2', 4700, 'アクション', 'ロックスター・ゲームス', '#', 481, 'red_title.jpg', '2022-03-06 06:19:30'),
(26, '鬼滅の刃 ヒノカミ血風譚', 4527, '格闘', 'アニプレックス', '#', 821, 'kimetu_title.jpg', '2022-03-06 06:58:24'),
(27, '新すばらしきこのせかい\r\n', 4200, 'アクション', 'スクウェア・エニックス', '#', 561, 'suba_title.jpg', '2022-03-06 07:02:10'),
(28, '真・女神転生III NOCTURNE HD REMASTER', 2600, 'RPG', 'アトラス', '#', 963, 'megami_title.jpg', '2022-03-06 07:03:39'),
(29, 'Among Us', 520, '人狼', 'InnerSloth', '#', 3021, 'Among_title.jpg', '2022-03-06 07:07:29'),
(30, 'DARK SOULS: REMASTERED', 4730, 'アクション', 'FromSoftware', '#', 6210, 'DARK_title.jpg', '2022-03-06 07:28:34'),
(31, 'ELDEN RING', 9240, 'アクション', '\r\nFromSoftware', '#', 2012, 'ELDEN_title.jpg', '2022-03-06 07:31:13'),
(32, 'GOD EATER3', 9020, 'アクション', 'Marvelous', '#', 801, 'GODEATER_title.jpg', '2022-03-06 07:35:27'),
(33, 'NieRAutomata\r\n', 5280, 'アクション', 'スクウェア・エニックス', '#', 5012, 'Nie_title.jpg', '2022-03-06 07:36:30'),
(34, 'Street Fighter V', 3046, '格闘', 'Capcom', '#', 2091, 'Street_title.jpg', '2022-03-06 07:38:31'),
(35, 'Tales of Arise', 8778, 'RPG', 'バンダイナムコ', '#', 1721, 'Talse_title.jpg', '2022-03-06 07:40:22'),
(36, 'TEKKEN7', 4400, '格闘', 'バンダイナムコ', '#', 2109, 'TEKKEN_title.jpg', '2022-03-06 07:41:35'),
(37, 'The Witcher® 3 Wild Hunt', 5588, 'アクション', ' Projekt RED', '#', 1392, 'Wicher_title.jpg', '2022-03-06 07:44:28'),
(38, 'MONSTER HUNTER RISE', 5990, 'アクション', 'Capcom', '#', 4091, 'MHR_title.jpg', '2022-03-06 07:46:27'),
(39, 'ドフィと迷いの森', 220, 'アクション', 'unknown', '/HEW/items/dofi_to_mayoi_no_mori', 10000, 'dofi_title.png', '2022-03-06 08:55:02'),
(40, 'Fate/EXTELLA LINK', 2980, 'アクション', 'マーベラス', '#', 200, 'extella_title.jpg', '2022-03-06 09:00:41'),
(41, 'グランツーリスモ7', 7590, 'レーシング', 'ソニー・インタラクティブエンタテインメント', '#', 100, 'guran_title.jpg', '2022-03-06 09:04:28'),
(42, '初音ミク Project DIVA Future Tone DX', 9500, '音楽', 'セガ', '#', 3939, 'miku_title.jpg', '2022-03-06 09:09:12');

-- --------------------------------------------------------

--
-- テーブルの構造 `t_users`
--

CREATE TABLE `t_users` (
  `user_id` int(11) NOT NULL,
  `user_pwd` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_tname` varchar(255) NOT NULL,
  `user_birthday` date DEFAULT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_card` bigint(20) DEFAULT NULL,
  `user_card_limit` varchar(255) DEFAULT NULL,
  `user_cart` varchar(1000) DEFAULT '',
  `user_purchased` varchar(10000) DEFAULT NULL,
  `user_registered` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `t_users`
--

INSERT INTO `t_users` (`user_id`, `user_pwd`, `user_name`, `user_tname`, `user_birthday`, `user_email`, `user_card`, `user_card_limit`, `user_cart`, `user_purchased`, `user_registered`) VALUES
(5, 'aaaa', 'aaaa', 'ああああ', NULL, 'aaaa@aaaaaa.com', NULL, '', '', NULL, '2022-02-22 06:57:33'),
(8, 'test1111', 'TEST', 'テスト', '1987-08-08', 'test@sample.com', 1111222233334444, '2026-12', '', '1,39', '2022-02-25 05:18:49'),
(9, 'test2222', 'test2', '田中雅紀', NULL, 'test@sample2.com', NULL, '', '', NULL, '2022-03-05 12:02:07');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `t_items`
--
ALTER TABLE `t_items`
  ADD PRIMARY KEY (`item_id`);

--
-- テーブルのインデックス `t_users`
--
ALTER TABLE `t_users`
  ADD PRIMARY KEY (`user_id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `t_items`
--
ALTER TABLE `t_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- テーブルの AUTO_INCREMENT `t_users`
--
ALTER TABLE `t_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

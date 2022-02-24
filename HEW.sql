-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- ホスト: db
-- 生成日時: 2022 年 2 月 24 日 05:16
-- サーバのバージョン： 8.0.28
-- PHP のバージョン: 7.4.20

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
  `item_id` int NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_price` int NOT NULL,
  `item_sold` int NOT NULL,
  `item_registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `t_users`
--

CREATE TABLE `t_users` (
  `user_id` int NOT NULL,
  `user_pwd` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_tname` varchar(255) NOT NULL,
  `user_birthday` date DEFAULT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_card` int DEFAULT NULL,
  `user_cart` varchar(1000) DEFAULT NULL,
  `user_purchased` varchar(10000) DEFAULT NULL,
  `user_registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- テーブルのデータのダンプ `t_users`
--

INSERT INTO `t_users` (`user_id`, `user_pwd`, `user_name`, `user_tname`, `user_birthday`, `user_email`, `user_card`, `user_cart`, `user_purchased`, `user_registered`) VALUES
(1, 'Yoshikawa1224', 'HIROHIRO1224', '吉川大翔', '2001-12-24', 'yoshiyoshi1224@icloud.com', NULL, NULL, NULL, '2022-02-22 05:06:03'),
(2, 'tanakatanaka', 'PH1030029', '田中雅紀', NULL, 'tanaka.masanori@example.com', NULL, NULL, NULL, '2022-02-22 06:07:22'),
(3, 'anpanman', 'L&V', 'アンパンマン', NULL, 'anpanman@example.com', NULL, NULL, NULL, '2022-02-22 06:39:26'),
(5, 'aaaa', 'aaaa', 'ああああ', NULL, 'aaaa@aaaaaa.com', NULL, NULL, NULL, '2022-02-22 06:57:33'),
(6, 'testtest', 'DP3245TNT', 'system()', NULL, 'test@sample.com', NULL, NULL, NULL, '2022-02-23 12:40:02'),
(7, '11111111', 'TEST', '頭可笑', NULL, 'test@sample.com', NULL, NULL, NULL, '2022-02-23 12:42:13');

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
  MODIFY `item_id` int NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `t_users`
--
ALTER TABLE `t_users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2023-04-24 11:53:03
-- サーバのバージョン： 10.4.27-MariaDB
-- PHP のバージョン: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `shotohlcd31_kfc`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `user`
--

CREATE TABLE `user` (
  `user_id` int(20) UNSIGNED NOT NULL,
  `kind` varchar(20) NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `furigana` varchar(20) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `email` varchar(40) NOT NULL,
  `password` varchar(20) NOT NULL,
  `address` varchar(40) NOT NULL,
  `zip` varchar(20) NOT NULL,
  `birth` date NOT NULL,
  `job` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `user`
--

INSERT INTO `user` (`user_id`, `kind`, `user_name`, `name`, `furigana`, `gender`, `email`, `password`, `address`, `zip`, `birth`, `job`) VALUES
(2, '里親', 'ゆう', 'テストゆう', 'てすとゆう', '', 'yuuka.kobatake@gmail.com', '91121', '石川県金沢市', '9200022', '2012-10-01', '会社員'),
(3, '里親', 'ゆう', 'テストゆう', 'てすとゆう', '', 'yuuka.kobatake@gmail.com', '91121', '石川県金沢市', '9200022', '2012-10-01', '会社員'),
(4, '里親', 'ゆう', 'テストゆう', 'てすとゆう', '', 'yuuka.kobatake@gmail.com', '91121', '石川県金沢市', '9200022', '2012-10-01', '会社員'),
(8, '里親', '太郎', '田中太郎', 'たなかたろう', '男', 'yuuka.kobatake@gmail.com', 'KFCpassword', '石川県金沢市北安江1-1-1', '9200022', '2018-01-21', 'その他'),
(9, '里親', '太郎', '田中太郎', 'たなかたろう', '男', 'grgagaraar@rtr.com', 'KFCpassword', '石川県金沢市北安江', '2222222', '0000-00-00', '会社員');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

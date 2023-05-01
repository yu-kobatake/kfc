-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2023-04-26 14:30:40
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
(1, '里親', 'ゆー', 'テスト優子', 'てすとゆうこ', '女', 'yuuko@tet.com', 'KFCpassword', '石川県金沢市', '9200022', '2023-04-07', '会社員'),
(2, '里親', 'ゆう', '佐藤ゆうた', 'さとうゆうた', '男', 'ysato@test.com', '11111', '石川県金沢市', '9200022', '2012-10-01', '会社員'),
(3, 'ブリーダー', 'たつ', '田中達也', 'たなかたつや', '男', 'tatsu@test.com', '1111111', '石川県金沢市', '9200022', '2012-10-01', '会社員'),
(4, '里親', '太郎', '田中太郎', 'たなかたろう', '男', 'grgagaraar@rtr.com', 'KFCpassword', '石川県金沢市北安江', '2222222', '0000-00-00', '会社員'),
(5, '里親', 'たろー', '佐藤太郎', 'さとうたろう', '男', 'tarotaro@test.com', 'password', '石川県金沢市北安江', '9200022', '2022-01-26', 'パート・アルバイト'),
(6, 'ブリーダー', 'ニックネーム', '田中花子', 'たなかはなこ', '女', 'test@gmail.com', 'KFCpassword', '石川県金沢市北安江', '9200022', '2022-01-26', 'パート・アルバイト'),
(7, '里親', 'しまー', '島花子', 'しまはなこ', '女', 'shima@test.com', 'password', '石川県金沢市北安江', '9200022', '2022-01-26', '会社員'),
(31, '里親', 'やま', '山口大介', 'やまぐちだいすけ', '男', 'yamama@gmail.com', 'KFCpassword', '石川県金沢市北安江', '9200022', '1999-01-06', '会社員'),
(32, '里親', 'やまだ', '山田太郎', 'やまだたろう', '男', 'yamada@test.jp', 'KFCpassword', '石川県金沢市', '9200000', '2023-04-28', '自営業'),
(33, 'ブリーダー', '太郎', 'てすと', 'たなかたろう', '回答しない', 'ttetetata@tttt', 'KFCpassword', '石川県河北郡内灘町大根布', '9200266', '2023-03-28', '会社員'),
(35, '里親', '太郎', '田中太郎', 'たなかたろう', '回答しない', 'etetetet@tetete', 'KFCpassword', '石川県金沢市北安江', '9200222', '2023-04-19', '会社員'),
(36, 'ブリーダー', '花子', '山田花子', 'やまだはなこ', '女', 'test@hanako', 'KFCpassword', '石川県金沢市', '9200000', '2023-03-01', 'パート・アルバイト'),
(38, '里親', 'にっく', '西山太郎', 'にしやまたろう', '男', 'nishiyama@taro', 'KFCpassword', '石川県金沢市北安江', '9200222', '2023-04-01', '自営業'),
(45, 'ブリーダー', '太郎', '田中太郎', 'たなかたろう', '男', 'tttt@tttttttttt', 'KFCpassword', '石川県金沢市北安江', '9200222', '2023-04-28', '経営者・役員'),
(50, 'ブリーダー', '太郎', '田中太郎', 'たなかたろう', '男', 'taro@tanaka.cm', 'KFCpassword', '石川県金沢市北安江', '9200222', '2023-04-05', '会社員'),
(55, '里親', 'てっす', 'てすとたろう', 'てすとたろう', '回答しない', 'test@testtest.com', 'KFCpassword', '石川県金沢市北安江', '9200022', '2023-05-06', '経営者・役員'),
(56, '里親', '太郎', '鈴木太郎', 'すずきたろう', '回答しない', 'suzu@test.com', 'KFCpassword', '石川県金沢市北安江', '9200022', '2023-03-26', '会社員'),
(57, 'ブリーダー', 'これはニックネーム', 'テスト', 'たなかたろう', '回答しない', 'te@te', 'KFCpassword', '石川県金沢市', '9200000', '2023-04-28', 'パート・アルバイト');

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
  MODIFY `user_id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

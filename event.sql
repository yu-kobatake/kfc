-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2023-04-28 14:32:23
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
-- テーブルの構造 `event`
--

CREATE TABLE `event` (
  `event_id` int(20) NOT NULL,
  `title` varchar(40) NOT NULL,
  `image_1` varchar(40) NOT NULL,
  `image_2` varchar(40) NOT NULL,
  `image_3` varchar(40) NOT NULL,
  `kind` varchar(40) NOT NULL,
  `area` varchar(40) NOT NULL,
  `area_address` varchar(40) NOT NULL,
  `day` date NOT NULL,
  `time` varchar(20) NOT NULL,
  `information` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `event`
--

INSERT INTO `event` (`event_id`, `title`, `image_1`, `image_2`, `image_3`, `kind`, `area`, `area_address`, `day`, `time`, `information`) VALUES
(1, '金沢市開催★GW犬猫譲渡会', '1_image1.jpg', '1_image2.jpg', '1_image3.jpg', '全て', '石川県', '金沢市●●●●●●', '2023-05-03', '14:00～18:00', '昨年に引き続き、今年も金沢市●●で保護犬譲渡会を開催します。\r\n少しでも興味のある方お待ちしております。'),
(2, 'MIX犬たくさん！保護犬の会', '2_image1.jpg', '2_image2.jpg', '2_image3.jpg', '犬', '石川県', '金沢市●●●●●●', '2023-05-06', '14:00～18:00', '唯一無二のMIX犬に出会いませんか？元気な子がいっぱいです。\r\nＧＷはぜひ遊びにきてください！'),
(3, '子猫の譲渡会開催！', '3_image1.jpg', '3_image2.jpg', '3_image3.jpg', '猫', '東京都', '港区●●●●●●', '2023-06-03', '13:00～16:00', 'かわいい子猫の家族が待っています。\r\n大好評の子猫をあつめた譲渡会が今年も開催します。'),
(4, 'イベント・ねこみあい', '4_image1.jpg', '4_image2.jpg', '4_image3.jpg', '猫', '大阪府', '住所●●●●●●', '2023-06-11', '13:00～16:00', '猫たちいっぱいの譲渡会を開催します。\r\n人懐っこい子ばかりですので、興味のある方はぜひ会いにきてください。'),
(5, '全国から集まった犬集会', '5_image1.jpg', '5_image2.jpg', '5_image3.jpg', '犬', '富山県', '住所●●●●●●', '2023-07-01', '13:00～16:00', '昨年に引き続き、今年も保護譲渡会を開催します。\r\n少しでも興味のある方お待ちしております。'),
(6, '犬＆猫譲渡会イベント', '6_image1.jpg', '6_image2.jpg', '6_image3.jpg', '全て', '岐阜県', '住所●●●●●●', '2023-07-15', '13:00～16:00', '昨年に引き続き、今年も保護譲渡会を開催します。\r\n少しでも興味のある方お待ちしております。'),
(7, '自然大好き元気な犬猫譲渡会', '7_image1.jpg', '7_image2.jpg', '7_image3.jpg', '全て', '福井県', '住所●●●●●●', '2023-07-15', '13:00～16:00', '昨年に引き続き、今年も保護譲渡会を開催します。\r\n少しでも興味のある方お待ちしております。'),
(8, 'ワンワン譲渡会', '8_image1.jpg', '8_image2.jpg', '8_image3.jpg', '犬', '東京都', '住所●●●●●●', '2023-08-05', '13:00～16:00', '昨年に引き続き、今年も保護譲渡会を開催します。\r\n少しでも興味のある方お待ちしております。');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`event_id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `event`
--
ALTER TABLE `event`
  MODIFY `event_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

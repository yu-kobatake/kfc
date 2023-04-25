-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2023-04-25 16:51:58
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
-- テーブルの構造 `animal`
--

CREATE TABLE `animal` (
  `animal_id` int(20) UNSIGNED NOT NULL,
  `title` varchar(40) NOT NULL,
  `image_1` varchar(40) NOT NULL,
  `image_2` varchar(40) NOT NULL,
  `image_3` varchar(40) NOT NULL,
  `kind` varchar(40) NOT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `age` varchar(20) NOT NULL,
  `area_1` varchar(40) NOT NULL,
  `area_2` varchar(40) DEFAULT NULL,
  `area_3` varchar(40) DEFAULT NULL,
  `animal_area` varchar(40) NOT NULL,
  `animal_character` varchar(40) DEFAULT NULL,
  `other` varchar(500) DEFAULT NULL,
  `user_id` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `animal`
--

INSERT INTO `animal` (`animal_id`, `title`, `image_1`, `image_2`, `image_3`, `kind`, `gender`, `age`, `area_1`, `area_2`, `area_3`, `animal_area`, `animal_character`, `other`, `user_id`) VALUES
(1, '元気なミニチュアダックスフンド（茶）', '1_image1.jpg', '1_image2.jpg', '1_image3.jpg', '犬', '♂', '1歳7か月', '石川県', '富山県', '福井県', '石川県', '元気な男の子', '１テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト', '2'),
(2, '可愛いミニチュアダックスフンド（黒）', '2_image1.jpg', '2_image2.jpg', '2_image3.jpg', '犬', '♀', '2歳1か月', '大阪府', '京都府', '奈良県', '大阪府', '可愛い女の子', '２テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト', '3'),
(3, '元気な柴犬（茶）', '3_image1.jpg', '3_image2.jpg', '3_image3.jpg', '犬', '♂', '1歳9か月', '福岡県', '佐賀県', '大分県', '福岡県', '元気な男の子', '３テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト', '2'),
(4, '可愛い柴犬（黒）', '4_image1.jpg', '4_image2.jpg', '4_image3.jpg', '犬', '♀', '不明', '沖縄県', NULL, NULL, '沖縄県', '可愛い女の子', '４テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト', '3'),
(5, '元気な黒猫', '5_image1.jpg', '5_image2.jpg', '5_image3.jpg', '猫', '♂', '0歳11ヵ月', '岩手県', '青森県', '秋田県', '岩手県', '元気な男の子', '５テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト', '2'),
(6, '可愛いラグドール', '6_image1.jpg', '6_image2.jpg', '6_image3.jpg', '猫', '♀', '2歳1ヵ月', '東京都', '埼玉県', '千葉県', '東京都', '可愛い女の子', '６テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト', '3'),
(7, 'test1', '7_image1.jpg', '7_image2.jpg', '7_image3.jpg', '犬', '♂', '1ヵ月', '石川県', '富山県', '福井県', '石川県', 'test1', 'test1', '2'),
(8, 'test2', '8_image1.jpg', '8_image2.jpg', '8_image3.jpg', '猫', 'test2', '2ヵ月', '石川県', '富山県', '福井県', '石川県', 'test2', 'test2', '3'),
(9, 'test', '9_image1.jpg', '9_image2.jpg', '9_image3.jpg', '猫', 'test3', '3ヵ月', '石川県', '富山県', '福井県', '石川県', 'test3', 'test3', '2');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `animal`
--
ALTER TABLE `animal`
  ADD PRIMARY KEY (`animal_id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `animal`
--
ALTER TABLE `animal`
  MODIFY `animal_id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

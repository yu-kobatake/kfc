-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2023-05-02 09:46:25
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
(7, '元気なコーギー', '7_image1.jpg', '7_image2.jpg', '7_image3.jpg', '犬', '♂', '1ヵ月', '石川県', '富山県', '福井県', '石川県', 'test1', 'test1', '2'),
(8, '可愛いアメリカンショートヘア', '8_image1.jpg', '8_image2.jpg', '8_image3.jpg', '猫', 'test2', '2ヵ月', '石川県', '富山県', '福井県', '石川県', 'test2', 'test2', '3'),
(9, 'お昼寝が大好きな猫', '9_image1.jpg', '9_image2.jpg', '9_image3.jpg', '猫', '♀', '3ヵ月', '石川県', '富山県', '福井県', '石川県', 'test3', 'test3', '2'),
(10, '元気なゴールデンレトリバー', '10_image1.jpg', '10_image2.jpg', '10_image3.jpg', '犬', '♂', '5ヵ月', '石川県', '富山県', '福井県', '石川県', 'test', 'test', '2'),
(11, 'おちゃめなサモエド', '11_image1.jpg', '11_image2.jpg', '11_image3.jpg', '犬', '♂', '3歳5か月', '東京都', '埼玉県', '', '東京都', 'おちゃめ', 'test', '2'),
(12, '可愛い猫', '12_image1.jpg', '12_image2.jpg', '12_image3.jpg', '猫', '♀', '3歳5か月', '大阪府', '京都府', '', '大阪府', 'おちゃめ', 'test', '2');

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

-- --------------------------------------------------------

--
-- テーブルの構造 `good`
--

CREATE TABLE `good` (
  `id` int(20) UNSIGNED NOT NULL,
  `animal_id` int(20) UNSIGNED NOT NULL,
  `user_id` int(20) UNSIGNED NOT NULL,
  `created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `message`
--

CREATE TABLE `message` (
  `id` int(20) UNSIGNED NOT NULL,
  `text` varchar(200) NOT NULL,
  `image` blob DEFAULT NULL,
  `user_id` int(20) UNSIGNED NOT NULL,
  `destination_user_id` int(20) UNSIGNED NOT NULL,
  `created_id` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `message_relation`
--

CREATE TABLE `message_relation` (
  `id` int(20) UNSIGNED NOT NULL,
  `user_id` int(20) UNSIGNED NOT NULL,
  `destination_user_id` int(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
-- テーブルのインデックス `animal`
--
ALTER TABLE `animal`
  ADD PRIMARY KEY (`animal_id`);

--
-- テーブルのインデックス `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`event_id`);

--
-- テーブルのインデックス `good`
--
ALTER TABLE `good`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `message_relation`
--
ALTER TABLE `message_relation`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `animal`
--
ALTER TABLE `animal`
  MODIFY `animal_id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- テーブルの AUTO_INCREMENT `event`
--
ALTER TABLE `event`
  MODIFY `event_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- テーブルの AUTO_INCREMENT `good`
--
ALTER TABLE `good`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `message`
--
ALTER TABLE `message`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `message_relation`
--
ALTER TABLE `message_relation`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

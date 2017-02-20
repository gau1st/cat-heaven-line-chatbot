SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `eventlog` (
  `id` int(11) NOT NULL,
  `signature` text,
  `events` longtext,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `eventlog`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `eventlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `number` int(3) NOT NULL,
  `text` text NOT NULL,
  `image` varchar(250) NOT NULL,
  `option_a` varchar(200) NOT NULL,
  `option_b` varchar(200) NOT NULL,
  `option_c` varchar(200) NOT NULL,
  `option_d` varchar(200) NOT NULL,
  `answer` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;


INSERT INTO `questions` (`id`, `number`, `text`, `image`, `option_a`, `option_b`, `option_c`, `option_d`, `answer`) VALUES
(1, 1, 'Jenis apakah kucing berikut ini?', 'https://www.petfinder.com/images/breeds/cat/1000.jpg', 'Abyssinians', 'American Bobtail', 'American Curl', 'American Shorthair', 'Abyssinians'),
(2, 2, 'Jenis apakah kucing berikut ini?', 'https://www.petfinder.com/images/breeds/cat/1040.jpg', 'Burmese', 'British Shorthair', 'Bengal', 'Balinese', 'Balinese'),
(3, 3, 'Jenis apakah kucing berikut ini?', 'https://www.petfinder.com/images/breeds/cat/1050.jpg', 'Burmese', 'British Shorthair', 'Bengal', 'Balinese', 'Bengal'),
(4, 4, 'Jenis apakah kucing berikut ini?', 'https://www.petfinder.com/images/breeds/cat/2030.jpg', 'Chartreux', 'Cymric', 'Cornish Rex', 'Devon Rex', 'Cornish Rex'),
(5, 5, 'Jenis apakah kucing berikut ini?', 'https://www.petfinder.com/images/breeds/cat/2050.jpg', 'Chartreux', 'Cymric', 'Cornish Rex', 'Devon Rex', 'Devon Rex'),
(6, 6, 'Jenis apakah kucing berikut ini?', 'https://www.petfinder.com/images/breeds/cat/2060.jpg', 'Exotic Shorthair', 'Egyptian Mau', 'Himalayan', 'Javanese', 'Egyptian Mau'),
(7, 7, 'Jenis apakah kucing berikut ini?', 'https://www.petfinder.com/images/breeds/cat/2090.jpg', 'Exotic Shorthair', 'Egyptian Mau', 'Himalayan', 'Javanese', 'Himalayan'),
(8, 8, 'Jenis apakah kucing berikut ini?', 'https://www.petfinder.com/images/breeds/cat/3010.jpg', 'Exotic Shorthair', 'Egyptian Mau', 'Himalayan', 'Javanese', 'Javanese'),
(9, 9, 'Jenis apakah kucing berikut ini?', 'https://www.petfinder.com/images/breeds/cat/3030.jpg', 'Manx', 'Munchkin', 'Maine Coon', 'Korat', 'Maine Coon'),
(10, 10, 'Jenis apakah kucing berikut ini?', 'https://www.petfinder.com/images/breeds/cat/5030.jpg', 'Manx', 'Munchkin', 'Maine Coon', 'Korat', 'Munchkin'),
(11, 11, 'Jenis apakah kucing berikut ini?', 'https://www.petfinder.com/images/breeds/cat/3050.jpg', 'Norwegian Forest Cat', 'Oriental', 'Maine Coon', 'Ocicat', 'Norwegian Forest Cat'),
(12, 12, 'Jenis apakah kucing berikut ini?', 'https://www.petfinder.com/images/breeds/cat/3080.jpg', 'Norwegian Forest Cat', 'Oriental', 'Maine Coon', 'Persian', 'Persian'),
(13, 13, 'Jenis apakah kucing berikut ini?', 'https://www.petfinder.com/images/breeds/cat/3090.jpg', 'Scottish Folds', 'Ragdoll', 'Maine Coon', 'Siamese', 'Ragdoll'),
(14, 14, 'Jenis apakah kucing berikut ini?', 'https://www.petfinder.com/images/breeds/cat/4010.jpg', 'Scottish Folds', 'Ragdoll', 'Maine Coon', 'Siamese', 'Scottish Folds'),
(15, 15, 'Jenis apakah kucing berikut ini?', 'https://www.petfinder.com/images/breeds/cat/4020.jpg', 'Singapura', 'Ragdoll', 'Maine Coon', 'Siamese', 'Siamese'),
(16, 16, 'Jenis apakah kucing berikut ini?', 'https://www.petfinder.com/images/breeds/cat/4040.jpg', 'Singapura', 'Munchkin', 'Maine Coon', 'Manx', 'Singapura'),
(17, 17, 'Jenis apakah kucing berikut ini?', 'https://www.petfinder.com/images/breeds/cat/4070.jpg', 'Singapura', 'Munchkin', 'Sphynx', 'Somali', 'Sphynx'),
(18, 18, 'Jenis apakah kucing berikut ini?', 'https://www.petfinder.com/images/breeds/cat/4090.jpg', 'Turkish Van', 'Munchkin', 'Sphynx', 'Turkish Angora', 'Turkish Angora'),
(19, 19, 'Jenis apakah kucing berikut ini?', 'https://www.petfinder.com/images/breeds/cat/5000.jpg', 'Turkish Van', 'Munchkin', 'Sphynx', 'Turkish Angora', 'Turkish Van'),
(20, 20, 'Jenis apakah kucing berikut ini?', 'https://www.petfinder.com/images/breeds/cat/4050.jpg', 'Somali', 'Munchkin', 'Snowshoe', 'Turkish Angora', 'Snowshoe');

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `score` float NOT NULL DEFAULT '0',
  `line_id` varchar(50) DEFAULT NULL,
  `number` int(3) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

CREATE TABLE `tips` (
 `id` int(11) NOT NULL,
 `title` text NOT NULL,
 `url` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `tips`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `tips`
 MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;


INSERT INTO `tips` (`id`, `title`, `url`) VALUES
(1, 'Cara Merawat Bulu Kucing yang Mudah Dilakukan Sendiri', 'http://kucingpedia.com/cara-merawat-bulu-kucing/'),
(2, 'Cara Alami Mengobati Kutu Kucing Tanpa ke Dokter Hewan', 'http://www.amazine.co/28519/6-cara-alami-mengobati-kutu-kucing-tanpa-ke-dokter-hewan/'),
(3, 'Tips dan Trik Menghadapi Kucing Pipis Sembarangan', 'http://www.homievetcare.com/2016/02/tips-cara-mengatasi-kucing-pipis-sembarangan.html');

CREATE TABLE `quiznumber` (
 `id` int(11) NOT NULL,
 `number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `quiznumber`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `quiznumber`
 MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

INSERT INTO `quiznumber` (`id`, `number`) VALUES
(1, 0),
(2, 0),
(3, 0),
(4, 0),
(5, 0),
(6, 0),
(7, 0),
(8, 0),
(9, 0),
(10, 0);

-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 21, 2017 at 09:18 AM
-- Server version: 10.1.20-MariaDB
-- PHP Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id1033922_abcmovies`
--

-- --------------------------------------------------------

--
-- Table structure for table `banner`
--

CREATE TABLE `banner` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `picture` varchar(256) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `banner`
--

INSERT INTO `banner` (`id`, `name`, `picture`, `status`) VALUES
(5, 'kong skull island', './pictures/banner/kong-banner-2.jpg', 0),
(6, 'galih dan ratna', './pictures/banner/galihratna_s.png', 0),
(7, 'beauty and the beast', './pictures/banner/new_beauty_and_the_beast__2017__banner_poster_by_artlover67-davww9h.jpg', 0),
(9, 'logan', './pictures/banner/wolverine-last-time-banner.jpg', 0),
(10, 'boss babby', './pictures/banner/Boss-Baby.jpg', 1),
(11, 'ghost in the shell', './pictures/banner/ghost_in_the_shell_banner-1.jpg', 0),
(12, 'fast and furious 8', './pictures/banner/fast--furious-8-banner.jpg', 1),
(13, 'power rangers', './pictures/banner/Power-Rangers-character-banners-1.jpg', 0),
(14, 'miss sloane', './pictures/banner/552_banner.jpg', 0),
(15, 'the guys', './pictures/banner/The-Guys-e1491820021108.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `film`
--

CREATE TABLE `film` (
  `id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `summary` text,
  `genre` varchar(256) DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `playing_date` date DEFAULT NULL,
  `length` smallint(3) DEFAULT NULL,
  `director` varchar(256) DEFAULT NULL,
  `writer` varchar(256) DEFAULT NULL,
  `actors` varchar(256) DEFAULT NULL,
  `poster` varchar(256) DEFAULT NULL,
  `trailer` varchar(256) DEFAULT NULL,
  `imdb_id` varchar(12) DEFAULT NULL,
  `imdb_rating` float DEFAULT NULL,
  `metascore` int(2) DEFAULT NULL COMMENT 'from metacritic.com',
  `twitter_positif` int(6) DEFAULT '0',
  `twitter_negatif` int(6) DEFAULT '0',
  `rating` float DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '3' COMMENT '0 for Coming Soon, 1 for Now Playing, 2 for Old Movies, 3 for Unchecked Coming Soon, 4 for Unchecked Now Playing'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `film`
--

INSERT INTO `film` (`id`, `title`, `summary`, `genre`, `year`, `playing_date`, `length`, `director`, `writer`, `actors`, `poster`, `trailer`, `imdb_id`, `imdb_rating`, `metascore`, `twitter_positif`, `twitter_negatif`, `rating`, `status`) VALUES
(1, 'GHOST IN THE SHELL', 'Motoko Kusanagi (Scarlett Johansson) adalah seorang agen cyborg yang hidup di era tahun 2029 dimana robot-robot dengan kecerdasan buatan hidup berdampingan dengan manusia. \r\n\r\nKusanagi dan sejumlah anggota tim keamanan, Public Security Section 9 memiliki kewajiban untuk menghentikan kejahatan yang dilakukan oleh hacker terkenal, the Puppet Master dan mengantisipasi pergerakan dari penjahat misterius yang ingin menghancurkan perkembangan teknologi dari Hanka Robotic.', 'Action, Drama, Sci-fi', 2017, '2017-03-29', 120, 'Rupert Sanders', 'William Wheeler', 'Scarlett Johansson, Michael Pitt, Juliette Binoche', 'http://www.21cineplex.com/data/gallery/pictures/148912889080786_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/G4VmJcZR0Yg\" frameborder=\"0\" allowfullscreen></iframe>', 'tt0113568', 8, 0, 0, 0, 0, 2),
(2, 'MISS SLOANE', 'Elizabeth Sloane (Jessica Chastain) adalah pelobi politik yang handal dan terkenal di Washington D.C. Strategi politiknya selalu berhasil mengalahkan lawan-lawannya.\r\n\r\nNamun sebuah kasus akan membawanya pada suatu dilema. Ia berada di posisi sulit saat sebuah Undang-undang kepemilikan senjata api sedang diuji. Elizabeth menjadi salah satu pelobi di pihak yang tengah berseteru akan Undang-undang tersebut.', 'Drama, Thriller', 2016, '2016-12-09', 132, 'John Madden', 'Jonathan Perera', 'Jessica Chastain, Gugu Mbatha-Raw, Michael Stuhlbarg, John Lithgow', 'https://images-na.ssl-images-amazon.com/images/M/MV5BMTAyODY4Njc4MjBeQTJeQWpwZ15BbWU4MDI0NTIzMDAy._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/AMUkfmUu44k\" frameborder=\"0\" allowfullscreen></iframe>', 'tt4540710', 6.6, 63, 23, 17, 0, 1),
(3, 'THE BOSS BABY', 'Berkisah tentang kehidupan seorang bayi yang tampak berbeda dari bayi pada umumnya. Ia nakal, keras kepala, selalu mengenakan jas, membawa koper dan kopi adalah minuman kesukaanya. \r\n\r\nDi balik penampilannya, Boss Baby adalah utusan dari Baby Corp. Perusahaan yang bertugas untuk menyelidiki dan menghentikan bisnis dari Puppy Co., sebuah organisasi yang berniat untuk melemahkan bisnis makanan bayi demi keuntungan bisnis makanan hewan.', 'Animation, Comedy, Family', 2017, '2017-03-31', 0, 'Tom McGrath', 'Marla Frazee (book), Michael McCullers (screenplay)', 'Miles Christopher Bakshi, Alec Baldwin, Eric Bell Jr., Steve Buscemi', 'http://www.21cineplex.com/data/gallery/pictures/148939461451821_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/tquIfapGVqs\" frameborder=\"0\" allowfullscreen></iframe>', 'tt3874544', 4.7, 0, 17, 22, 0, 1),
(4, 'FAST AND FURIOUS 8', 'Dalam seri ke delapan dari franchise Fast and Furious, Dom (Vin Diesel) akan mengkhianati teman-temannya dan bekerjasama dengan teroris bernama Chiper (Charlize Theron).\r\n\r\nTak lama setelah itu, tim yang tersisa direkrut oleh pasukan agen rahasia pemerintah pimpinan Frank Petty (Kurt Russel). Tugas mereka adalah menghentikan aksi teror yang direncanakan oleh Dom dan Chiper.', 'Action, Crime, Thriller', 2017, '2017-05-24', 130, 'F. Gary Gray ', 'Chris Morgan, Gary Scott Thompson (characters)', 'Vin Diesel, Dwayne Johnson, Jason Statham', 'http://www.21cineplex.com/data/gallery/pictures/148905122668489_452x647.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/19uRZ0vVVbA\" frameborder=\"0\" allowfullscreen></iframe>', 'tt1905041', 7.1, 61, 35, 49, 0, 1),
(5, 'LIFE', 'Enam orang astronot dipilih untuk ditempatkan di stasiun luar angkasa internasional. Pada awalnya mereka bersemangat ketika sebuah robot penjelajah menemukan menemukan bukti pertama yang menandakan kehidupan di Mars. Para astronot yang terbuai oleh kesuksesan mereka tidak menyadari jika kehidupan yang mereka temukan merupakan ancaman bagi umat manusia.', 'Horror, Sci-fi, Thriller', 2017, '2017-02-05', 108, 'Daniel Espinosa', 'Robert Ramsey, Matthew Stone', 'Jake Gyllenhaal, Rebecca Ferguson, Ryan Reynolds', 'http://www.21cineplex.com/data/gallery/pictures/148879301615406_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/cuA-xqBw4jE\" frameborder=\"0\" allowfullscreen></iframe>', 'tt0123964', 6.7, 63, 0, 0, 0, 2),
(6, 'GOLD', 'Kenny Wells (Matthew McConaughey) adalah seorang pebisnis yang mengalami kesulitan finansial. Kenny berusaha merubah peruntungannya dengan mencari tambang emas di hutan yang belum di petakan di wilayah Kalimantan, Indonesia.\r\n\r\nBersama temannya Michael Acosta (Edgar Ramirez) ahli geologi, Kenny menelusuri belantara hutan demi impiannya.', 'Adventure, Drama, Thriller', 2017, '2017-01-27', 120, 'Stephen Gaghan', 'Patrick Massett, John Zinman', 'Matthew McConaughey, Edgar Ramírez, Bryce Dallas Howard, Corey Stoll', 'https://images-na.ssl-images-amazon.com/images/M/MV5BNjEwNzMzMDI4Nl5BMl5BanBnXkFtZTgwMTM2ODkwMTI@._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/IhYROWOayLw\" frameborder=\"0\" allowfullscreen></iframe>', 'tt1800302', 6.6, 49, 0, 0, 0, 0),
(7, 'PERFECT DREAM', 'Bagi DIBYO keberhasilan diukur dari seberapa besar ia mampu memenuhi ambisi hidupnya. Dari kehidupan jalanan menjalankan bisnis gelap, Dibyo berhasil menikahi Lisa, putri Marcel Himawan, seorang pengusaha besar di kalangan elite Surabaya. Dibyo bahkan berhasil mengembalikan kejayaan bisnis Marcel. \r\n\r\nHarta berlimpah tak membuat Dibyo puas. Ambisi Dibyo adalah menguasai wilayah lawan bisnisnya, Hartono si mafia nomor satu. Pertikaian antar-geng pun tak terelakkan.\r\n\r\nAmbisi Dibyo makin meluap setelah mengenal Rina, pemilik galeri foto yang mampu memberi kehangatan cinta seorang ibu yang tak pernah Dibyo dapatkan selama ini. \r\n\r\nLisa harus memilih, mengikuti ambisi suaminya atau berjuang mempertahankan keutuhan keluarga yang ia cintai!', 'Drama', 2017, '2017-03-20', 0, 'Hestu Saputra', 'Sinung Winahyo, Hestu Saputra', 'Rara Nawangsih, Tissa Biani Azzahra, Wulan Guritno, Olga Lydia', 'http://www.21cineplex.com/data/gallery/pictures/148834342534292_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/Ocd9naaBprI\" frameborder=\"0\" allowfullscreen></iframe>', 'tt6522380', 0, 0, 0, 0, 0, 2),
(9, 'BEAUTY AND THE BEAST', 'Ketika Belle (Emma Watson) pergi untuk mencari ayahnya, Maurice (Kevin Kline), ia menemukan jika ayahnya disekap di dalam sebuah kastil tua oleh The Beast (Dan Stevens). Belle kemudian bertukar tempat sebagai tahanan demi membebaskan ayahnya. \r\n \r\nGadis itu terkejut ketika menyadari jika benda-benda di dalam kastil itu hidup dan dapat berbicara. Benda-benda tersebut memberitahu jika watak Beast tidaklah seburuk penampilannya. Ketika ia mulai dekat dengan Beast, para penduduk kota telah melakukan persiapan untuk menolong sang gadis.', 'Animation, Family, Fantasy', 2017, '2017-03-23', 129, 'Bill Condon ', 'Evan Spiliotopoulos, Stephen Chbosky', 'Emma Watson, Dan Stevens, Luke Evans, Josh Gad', 'http://www.21cineplex.com/data/gallery/pictures/148816706164595_452x647.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/e3Nl_TCQXuw\" frameborder=\"0\" allowfullscreen></iframe>', 'tt0101414', 8, 95, 24, 32, 0, 2),
(10, 'MOON CAKE STORY', 'David adalah seorang pengusaha muda yang menjadi taipan dan berada pada puncak keberhasilan bisnisnya. Di sisi lain, Asih adalah seorang wanita yang hidup di tengah kampung kumuh Jakarta dengan bekerja serabutan dari joki 3 in 1 dan mencuci dari toko laundry. Asih harus menjadi tulang punggung bagi anak dan adiknya di tengah beragam persoalan Jakarta yang penuh kisah-kisah kemanusiaan. Asih menjadi perempuan yang selalu bertanya: mampukah mengubah kemiskinan, namun juga mempertahankan prinsip hidup? \r\n\r\nSementara, David lalu bertanya berulang kali pada dirinya: Kenapa penyakit terjadi pada dirinya, justru di tengah puncak suksesnya? Dan kenapa dirinya seperti didorong ke ingatan masa lampau ketika dirinya hidup miskin bersama ibunya? \r\n\r\nKisah film ini dimulai ketika David harus melewati jalan 3 in 1 agar cepat sampai ke kantornya dengan supirnya, David tanpa sengaja tertarik pada sosok joki bernama Asih yang menggandeng anaknya, Bimo (12 tahun). \r\n\r\nKisah pun berlanjut. David menemui kembali Asih untuk memberi uang bayaran joki yang belum terbayar, namun yang terjadi kemudian, pertemuan demi pertemuan tanpa sengaja membawa David mengenal Asih, Bimo, dan Sekar, adik Asih, serta kehidupan lingkungan kampung kumuh tempat tinggal Asih di belakang jalan menuju 3 in 1.\r\n\r\nDavid yang dalam proses kehilangan ingatan, justru semakin terbawa memori masa kecilnya, terlebih David menemukan sosok Asih sebagai sosok ibunya yang bekerja keras untuk menghidupi David dan Kakak nya Aline kesedihan dan kegembiraan selalu meliputi kehidupan keluarga David .. dalam menjalani hidup di kota besar Ibu David adalah, sosok ibu tunggal yang mampu hidup di tengah kemiskinan dan merawat David dengan prinsip-prinsip hidup tentang manusia dan kerja.\r\n\r\nSementara, Asih lewat David menemukan keindahan mengubah dan membangun hidupnya dan keluarganya tanpa kehilangan prinsip hidup. Sebuah nilai hidup yang perlu ditumbuhkan di kota-kota besar, ketika kemanusiaan terkubur oleh kekerasan kompetisi hidup kota besar… Film yang memberikan arti hidup dalam menjalani keras nya Ibukota.', 'Animation, Family, Fantasy', 1991, '1991-11-22', 84, 'Garin Nugroho', 'Garin Nugroho', 'Bunga Citra Lestari, Morgan Oey, Dominique Diyose, Melati Zein', 'http://www.21cineplex.com/data/gallery/pictures/148792288417018_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/KjqRVPp07NU\" frameborder=\"0\" allowfullscreen></iframe>', 'tt0101414', 8, 95, 0, 0, 0, 2),
(11, 'DEAR NATHAN', 'Tidak ada hal yang sangat diinginkan SALMA di sekolah barunya selain focus pada belajar dan menunjukkan prestasinya. Sebagai murid pindahan di SMA Garuda, Salma berusaha selektif memilih teman. Sayangnya pagi itu Salma terlambat datang dan seorang siswa yang tidak kenal menolongnya menyelinap kesekolah dan menyelamatkannya dari hukuman terlambat upacara bendera. Belakangan Salma tahu bahwa siswa penolong itu bernama NATHAN, murid paling berandal seantero sekolah yang hobi tawuran.\r\n\r\nSebagai murid baik-baik, tentu Salma berusaha menjauhi orang macam Nathan. Namun, masalah datang ketika Nathan dengan terang-terangan mengejar cinta Salma dan membuat heboh satu sekolah. Berbagai cara digunakan Salma untuk menghindar, tapi sepertinya kesempatan-kesempatan tak terduga justru mengantarnya semakin dekat dengan Nathan.\r\n\r\nSaat Salma memahami titik rapuh masa lalu Nathan, dia pun bersimpati dan perlahan jatuh cinta. Saat cinta Salma tumbuh, dia ingin merubah Nathan menjadi Nathan yang baru. Di saat Nathan serius membuka diri untuk diubah oleh Salma, kekasih masa lalu Nathan bernama SELI, datang untuk meminta cinta Nathan kembali.\r\n\r\nAkankah Salma mempertahankan Nathan sebagai cinta pertama dalam hidupnya?', 'Drama', 2017, '2017-03-23', 84, 'Indra Gunawan', 'Bagus Bramanti, Gea Rexy', 'Jefri Nichole, Amanda Rawles, Rayn Wijaya, Diandra Agatha', 'http://www.21cineplex.com/data/gallery/pictures/148732443788212_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/8GIQsLKMBkk\" frameborder=\"0\" allowfullscreen></iframe>', 'tt0101414', 8, 95, 0, 0, 0, 2),
(12, 'BARACAS', 'Bandung geger, oleh adanya pemuda yang terpaksa meninggalkan keluarganya untuk bergabung dengan BARACAS. Baracas adalah merupakan Kelompok Independen yang dibentuk oleh seseorang bernama AGUS untuk menjadi tempat bergabungnya kaum lelaki yang dikecewakan oleh wanita. Mereka meng-klaim dirinya sebagai kaum lelaki yang merana karena adanya peristiwa pengkhianatan, penolakan, dan lain-lain sebagainya. Mereka menghimpun kekuatan dan menyatukan perasaan untuk bersama-sama dengan sengaja melakukan upaya membenci wanita di seluruh dunia dan pada semua unsur yang bersangkutan dengan wanita. Mereka menganggap dirinya sebagai korban wanita. Apa yang sudah dilakukan oleh wanita kepada mereka dinggapnya sebagai kekejaman yang tidak bisa dimaafkan dan menjatuhkan harga diri laki-laki di seluruh dunia. AGUS, pendiri dan sekaligus ketua Baracas adalah mantan pacarnya SARAH, Agus kecewa kepada Sarah karena secara tiba-tiba Sarah memutuskan hubungan mereka. Alhasil Agus geram, Agus marah, tidak cuma ke Sarah tetapi kepada seluruh wanita yang ada di muka bumi ini.\r\n\r\nGerakan-gerakan perlawanan terhadap BARACAS dengan berbagai macam upaya. Mereka merunding pihak polisi dan pemerintahan kota Bandung, sengaja membiarkan Baracas untuk berkembang besar. Itulah sebabnya mereka demo. Itulah sebabnya mereka menuntut polisi untuk menindak anggota Baracas. Bahkan Baracas mendapatkan teror berupa serangan fisik dari orang-orang tertentu untuk merusak markas Baracas dengan menggunakan kekerasan. Perlawanan lain dilakukan juga oleh para wanita yang tak lain adalah para mantan anggota Baracas. Mereka bergabung untuk membuat kekuatan dengan tujuan meluluhkan perasaann anggota Baracas dengan melakukan berbagai cara ampuh untuk mengubah cara berpikir mereka. Mereka dibantu oleh CEU POPONG yang selalu memberi nasihat dan masukan apa-apa saja yang dilakukan oleh para mantan itu. Akankah para mantan itu berhasil meluluhkan perasaan mereka?', 'Comedy', 2017, '2017-03-23', 120, 'Pidi Baiq', 'Pidi Baiq', 'Ringgo Agus Rahman, Ajun Perwira, Stella Cornelia', 'http://www.21cineplex.com/data/gallery/pictures/14873264526232_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/0469OXCxnPQ\" frameborder=\"0\" allowfullscreen></iframe>', 'tt0101414', 8, 95, 0, 0, 0, 2),
(13, 'POWER RANGERS', 'Film yang menjadi seri pertama Power Rangers di abad ke 21 ini akan menampilkan beberapa tokoh utama dari seri Mighty Morphin Power Rangers yang akan diperankan oleh aktor terbaru. \\n \\nBerkisah mengenai lima remaja yang dipersatukan oleh  sebuah insiden yang', 'Action, Adventure, Sci-fi', 2017, '2017-02-23', 120, 'Dean Israelite', 'John Gatins, Matt Sazama, Burk Sharpless, Zack Stentz', 'Elizabeth Banks, Rj Cyler, Naomi Scott, Becky G.', 'http://www.21cineplex.com/data/gallery/pictures/148739512016390_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/5kIe6UZHSXw\" frameborder=\"0\" allowfullscreen></iframe>', 'tt4475970', 7.8, 0, 0, 0, 0, 2),
(15, 'BID\'AH CINTA', 'Hubungan asmara antara Khalida dan Kamal tak direstui oleh dua keluarga yang saling berbeda dan bermusuhan, keluarga mereka mempunyai pemahaman tentang Islam yang berbeda satu sama lain. Islam puritan & Islam tradisional. Persoalan perbedaan pandangan agama ini menyeret hubungan asmara mereka ke dalam pusaran konflik. Khalida adalah anak H. Rohili, seorang yang sangat akrab dengan para pemuda di kampung itu. Di sisi lain, Kamal adalah anak lelaki H. Jamat, seorang haji kaya yang cukup disegani dan menjadi pendukung utama penyebaran Islam puritan di kampung yang dimotori kemenakannya bernama Ustadz Jaiz.\r\n\r\nPerbenturan antara H. Rohili dan H. Jamat pada akhirnya juga membenturkan hubungan Khalida dengan Kamal. Khalida yang dibesarkan dalam ajaran Islam tradisional merasa terganggu dengan perkembangan ini. Sebaliknya, Kamal yang banyak mendapat pengaruh dari ajaran Islam puritan H. Jamat dan berkepentingan dengan pekerjaannya di Yayasan pendidikan yang dipimpin oleh Ustadz Jaiz, merasa bingung dan tertekan dalam posisinya yang sulit. \r\n\r\nDi tengah lingkungan yang tak mungkin diseragamkan dan di mana perbedaaan merupakan suatu keniscayaan, bagaimanakah kelanjutan kisah cinta Khalida dan Kamal? Apakah cinta dapat menghapus segala kebencian yang ada?', 'Drama', 2017, '2017-03-16', 128, 'Casper Van Dien', 'Nurman Hakim', 'Cassi Thomson, Samantha Cope, Casper Van Dien, Randy Wayne', 'http://www.21cineplex.com/data/gallery/pictures/14867100493795_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/_o1fwxzZIuE\" frameborder=\"0\" allowfullscreen></iframe>', 'tt5084204', 5.7, 0, 0, 0, 0, 2),
(16, 'THE SPACE BETWEEN US', 'Sekelompok astronot diutus untuk pergi ke dalam misi penelitian di Mars. Ketika mendarat, salah seorang astronot menemukan jika ia tengah mengandung. Tidak lama kemudian astronot itu meninggal setelah ia melahirkan seorang bayi lelaki. \r\n \r\nEnam belas tahun kemudian, putra dari sang astronot, Gardner Elliot telah tumbuh menjadi seorang remaja yang menghabiskan hidupnya di planet Mars bersama para astronot. Gardner memiliki keinginan untuk bertemu dengan ayah kandungnya di bumi. Sementara itu ia juga menjalani persahabatan dengan Tulsa (Britt Robertson), seorang gadis yang tinggal di Colorado. \r\n \r\nKetika Gardner dan astronot lainnya kembali ke bumi, ia menyadari jika organ tubuhnya tidak terbiasa dengan atmosfir bumi. Ketika dirawat di rumah sakit, Gardner melarikan diri untuk mencari Tulsa. ', 'Adventure, Drama, Romance', 2017, '2017-02-03', 120, 'Peter Chelsom', 'Allan Loeb (screenplay), Stewart Schill (story by), Richard Barton Lewis (story by), Allan Loeb (story by)', 'Gary Oldman, Janet Montgomery, Trey Tucker, Scott Takeda', 'https://images-na.ssl-images-amazon.com/images/M/MV5BNjYzODU1OTkwN15BMl5BanBnXkFtZTgwMDA3MTMwMDI@._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/x73-573aWfs\" frameborder=\"0\" allowfullscreen></iframe>', 'tt3922818', 6.3, 33, 0, 0, 0, 0),
(18, 'TRINITY, THE NEKAD TRAVELER', 'Awalnya TRINITY (Maudy Ayunda) adalah seorang Mbak-mbak kantoran yang hobi traveling sejak kecil. Namun hobinya ini sering terbentur dengan duit pas-pasan dan jatah cuti di kantor. Akibatnya Trinity sering diomeli BOSS (Ayu Dewi). Trinity memiliki sahabat yang punya hobi sama, yakni YASMIN (Rachel Amanda) dan NINA (Anggika Bolsterli), ditambah dengan sepupu Trinity, EZRA (Babe Cabita). Trinity selalu menuliskan pengalamannya dalam sebuah blog berjudul naked-traveler.com.\r\n\r\nDi rumah, BAPAK (Farhan) dan MAMAH (Cut Mini) selalu menanyakan kapan Trinity serius memikirkan jodoh. Tapi Trinity selalu menjawab : nanti kalau semua bucket list sudah terpenuhi. Bucket list adalah daftar hal-hal yang harus Trinity lakukan sebelum tua, kebanyakan sih isinya (lagi-lagi) tentang jalan-jalan. Bapak langsung pusing mendengarnya. Trinity mengalami dilema antara fokus ke pekerjaannya sekarang atau mengejar passion dia yang sebenarnya, hingga kisah cintanya dengan PAUL (Hamish Daud) seorang fotografer tampan yang juga hobi traveling.', 'Drama', 2017, '2017-03-30', 103, 'Rizal Mantovani', 'Rahabi Mandra', 'Maudy Ayunda, Hamish Daud, Babe Cabiita', 'http://www.21cineplex.com/data/gallery/pictures/14860059712454_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/TNOALGw6vqM\" frameborder=\"0\" allowfullscreen></iframe>', 'tt6496236', 0, 0, 0, 0, 0, 2),
(19, 'SHUT IN', 'Mary (Naomi Watts) yang berprofesi sebagai psikolog anak mencoba untuk membangun kehidupnya kembali setelah ia kehilangan suaminya dalam sebuah kecelakaan mobil. Akan tetapi, meskipun putranya yang bernama Stephen(Charlie Heaton) lolos dari maut, kecelakaan tersebut telah membuatnya jatuh ke dalam koma.\r\n \r\nMary yang membuka praktek di rumah mendapatkan seorang pasien baru bernama Tom (Jacob Tremblay), seorang anak yang ibunya baru saja meninggal. Saat mendengar jika Tom akan dibawa ke Boston, Mary memutuskan untuk merawat Tom dirumahnya sendiri. Tidak lama setelah itu, Tom melarikan diri di tengah badai salju dan dinyatakan meninggal oleh yang berwajib, meskipun tubuhnya tidak pernah ditemukan.\r\n \r\nMary yang merasa bersalah atas kematian Tom tiba-tiba mendengar suara dan melihat bayangan anak itu di dalam rumahnya. Kekuatan mental sang psikolog mulai diuji ketika beberapa kejadian misterius mulai bermunculan pada saat badai salju membuatnya terjebak di rumahnya sendiri.', 'Drama, Thriller', 2016, '2016-11-11', 91, 'Farren Blackburn', 'Christina Hodson', 'Naomi Watts, Oliver Platt, Charlie Heaton, Jacob Tremblay', 'https://images-na.ssl-images-amazon.com/images/M/MV5BMjM3MTAyMTE2MV5BMl5BanBnXkFtZTgwMzY5MzM0MDI@._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/G7czL2a5R3c\" frameborder=\"0\" allowfullscreen></iframe>', 'tt2582500', 4.4, 22, 0, 0, 0, 2),
(24, 'BADRINATH KI DULHANIA', 'Badrinath Bansal (Varun Dhawan) dan Vaidehi Trivedi (Alia Bhatt) adalah sepasang muda-mudi yang berasal dari kota kecil. Keduanya bertemu dan saling jatuh hati.\r\n\r\nKisah keduanya rumit saat terjadi perbedaan prinsip. Namun walaupun demikian keduanya tetap saling menaruh hati. Bagaimana akhir kisahnya? Saksikan Badrinath Ki Dulhania di bioskop.', 'Comedy, Drama, Romance', 2017, '2017-03-10', 139, 'Shashank Khaitan', 'Shashank Khaitan', 'Varun Dhawan, Alia Bhatt, Gauhar Khan, Girish Karnad', 'http://www.21cineplex.com/data/gallery/pictures/148905796917849_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/ztX-iGlZ_Ug\" frameborder=\"0\" allowfullscreen></iframe>', 'tt6277440', 0, 0, 0, 0, 0, 2),
(25, 'DANGAL', 'Mahavir Singh Phogat (Aamir Khan) yang pernah menjuarai sebuah turnamen gulat memiliki mimpi untuk memenangkan medali emas untuk kompetisi gulat dunia. Namun, masalah finansial memaksanya pensiun dari karirnya sebagai pegulat profesional. Pada saat itu Phogat mengharapkan jika kelak anak lelakinya dapat meneruskan cita-citanya. Akan tetapi Phogat merasa putus asa setelah istrinya melahirkan empat anak perempuan. Beberapa tahun kemudian ia melihat kedua anak perempuannya, Geeta dan Babita mengalahkan dua orang anak laki-laki. Phogat menyadari bakat mereka dan mulai melatih anak-anaknya menjadi pegulat profesional.', 'Action, Biography, Drama', 2016, '2016-12-21', 161, 'Nitesh Tiwari', 'Piyush Gupta, Shreyas Jain, Nikhil Mehrotra, Nitesh Tiwari', 'Aamir Khan, Sakshi Tanwar, Fatima Sana Shaikh, Sanya Malhotra', 'https://images-na.ssl-images-amazon.com/images/M/MV5BMTQ4MzQzMzM2Nl5BMl5BanBnXkFtZTgwMTQ1NzU3MDI@._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/x_7YlGv9u1g\" frameborder=\"0\" allowfullscreen></iframe>', 'tt5074352', 9, 0, 0, 0, 0, 2),
(26, 'NOBITA AND THE BIRTH OF JAPAN', 'DORAEMON THE MOVIE: NOBITA AND THE BIRTH OF JAPAN - Dalam film Doraemon terbaru, Nobita dan teman-teman menempuh perjalanan waktu untuk kembali ke Jepang di masa prasejarah dimana mereka akan membangun sebuah kota impian. Di waktu yang sama, mereka bertemu dengan seorang bocah bernama Kukuru yang berada dalam masalah karena sukunya ditindas oleh dukun misterius bernama Gigazombie. Mampukah Nobita dan kawan-kawan menyelamatkan Kukuru beserta anggota sukunya yang tertindas?', 'Animation', 2016, '2016-01-26', 103, 'Shinnosuke Yakuwa', 'Shinnosuke Yakuwa, Higashi Shimzu', 'Wasabi Mizuta, Megumi Ohara, Yumi Kakazu, Subaru Kimura, Tomokazu Seki', 'http://www.21cineplex.com/data/gallery/pictures/148705623580169_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/aPtam_mNZdA\" frameborder=\"0\" allowfullscreen></iframe>', '', 0, 0, 0, 0, 7, 2),
(27, 'GALIH DAN RATNA', 'Cinta pertama. Atau cinta monyet. Atau apalah itu. Semua pasti pernah merasakannya, terutama di masa-masa remaja yang indah. Tetapi ini tidak pernah dirasakan oleh GALIH (Refal Hady), seorang siswa SMA teladan tetapi introvert yang hidup dalam bayang-bayang almarhum ayahnya dan tuntutan ibunya yang harus struggle sebagai single-mother. \r\n\r\nRATNA (Sheryl Sheinafia), seorang siswi yang baru saja pindah ke SMA tempat Galih bersekolah. Pintar dan berbakat namun ia sendiri tidak tahu passion hidupnya apa. Ia hidup tanpa tujuan, selalu mengejar hal-hal yang sangat instan. Layaknya anak millenials saat ini. \r\n\r\nDi suatu sore, di lapangan belakang sekolah, Galih dan Ratna pun bertemu. Sebuah pertemuan yang sederhana. Ratna tertarik dengan walkman yang sedang didengarkan oleh Galih. Galih membiarkan Ratna mendengarkan kaset mixtape pemberian Ayahnya. \r\n\r\nInikah yang disebut dengan cinta pertama? Yang manis, menggebu-gebu, dan juga pahit? Yang berawal dari sebuah momen sederhana di lapangan belakang sekolah? Pada akhirnya, cinta pertama inilah yang membawa Galih dan Ratna ke tahap baru dalam kehidupan mereka. Sebuah tahap pendewasaan dimana tanggung-jawab, tuntutan, dan passion saling berperang dan akhirnya berdamai. Dan cinta pertama inilah yang akan selalu menjadi sebuah kenangan yang tidak akan mereka lupakan.', 'Drama', 2017, '2017-03-09', 112, 'Lucky Kuswandi', 'Fathan Todjon, Lucky Kuswandi', 'Refal Hady, Sheryl Sheinafia, Joko Anwar, Marissa Anita', 'http://www.21cineplex.com/data/gallery/pictures/14864580058957_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/Ks0lO3zCWOQ\" frameborder=\"0\" allowfullscreen></iframe>', '', 0, 0, 0, 0, 0, 2),
(28, 'HIDDEN FIGURES', 'Berkisah tentang tim ahli matematika wanita Afrika-Amerika yang memiliki peran penting dalam sejarah NASA.\r\n\r\nKatherine G. Johnson (Taraji P. Henson), Dorothy Vaughan (Octavia Spencer) dan Mary Jackson (Janelle Monae) adalah sosok penting dibalik keberhasilan NASA dalam proyek pertama mereka membawa manusia keluar angkasa.\r\n\r\nKetiganya merupakan ahli matematika yang membuat perhitungan terhadap pesawat yang akan digunakan pada misi pertama NASA untuk mendarat di bulan.', 'Biography, Drama, History', 2016, '2017-01-06', 126, 'Theodore Melfi', 'Allison Schroeder (screenplay), Theodore Melfi (screenplay), Margot Lee Shetterly (based on the book by)', 'Taraji P. Henson, Octavia Spencer, Janelle Monáe, Kevin Costner', 'https://images-na.ssl-images-amazon.com/images/M/MV5BMjQxOTkxODUyN15BMl5BanBnXkFtZTgwNTU3NTM3OTE@._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/RK8xHq6dfAo\" frameborder=\"0\" allowfullscreen></iframe>', 'tt4846340', 7.9, 74, 0, 0, 0, 2),
(29, 'JOHN WICK: CHAPTER 2', 'Berlanjut dari film John Wick yang pertama, film ini kembali menampilkan Keanu Reeves sebagai pensiunan pembunuh bayaran, John Wick yang kembali beraksi. Kali ini John harus harus menghadapi krisis identitas yang mempertanyakan jati dirinya sebagai seorang suami yang bersedih atas kematian istrinya atau seorang pembunuh bayaran internasional tanpa belas kasihan.', 'Action, Crime, Thriller', 2017, '2017-02-10', 122, 'Chad Stahelski', 'Derek Kolstad, Derek Kolstad (based on characters created by)', 'Keanu Reeves, Riccardo Scamarcio, Ian McShane, Ruby Rose', 'https://images-na.ssl-images-amazon.com/images/M/MV5BMjE2NDkxNTY2M15BMl5BanBnXkFtZTgwMDc2NzE0MTI@._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/XGk2EfbD_Ps\" frameborder=\"0\" allowfullscreen></iframe>', 'tt4425200', 8.5, 75, 0, 0, 9, 2),
(31, 'LA LA LAND', 'Mia (Emma Stone), seorang perempuan yang bercita-cita tinggi untuk menjadi aktris kerap berpapasan dengan Sebastian (Ryan Gosling), musisi jazz yang bercita-cita untuk mendirikan klub jazznya sendiri. \r\n\r\nKedua orang tersebut akhirnya saling jatuh cinta dan bakhan Mia pun mulai menyukai musik Jazz. Pada suatu hari, Mia mulai meragukan kemampuannya setelah ia kerap gagal dalam mengikuti audisi film. Sebastian yang percaya dengan kemampuan akting Mia, kerap mendorong kekasihnya agar tidak menyerah. Sementara itu, Mia berpikir bahwa Sebastian terpaksa bergabung kedalam band terkenal demi mencari nafkah untuk mereka berdua. \r\n\r\nPada akhirnya, perbedaan dan perdebatan diantara keduanya pun kian melebarkan jarak diantara mereka.', 'Comedy, Drama, Musical', 2016, '2016-12-25', 128, 'Damien Chazelle', 'Damien Chazelle', 'Ryan Gosling, Emma Stone, Amiée Conn, Terry Walters', 'https://images-na.ssl-images-amazon.com/images/M/MV5BMzUzNDM2NzM2MV5BMl5BanBnXkFtZTgwNTM3NTg4OTE@._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/0pdqf4P9MB8\" frameborder=\"0\" allowfullscreen></iframe>', 'tt3783958', 8.5, 93, 0, 0, 0, 2),
(32, 'LION', 'Ketika Saroo baru berusia lima tahun, ia hidup dalam kemiskinan bersama ibu dan saudara-saudaranya di sebuah kota di India. Saroo dan kakak lelakinya, Guddu sering pergi bersama untuk mengemis di sebuah stasiun kereta. Pada suatu hari, Guddu memberitahu adiknya jika ia akan naik kereta ke kota lain. Mendengar perkataan ini, Saroo memohon kakanya untuk ikut bersamanya. Guddu mengabulkan permohonan adiknya dan pada saat mereka sampai di tempat tujuan, Guddu memberitahu Saroo untuk menunggunya di sebuah peron kereta. \r\n\r\nSaat Guddu tidak kunjung kembali, Saroo mulai mencari saudaranya dengan memasuki sebuah kereta yang membawanya ke tempat yang jauh. Karena sang anak tidak dapat memberitahu polisi alamat tempat tinggalnya, ia dinyatakan sebagai anak terlantar. Bocah India tersebut pada akhirnya diadopsi oleh sepasang suami-istri yang berasal dari Australia.\r\n\r\nKetika Saroo hidup dengan keluarga barunya, ia dibesarkan sebagai anak Australia pada umumnya dan kemampuannya untuk berbicara dalam bahasa Hindi kian menghilang. Sementara itu, ibu kandungnya di India tidak berhenti untuk mencari anaknya.', 'Drama', 2016, '2017-01-06', 118, 'Garth Davis', 'Saroo Brierley (adapted from the book ', 'Sunny Pawar, Abhishek Bharate, Priyanka Bose, Khushi Solanki', 'https://images-na.ssl-images-amazon.com/images/M/MV5BMjA3NjkzNjg2MF5BMl5BanBnXkFtZTgwMDkyMzgzMDI@._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/-RNI9o06vqo\" frameborder=\"0\" allowfullscreen></iframe>', 'tt3741834', 8, 69, 0, 0, 0, 2),
(33, 'LOGAN', 'Pada tahun 2024, Logan (Hugh Jackman) dan Profesor Charles Xavier (Patrick Stewart) harus bertahan tanpa X-Men ketika perusahaan yang dipimpin oleh Nathaniel Essex menghancurkan dunia. Keadaan menjadi semakin rumit dengan kemampuan regenerasi mutan logan yang mulai menghilang dan Xavier yang menderita Alzheimer. Logan harus mengalahkan Nathaniel Essex dengan bantuan seorang anak perempuan bernama Laura Kinney yang merupakan hasil kloning dari Wolverine', 'Action, Drama, Sci-fi', 2017, '2017-03-03', 136, 'James Mangold', 'James Mangold (story by), Scott Frank (screenplay), James Mangold (screenplay), Michael Green (screenplay)', 'Hugh Jackman, Patrick Stewart, Dafne Keen, Boyd Holbrook', 'https://images-na.ssl-images-amazon.com/images/M/MV5BMjI1MjkzMjczMV5BMl5BanBnXkFtZTgwNDk4NjYyMTI@._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/DekuSxJgpbY\" frameborder=\"0\" allowfullscreen></iframe>', 'tt3315342', 8.7, 77, 0, 0, 7.5, 2),
(34, 'LONDON LOVE STORY 2', '“Aku bersyukur..\r\nsemua hari hariku begitu berarti..\r\nDan Aku akan tetap minta sama Tuhan, \r\nuntuk ambil nyawa aku satu hari sebelum Tuhan ambil nyawanya\r\nKarena setiap detak jantungnya, adalah detik berharga untuk aku”\r\n\r\nCaramel sangat bahagia. Betapa tidak, impiannya sejak kecil untuk melihat dan bermain salju akhirnya terwujud karena Dave memberinya hadiah kejutan liburan ke Swiss untuk merayakan ulang tahun Caramel.\r\n\r\nsaat mereka akan berangkat menggunakan kereta dari stasiun St. Pancras International London ke Swiss, Tiba-tiba Dave tidak bisa berangkat bersama Caramel.\r\n\r\nSesampainya di sana, Sam yang sudah menjemput Caramel membawa Caramel makan malam di sebuah resto terbaik di kota Zurich, hingga Caramel sangat terkesan.\r\n\r\nSayangnya kebahagiaan Caramel menikmati hari pertamanya di Swiss, terganggu karena kemunculan Gilang, seorang chef sekaligus cucu pemilik restoran di Swiss. Gilang meminta Caramel untuk menemuinya di sebuah restoran. Caramel datang dan menemui Gilang. Gilang memberikan sebuah hadiah untuk Caramel.\r\n\r\nSetelah itu Gilang meminta Caramel menemuinya lagi keesokan harinya. Apakah Dave tahu tentang ini, Apakah Gilang mengetahui kalau Caramel adalah tunangan Dave..??', 'Drama', 2017, '2017-03-03', 100, 'Asep Kusdinar', 'Sukhdev Singh, Tisa TS', ' Ramzi. Salshabilla Adriani. Mawar Eva', 'http://www.21cineplex.com/data/gallery/pictures/148611950965983_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/_OeuFCIpFhg\" frameborder=\"0\" allowfullscreen></iframe>', 'tt3315342', 8.7, 77, 0, 0, 0, 2),
(35, 'MAX STEEL', 'Max McGrath (Ben Winchell) adalah seorang remaja berusia 16 tahun yang tengah beradaptasi dengan lingkungan barunya. Tidak lama sejak kepindahannya, ia menemukan bahwa sang almarhum ayah yang pernah bekerja sebagai ilmuwan telah mewariskan kekuatan super yang dapat merubah energi di sekelilingnya. \r\n\r\nKemudian Max juga bertemu dengan alien bernama Steel yang melatihnya untuk menggunakan kekuatannya sebagai seorang super hero bernama Max Steel. Ketika keduanya saling bekerja sama dalam misi membela kebenaran, beberapa makhluk misterius dari galaksi lain memburu Max untuk merebut kekuatan yang dimilikinya.', 'Action, Adventure, Family', 2016, '2016-10-14', 92, 'Stewart Hendler', 'Christopher Yost', 'Ben Winchell, Josh Brener, Maria Bello, Andy Garcia', 'https://images-na.ssl-images-amazon.com/images/M/MV5BMTk2MTMwOTk3N15BMl5BanBnXkFtZTgwMDI5OTYxMDI@._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/Tf4sa0BVJVw\" frameborder=\"0\" allowfullscreen></iframe>', 'tt1472584', 4.6, 22, 0, 0, 6.5, 2),
(36, 'JAKARTA UNDERCOVER', 'MOAMMAR EMKAS JAKARTA UNDERCOVER - Demi mengejar cita-citanya menjadi wartawan, Pras (Oka Antara) berangkat ke Jakarta untuk berguru pada Djarwo (Lukman Sardi), pemimpin redaksi sebuah majalah berita. Idealisme Pras mulai luntur saat menyadari bahwa kecemerlangan tulisannya dimanfaatkan oleh kantornya untuk tujuan tertentu.\r\n\r\nJiwa Pras memberontak. Apalagi setelah ia bertemu Awink (Ganindra Bimo), penari malam, yang membawanya berkenalan dengan Yoga (Baim Wong), sosok penting di dunia bisnis \"gelap\" Jakarta. Di sisi lain, Pras bertemu dengan Laura (Tiara Eve), perempuan super model yang membuat hidupnya berbeda, begitupun Laura yang menganggap Pras anomali dari kebanyakan lelaki yang ditemuinya di Jakarta.\r\n\r\nTanpa disadari, Pras berada di tengah pusaran dunia antah berantah Jakarta. Semakin dalam ia menggali, semakin jauh ia terseret ke dalamnya. Bagaimana Pras menghadapi kerasnya kehidupan di Jakarta?', 'Drama', 2016, '2016-10-14', 107, 'Stewart Hendler', 'Christopher Yost', 'Ben Winchell, Josh Brener, Maria Bello, Andy Garcia', 'http://www.21cineplex.com/data/gallery/pictures/148523068742889_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/5zMCsU5ncrM\" frameborder=\"0\" allowfullscreen></iframe>', 'tt1472584', 4.6, 22, 0, 0, 0, 2),
(37, 'RINGS', 'Film yang merupakan seri ketiga dari trilogi The Ring ini terjadi sebelum pembuatan video terkutuk yang akan membunuh siapa saja yang pernah menontonnya. Selain itu film ini juga berfokus pada keseharian seorang pelajar bernama Julia (Matilda Lutz) dan kekasihnya, Holt (Alex Roe). \r\n\r\nKhawatir akan hubungan mereka yang semakin renggang, Julia memutuskan untuk pergi menemui Holt. Ketika ia sampai, Julia menemukan jika kekasihnya adalah anggota dari sebuah klub yang menyebarkan video terkutuk Samara. Selain itu, Julia harus dihadapkan dengan kenyataan bahwa Holt telah menonton video tersebut enam setengah hari yang lalu dan mereka harus menemukan jalan untuk menghentikan kutukan Samara sebelum terlambat.', 'Drama, Horror', 2017, '2017-02-03', 102, 'F. Javier Gutiérrez', 'David Loucka (screenplay), Jacob Estes (screenplay), Akiva Goldsman (screenplay), David Loucka (story by), Jacob Estes (story by), Kôji Suzuki (based on the novel ', 'Matilda Anna Ingrid Lutz, Alex Roe, Johnny Galecki, Vincent D\'Onofrio', 'https://images-na.ssl-images-amazon.com/images/M/MV5BNjU1NDAxNTg0MF5BMl5BanBnXkFtZTgwNzUxMjEwMTI@._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/uukQ_6szDm8\" frameborder=\"0\" allowfullscreen></iframe>', 'tt0498381', 4.5, 25, 0, 0, 0, 2),
(38, 'SILENCE', 'Berkisah mengenai kedua misionaris Katholik (Andrew Garfield and Adam Driver) yang menghadapi ujian atas keyakinan merek ketika keduanya pergi ke Jepang untuk mencari guru mereka yang hilang (Liam Neeson) - tepat di saat keberadaan umat Katholik terancam di Jepang', 'Drama, History', 2016, '2017-01-13', 160, 'Martin Scorsese', 'Jay Cocks (screenplay), Martin Scorsese (screenplay), Shûsaku Endô (based on the novel by)', 'Andrew Garfield, Adam Driver, Liam Neeson, Tadanobu Asano', 'https://images-na.ssl-images-amazon.com/images/M/MV5BMjY3OTk0NjA2NV5BMl5BanBnXkFtZTgwNTg3Mjc2MDI@._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/A0KUWzfugg4\" frameborder=\"0\" allowfullscreen></iframe>', 'tt0490215', 7.5, 79, 0, 0, 0, 2),
(39, 'SPLIT', 'Ketika ketiga gadis remaja sedang menunggu ayah mereka di dalam mobil, seorang pria misterius menculik dan membawa mereka ke dalam sebuah bunker. Sang penculik yang bernama Kevin (James McAvoy) adalah seorang pria dengan gangguan jiwa yang membuatnya memiliki 23 kepribadian yang berbeda, yang diantaranya adalah seorang wanita dan anak berumur 9 tahun yang bernama Hedwig. \r\n \r\nSebagai salah satu gadis yang diculik, Casey berusaha meloloskan diri dengan meyakinkan salah satu kepribadian Kevin untuk melepaskan mereka. Akan tetapi hal tersebut tidaklah mudah, terlebih setelah Hedwig memperingatkan mereka akan the Beast yang merupakan kepribadian Kevin yang paling berbahaya.', 'Horror, Thriller', 2016, '2017-01-20', 117, 'M. Night Shyamalan', 'M. Night Shyamalan', 'James McAvoy, Anya Taylor-Joy, Betty Buckley, Haley Lu Richardson', 'https://images-na.ssl-images-amazon.com/images/M/MV5BZTJiNGM2NjItNDRiYy00ZjY0LTgwNTItZDBmZGRlODQ4YThkL2ltYWdlXkEyXkFqcGdeQXVyMjY5ODI4NDk@._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/84TouqfIsiI\" frameborder=\"0\" allowfullscreen></iframe>', 'tt4972582', 7.5, 62, 0, 0, 0, 2),
(40, 'SURGA YANG TAK DIRINDUKAN 2', 'Pertemuan dengan Arini membuat Meirose menjadi ragu dengan pilihan hidupnya selama ini. Arini begitu tulus menyayangi dirinya dan Akbar, dan berharap agar Meirose kembali pada Pras. Mereka sudah menjadi keluarga. Apalagi ketika sosok Pras muncul dihadapannya, Meirose tidak bisa mengingkari bahwa cintanya pada laki-laki bijak itu masih ada dalam hatinya. Bahkan Arini didukung Nadia, berusaha keras menarik Meirose kembali.\r\n\r\nMeirose bingung, maju dengan kehidupannya yang baru, yang dia sendiri tidak tahu akan jadi seperti apa, ataukah mundur pada kehidupannya yang lama, yang ingin dia tinggalkan selama ini, tapi menjanjikan hal yang lebih pasti bagi masa depannya?\r\n\r\nAda apa dibalik motivasi Arini yang menggebu-gebu meminta Meirose kembali dalam kehidupan rumah tangganya yang sudah harmonis selama ini?\r\n\r\nApa yang akan dilakukan Pras, akankah dia kembali menerima Meirose? Sementara, dia meragukan kemampuannya untuk bersikap adil sebagaimana yang diwajibkan Allah pada laki-laki yang memilih berpoligami?\r\n\r\nSiapa juga Dokter Syarief(Reza Rahardian) yang tiba-tiba hadir di tengah-tengah persoalan mereka?\r\n\r\nLalu mengapa surga itu tiba-tiba menjadi dirindukan sekarang?', 'Drama', 2017, '2017-02-09', 121, 'Hanung Bramantyo, Meisa Felaroze', 'Alim Sudio, Hanung Bramantyo, Manoj Punjabi', 'Reza Rahadian, Raline Shah, Laudya Cynthia Bella, Fedi Nuril', 'https://images-na.ssl-images-amazon.com/images/M/MV5BMTZiZjU4MDEtOWFjZS00YWZkLTg1MGMtNDcxNTdhMDE3NWU2XkEyXkFqcGdeQXVyNjU3MzA0NjE@._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/DpUXJVbb894\" frameborder=\"0\" allowfullscreen></iframe>', 'tt5946936', 0, 0, 0, 0, 0, 2),
(41, 'ULAR TANGGA', 'FINA (20), mahasiswi berwatak serius dan memiliki potensi indigo, sebenarnya sudah memiliki firasat buruk. Mimpi buruk yang muncul dalam tidurnya seperti menghidupkan alarm bahaya dalam diri FINA. Hal itu terkait dengan rencana mendaki gunung tim pecinta alam kampusnya. Kebetulan, tim yang akan berangkat dalam pendakian itu dipimpin BAGAS (21), kekasih FINA. BAGAS tidak percaya pada kekhawatiran FINA. Ia malah membujuk FINA untuk tetap berangkat bersama tim pecinta alam yang beranggotakan empat mahasiswa lain: MARTHA, WILLIAM, DODOY, dan LANI. Pada tahap awal, perjalanan mereka dibantu GINA, seorang pendaki dan penunjuk jalan yang sudah lebih dulu berpengalaman dan mengenal medan di gunung tersebut.\r\n\r\nSayangnya, peringatan GINA agar mereka memilih jalan yang aman, tidak diindahkan oleh teman-teman FINA. Tanpa mereka sadari, jalan yang mereka pilih mengantarkan mereka menuju pohon tua yang angker dan rumah misterius di gunung yang memiliki cerita kelam di masa lalu. FINA baru menyadari bahaya mengancam dirinya dan teman-temannya. Kemunculan dua hantu anak kecil, SANIA dan TANIA, seolah menjadi pertanda akan bahaya yang mengancam mereka. Mereka tersesat di gunung, dan terpaksa bertahan di rumah misterius yang sudah kosong bertahun-tahun.\r\n\r\nNamun, saat mereka berkeinginan turun gunung segalanya sudah terlambat. Kejadian buruk menimpa mereka satu per satu. Semua itu diawali dengan penemuan permainan kuno ular tangga yang terbuat dari kayu, di bawah pohon angker itu. Penemuan itu memunculkan kembali hantu yang sangat berbahaya. Hantu yang penuh kemarahan karena pohon angker yang menjadi tempat tinggalnya, dirusak pada masa lalu. Seseorang telah memotong cabang pohon tersebut dan membuatnya menjadi ukiran permainan ULAR TANGGA. Peristiwa terkutuk yang memakan korban jiwa.', 'Thriller', 2016, '1970-01-01', 94, 'Arie Azis', 'N/A', 'Shareefa Daanish, Guntur Triyoga, Fauzan Nasrul, Alessia Cestaro', 'http://ia.media-imdb.com/images/M/MV5BNTA4OWE2MDItZGQ2My00YWU2LTkyOWYtMWViMDQ0YjcyMmEzXkEyXkFqcGdeQXVyMjIyNDU1OTg@._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/NgATJRgFlHg\" frameborder=\"0\" allowfullscreen></iframe>', 'tt5742836', 0, 0, 0, 0, 0, 2),
(42, 'GUARDIANS OF THE GALAXY 2', 'Pada seri kedua film Guardian of the Galaxy, Peter Quills (Chris Pratt) dan para Guardians kembali melanjutkan petualangan mereka dengan menjelajahi bagian terluar dari kosmos. Kali ini, persahabatan para Guardians akan diuji pada saat mereka mengungkap misteri dari silsilah keluarga Peter Quill. Di film ini juga, seorang musuh lama akan muncul kembali untuk membantu para Guardians dalam misi mereka', 'Action, Adventure, Sci-fi', 2017, '2017-06-17', 137, 'James Gunn', 'James Gunn, Dan Abnett', 'Chris Pratt, Zoe Saldana, Sylvester Stallone', 'http://www.21cineplex.com/data/gallery/pictures/149086206646137_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/duGqrYw4usE\" frameborder=\"0\" allowfullscreen></iframe>', 'tt3896198', 0, 0, 0, 0, 0, 0),
(43, 'THE INFILTRATOR', 'Seorang pejabat Bea Cukai AS Robert Mazur (Bryan Cranston) melakukan penyamaran untuk menyusup ke dalam jaringan perdagangan narkoba yang diatur oleh mafia besar asal Kolumbia, Pablo Escobar.\r\n\r\nMazur menyamar sebagai Bob Musella seorang pengusaha pencucian uang. Dengan penuh resiko yang mengancam keselamatan jiwanya, Mazur bersama agen lainnya mencoba membongkar praktik pencucian uang yang dilakukan Pablo dengan bangkir korup dan 85 gembong narkoba lainnya.', 'Biography, Crime, Drama', 2016, '2016-07-13', 127, 'Brad Furman', 'Ellen Sue Brown (screenplay), Robert Mazur (based on the book on)', 'Bryan Cranston, Leanne Best, Daniel Mays, Tom Vaughan-Lawlor', 'https://images-na.ssl-images-amazon.com/images/M/MV5BMTEwNzM2NjY2MTNeQTJeQWpwZ15BbWU4MDQ3MDI3Njgx._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/N7_M4hjXW1I\" frameborder=\"0\" allowfullscreen></iframe>', 'tt1355631', 7.1, 66, 0, 0, 0, 0),
(45, 'GET OUT', 'Film ini mengisahkan seorang pemuda berkulit hitam bernama Chris (Daniel Kaluuya) yang mengunjungi rumah orang tua kekasihnya yang berkulit putih Rose (Allison Williams).\r\n\r\nDia tak pernah mengira kunjungannya akan menjadi sebuah kejadian yang buruk bagi dirinya hingga seorang pria sesama kulit hitam memperingatkannnya untuk \"keluar\". Kemudian Chris menyadari bahwa untuk \"Keluar\" jauh lebih sulit dari yang ia bayangkan.', 'Horror, Mystery', 2017, '2017-02-24', 104, 'Jordan Peele', 'Jordan Peele', 'Daniel Kaluuya, Allison Williams, Catherine Keener, Bradley Whitford', 'https://images-na.ssl-images-amazon.com/images/M/MV5BNTE2Nzg1NjkzNV5BMl5BanBnXkFtZTgwOTgyODMyMTI@._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/sRfnevzM9kQ\" frameborder=\"0\" allowfullscreen></iframe>', 'tt5052448', 8.3, 83, 0, 0, 0, 1),
(46, 'KARTINI', 'Kartini tumbuh dengan melihat langsung bagaimana ibu kandungnya, Ngasirah (Christine Hakim) menjadi orang terbuang di rumahnya sendiri, dianggap pembantu hanya karena tidak mempunyai darah ningrat. Ayahnya, Raden Sosroningrat (Deddy Sutomo), yang mencintai Kartini dan keluarga juga tidak berdaya melawan tradisi.\r\n\r\nSepanjang hidupnya, Kartini memperjuangkan kesetaraan hak bagi semua orang, tidak peduli ningrat atau bukan, terutama hak pendidikan untuk perempuan. Bersama kedua saudarinya, Roekmini (Acha Septriasa) dan Kardinah (Ayushita Nugraha), Kartini mendirikan sekolah untuk kaum miskin dan menciptakan lapangan kerja untuk rakyat di Jepara dan sekitarnya. \r\n\r\nFilm Kartini adalah perjuangan emosional dari sosok Kartini yang harus melawan tradisi dan bahkan menentang keluarganya sendiri untuk memperjuangkan kesetaraan hak untuk semua orang di Indonesia.', 'Biography, Drama, Family', 2017, '2017-04-01', 122, 'Hanung Bramantyo', 'Bagus Bramanti, Hanung Bramantyo, Hanung Bramantyo, Robert Ronny (additional stories), Robert Ronny (story)', 'Adinia Wirasti, Reza Rahadian, Christine Hakim, Dian Sastrowardoyo', 'https://images-na.ssl-images-amazon.com/images/M/MV5BZDZjY2EzZjAtODQzOC00NmE1LTk1MGYtOGQ4YWYyN2I3NmY0XkEyXkFqcGdeQXVyMTE4OTcyMA@@._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/ePQV41Rk9uw\" frameborder=\"0\" allowfullscreen></iframe>', 'tt5882416', 0, 0, 0, 0, 0, 1),
(47, 'THE GUYS', 'Alfi, seorang karyawan yang bercita-cita jadi bos, ingin mendapatkan cinta Amira, teman sekantornya. Namun, masalah timbul ketika Via, gebetan abadinya, yang tadinya mengabaikan Alfi, mulai menujukkan rasa cinta..  Di satu sisi, ketika Alfi dekat dengan Amira, ternyata', 'Drama', 2017, '2017-11-20', 84, 'Raditya Dika', 'Raditya Dika', 'Raditya Dika, Pevita Pearce, Marthino Lio', 'http://www.21cineplex.com/data/gallery/pictures/148964775232770_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/JxTmtJ0Fc2U\" frameborder=\"0\" allowfullscreen></iframe>', 'tt0319470', 6.5, 60, 0, 0, 0, 1),
(48, 'LABUAN HATI', 'Bia, Indi dan Maria adalah tiga perempuan yang bertemu di Labuan Bajo, Flores, NTT. Hidup di atas kapal, menyelam, bertemu komodo hingga menginap di sebuah pulau yang sama... Tiga perempuan dengan latar belakang berbeda, alasan berbeda, dan tujuan berbeda dengan cepat bersahabat karena kecintaan yang sama: kepada laut, permukaan maupun kedalaman.\r\n\r\nDan ada Mahesa, instruktur diving yang terus mendampingi mereka, yang kemudian menjadi salah satu alasan mereka merasa ada yang salah dengan hidup masing-masing.\r\n\r\nPadahal... tak ada yang salah. Setiap perempuan berhak bersedih atas masa lalu, berhak berbahagia untuk hari ini, dan berhak selalu punya harapan akan masa depan, tanpa perlu mengutuk hidup yang tak melulu baik-baik saja.', 'Drama', 2017, '2017-11-20', 101, 'Lola Amaria', 'Titien Wattimena', 'Kelly Tandiono, Nadine Chandrawinata, Ully Triani', 'http://www.21cineplex.com/data/gallery/pictures/148964668873702_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/D3S_F07qmkY\" frameborder=\"0\" allowfullscreen></iframe>', 'tt0319470', 6.5, 60, 0, 0, 0, 2),
(49, 'HAND OF STONE', 'Hands of Stone akan berkisah tentang kehidupan Roberto Duran (Edgar Ramirez) yang menjadi seorang petinju profesional saat masih berusia 16 tahun. \r\n\r\nDibawah pelatih Ray Arcel (Robert De Niro) Ia menjadi salah satu petarung hebat di masanya dengan mengalahkan petinju terkenal Sugar Ray Leonard.', 'Drama', 2017, '2017-11-20', 110, 'Jonathan Jakubowicz', 'Jonathan Jakubowicz', 'Edgar Ramirez, Ellen Barkin, John Turturro', 'http://www.21cineplex.com/data/gallery/pictures/14897369646193_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/1W1L0WnVnjY\" frameborder=\"0\" allowfullscreen></iframe>', 'tt0319470', 6.5, 60, 0, 0, 0, 0),
(50, 'THE WORST YEARS OF MY LIFE', 'MIDDLE SCHOOL: THE WORST YEARS OF MY LIFE\r\n\r\nRafe Khatchadorian (Griffin Gluck) adalah remaja yang memiliki imajinasi tinggi. Namun ia punya banyak masalah, mulai dengan pacar sang Ibu sampai lingkungan baru di sekolah. \r\n\r\nMasalah terbesar Rafe adalah sang kepala sekolah Dwight (Andrew Daly) yang selalu mengekang kreativitas murid di sekolah dan ketat terhadap peraturan. \r\n\r\nMisi dilakukan Rafe bersama Leo (Thomas Barbusca) dan teman-tema sekolanya untuk membalas perlakuan sang kepala sekolah yang otoriter.', 'Comedy, Family', 2016, '2016-10-07', 92, 'Steve Carr', 'Chris Bowman (screenplay), Hubbel Palmer (screenplay), Kara Holden (screenplay), James Patterson (based on the book by), Chris Tebbetts (based on the book by)', 'Griffin Gluck, Lauren Graham, Alexa Nisenson, Andrew Daly', 'https://images-na.ssl-images-amazon.com/images/M/MV5BNjQzMTczNjI0Ml5BMl5BanBnXkFtZTgwODY5MTY5OTE@._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/XQtjPUyS6ZY\" frameborder=\"0\" allowfullscreen></iframe>', 'tt4981636', 6.1, 51, 0, 0, 0, 0);
INSERT INTO `film` (`id`, `title`, `summary`, `genre`, `year`, `playing_date`, `length`, `director`, `writer`, `actors`, `poster`, `trailer`, `imdb_id`, `imdb_rating`, `metascore`, `twitter_positif`, `twitter_negatif`, `rating`, `status`) VALUES
(51, 'SWEET 20', 'Fatmawati (Niniek L Karim), nenek 70 tahun cerewet yang tinggal bersama putranya seorang dosen, Aditya (Lukman Sardi), menantu (Cut Mini), dan 2 orang cucu (Kevin Julio, Alexa Key). Fatmawati selalu membanggakan Aditya, sampai suatu hari ia mengetahui akan dikirim ke panti jompo. Hal yang membuatnya sangat terpukul dan pergi dari rumah. Di perjalanan ia melihat studio foto “Forever Young”, dan berniat mengambil foto untuk di pemakamannya kelak. Namun setelah berfoto Fatmawati berubah menjadi 50 tahun lebih muda, dan kembali berusia 20 tahun. Fatmawati pun memulai kehidupan yang baru dan mengganti namanya menjadi Mieke, seperti nama artis idolanya Mieke Wijaya. Seiring berjalannya waktu, Mieke mendapat kesempatan untuk meraih mimpinya menjadi penyanyi, sesuatu yang tidak bisa dilakukannya pada saat muda dulu. Keunikan Mieke muda dengan gaya bicara dan seleranya yang masih seperti nenek 70 tahun, justru membuat 3 pria jatuh hati padanya, seorang produser musik (Morgan Oey), cucu laki-lakinya, dan Hamzah (Slamet Rahardjo) yang mencintainya sejak sama-sama muda dulu.\r\n\r\nHingga suatu peristiwa terjadi, yang membuat Mieke harus memilih untuk melanjutkan kehidupan barunya, atau kembali menjadi Fatmawati.', 'Comedy, Family', 2016, '2016-10-07', 92, 'Steve Carr', 'Chris Bowman (screenplay), Hubbel Palmer (screenplay), Kara Holden (screenplay), James Patterson (based on the book by), Chris Tebbetts (based on the book by)', 'Griffin Gluck, Lauren Graham, Alexa Nisenson, Andrew Daly', 'http://www.21cineplex.com/data/gallery/pictures/149086177359456_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/dZb6uIoVFIg\" frameborder=\"0\" allowfullscreen></iframe>', 'tt4981636', 6.1, 51, 0, 0, 0, 0),
(52, 'THE CURSE', 'Shelina seorang pengacara ekspatriat Indonesia bekerja disalah satu law firm ternama di Melbourne Australia.\r\n \r\nSuatu malam Shelina didatangi roh halus di rumahnya. Penampakan roh halus itu semakin hari semakin jelas dan sangat menakutkan. Akhirnya Shelina memanggil paranormal dari Indonesia guna melakukan pengusiran. Hasil ritual paranormal menemukan, roh halus itu mendatangi Shelina untuk menyampaikan pesan. Dan roh halus itu mempunyai kaitan yang sangat erat dengan Shelina.\r\n \r\nTeka teki dibalik kedatangan roh halus, membuat Shelina harus menghadapi sesuatu yang sangat menakutkan.', 'Sci-Fi, Horror', 2017, '2017-09-11', 92, 'David Keith', 'David Chaskin (screenplay)', 'Wil Wheaton, Claude Akins, Malcolm Danare, Cooper Huckabee', 'https://images-na.ssl-images-amazon.com/images/M/MV5BOTZhM2M1NGItZTdhNS00ODMxLWJjYTQtNTQ2Yzg4ZWZhMTM2XkEyXkFqcGdeQXVyMTQxNzMzNDI@._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/rvmN3mZIqdI\" frameborder=\"0\" allowfullscreen></iframe>', 'tt0092809', 4.9, 0, 0, 0, 0, 0),
(53, 'SURAU DAN SILEK', 'Adil (11th) adalah seorang anak yatim yang sangat menginginkan Ayah nya masuk surga dengan cara menjadi anak yang shaleh. Di saat yang bersamaan Adil juga sangat berambisi memenangkan pertandingan Silat di kampungnya. Ambisi Adil ini di dasari oleh kekalahan yang di alaminya pada pertandingan periode sebelumnya. Adil di kalahkan oleh Hardi (11Th) dengan kecurangan. Namun hal ini tidak di akui oleh Hardi. Karena menurut Hardi, Adil hanya mencari-cari alasan atas kekalahannya. Teman seperguruan Adil yang juga merupakan sahabatnya; Dayat (11th) dan Kurip (11th) ikut mendukung upaya membalaskan dendam kekalahan tersebut. \r\n \r\nDengan semangat yang tinggi mereka mempersiapkan diri menuju pertandingan berikutnya yang akan di adakan 6 bulan lagi. Dalam masa persiapan tersebut tiga sekawan; Adil, Dayat dan Kurip mengalami berbagai rintangan; Mulai dari guru silat mereka Rustam (27) yang pergi merantau, keinginan Adil untuk menjadi anak shaleh yang kadang bertentangan dengan ambisinya, pertikaian yang terjadi di antara Tiga Sekawan dalam memandang makna silat, Hardi dan kawan-kawan yang selalu mem-bully Adil, Dayat dan Kurip serta pencarian guru silat pengganti yang gagal disaat waktu menuju pertandingan terus berjalan.\r\n \r\n Rani (11th) merupakan teman sekolah Tiga sekawan (Adil, Kurip dan Dayat). Rani yang mengagumi Adil secara diam diam berusaha untuk mencarikan solusi terhadap kegalauan yang di hadapi oleh teman-temannya. Berkat usaha Rani inilah semua rintangan yang di hadapi oleh Tiga sekawan ini dapat teratasi. Berhasilkah Tiga Sekawan menemukan guru silat serta memenangkan pertandingan? ', 'Drama, Religi', 2017, '2017-09-11', 92, 'Arief Malinmudo', 'Arief Malinmudo', 'Dewi Irawan, Gilang Dirga, Komo Ricky', 'http://www.21cineplex.com/data/gallery/pictures/148956125842101_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/unyKxrBdHdA\" frameborder=\"0\" allowfullscreen></iframe>', 'tt0092809', 4.9, 0, 0, 0, 0, 0),
(54, 'HITAM DAN PUTIH', 'Ruanli dan Ashui adalah kakak beradik. Ruanli bekerja sebagai pelatih bela diri sedangkan Ashui diam-diam bekerja sebagai pemain film tanpa tahu Ruanli.\r\n\r\nSementara Yurong adalah seorang wanita polisi yang sedang menyamar, dia sedang menyelidiki kasus Narkoba kelompok Chutian. Chutian adalah direktur utama berasal dari Hongkong, dia berbisnis tempat hiburan tapi sebenarnya berbisnis gelap narkoba. \r\n\r\nRuanli dan Megda sedang berakting sebagai polisi dan penjahat. Disaat yang bersamaan Ashi dan Jinmao membawa tas hitam dengan uang. Ashi dan Jinmao kira Ruanli adalah polisi, kaget dan buru-buru kabur dengan membawa uang. Untuk menghindar dari kejaran Ruanli, uang itu di simpannya di sebuah motor yang ternyata itu adalah motor Ashui. \r\n\r\nRuanli, Adang, Azun dan Ganni merencanakan untuk menyelamatkan Ashui dari tangan Chutian. Terjadi pertukaran antara Ashui dengan Tas hitam tanpa diduga ternyata Chutian merencanakan sesuatu sampai Chutian berhasil membawa kabur Ashui sebagai sandera. Chutian berhasil di kepung, dengan akal liciknya dia berusaha membuang barang bukti demi menghindar dari hukum. \r\n\r\nBagaimana akhir kisahnya, saksikan di bioskop mulai 13 April 2017.', 'Drama, Action', 2017, '2017-04-13', 91, 'Daud Radex', 'Susan Yu', 'Roger Danuarta, Guntur Triyoga, Sunny Pang', 'http://www.21cineplex.com/data/gallery/pictures/148956110023905_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/vhiK5cDluhc\" frameborder=\"0\" allowfullscreen></iframe>', 'tt5351016', 0, 0, 0, 0, 0, 2),
(55, 'MENGEJAR HALAL', 'Kisah Haura berawal dengan batalnya pernikahan Haura dan Shidiq. Kegagalan pernikahan membuat Haura menjadi wanita yang terobsesi pada pernikahan sempurna, baginya butuh pria sempurna untuk mendapatkan pernikahan sempurna. Ditengah kegalauan menanti sosok pria sempurna, Haura dipertemukan dengan sosok Halal, pria yang memiliki semua yang diharapkan haura ada pada pasangan hidupnya.\r\n\r\nHaura melakukan segala cara demi menjadikan Halal pasangan hidupnya, Hubungan Haura dengan saudara dan sahabat-sahabatnya mulai renggang karena sikap egois Haura yang semakin tak terkendali kala mengejar Halal. Mampukah Haura mendapatkan Halal dan meraih pernikahan sempurna impiannya?', 'Drama, Comedy', 2010, '2010-11-01', 75, 'M. Amrul Ummami', 'M. Ali Ghifari', 'Inez Ayuningtyas, Abdul Kaafi, Ressa Rere, Ryan Qori, Ahmad Rhezanov', 'http://www.21cineplex.com/data/gallery/pictures/148956160311346_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/skSeKOOvMgg\" frameborder=\"0\" allowfullscreen></iframe>', 'tt5351016', 0, 0, 26, 27, 0, 1),
(56, 'NIGHT BUS', 'Bis Babad mengangkut beberapa penumpang menuju Sampar, sebuah kota yang terkenal kaya akan sumber daya alamnya namun terjadi konflik berkepanjangan. Sampar dijaga ketat oleh aparat pemerintah pusat yang siap siaga melawan pasukan Samerka (Sampar Merdeka), para milisi pemberontak yang menuntut kemerdekaan atas tanah kelahiran mereka.\r\n\r\nSetiap penumpang bis memiliki tujuannya masing- masing : mencari penghidupan yang lebih baik, memenuhi kebutuhan keluarga, menyelesaikan masalah pribadi atau sesederhana ingin pulang ke kampung halaman.\r\n\r\nMereka berpikir bahwa ini akan menjadi perjalanan seperti biasa, tanpa mereka sadari ada penyusup masuk ke dalam bis, membawa pesan penting yang harus di sampaikan ke Sampar. \r\n\r\nKehadiran penyusup membahayakan semua penumpang, orang paling dicari oleh kedua pihak yang bertikai, perintahnya temukan hidup atau mati!!\r\n\r\nSituasi menjadi semakin menegangkan ketika seluruh penumpang harus memperjuangkan hidupnya di antara desingan peluru. \r\n\r\nMereka bahkan harus menghadapi pihak lain yang justru tidak menginginkan konflik berakhir. Kaum oportunis, pemelihara konflik karena mereka hidup dari konflik, kesadisan dan kebengisan mereka semakin memberikan teror pada para penumpang bus.\r\n\r\nTidak ada yang tahu, siapa akan hidup dan siapa akan mati?!', 'Drama, Thriller, Action', 2007, '2007-05-11', 135, 'Emil Heradi', 'Rahabi Mandra', 'Yayu Unru, Teuku Rifnu Wikana, Hana Prinantina', 'http://www.21cineplex.com/data/gallery/pictures/148947703512087_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/VO9hVH6k3F0\" frameborder=\"0\" allowfullscreen></iframe>', 'tt0865924', 6.8, 0, 0, 0, 0, 2),
(57, 'BEST FRIEND FOREVER', 'Berkisah tentang ke empat remeja perempuan, Bianca(19th), Laura(18th), Sascha(19th) dan Tammy(18th) berencana menghabiskan jumat malam bersama. Kali ini Bianca merencanakan dengan sangat tertata, tidak seperti malam-malam sebelumnya. Namun, kenyataan tak seperti apa yang mereka berempat bayangkan. Rencana yang Bianca buat berubah 180 derajat, dan menjadi malapetaka.\r\n\r\nBianca, Laura, Sascha dan Tammy menjalani malam yang penuh dengan malapetaka yang datang hilir berganti menghampiri mereka berempat.\r\n\r\nPerjuangan Bianca, Laura, Sascha dan Tammy keluar dari situasi buruk, berujung Bianca harus kehilangan jejak pria idamannya. Yang membuat Bianca marah besar, sehingga memicu perkelahian diantara mereka berempat.\r\n\r\nDi malam yang sama Keempat sahabat ini menghadapi aksi-aksi berbahaya yang hampir merenggut nyawa dan membuat persahabatan mereka hancur.\r\n\r\nTingkah polos Bianca, Sascha, Tammy dan Laura dalam mengahadapi masalah membuat kekonyolan yang sangat lucu dan mampu memberikan arti tersendiri diantara mereka.\r\n\r\nNamun apakah Bianca, Sascha, Tammy dan Laura akan keluar dari mala petaka yang mengejar mereka pada malam itu ? Apa mungkin persahabatan mereka kembali baik seperti semula. Mampukah Bianca bertemu pria idamannya, serta siapakah pria idaman Bianca ? Saksikan kisah mereka dalam film Best Friends Forever, yang akan tayang di bioskop 6 April 2017.', 'Short, Comedy', 2012, '2012-07-19', 0, 'Estelle Linden', 'M. Rizkhi Faiz', 'Wendy Wilson, Kezia Karamoy, Yova Gracia, Ineke Valentina', 'http://www.21cineplex.com/data/gallery/pictures/148947679482206_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/WFox4ttcPM8\" frameborder=\"0\" allowfullscreen></iframe>', 'tt2558934', 0, 0, 0, 0, 0, 2),
(65, 'PHILLAURI', 'Seorang pemuda asal Mangalik, Kanan (Suraj Sharma) menikahi sebuah pohon sebagai ritual tolak bala agar dapat menikahi wanita pilihannya. \r\n\r\nNamun tanpa di sangka-sangka pohon yang ia nikahi tersebut berisi roh halus yang kemudian terus mengikuti langkah Kanan.', 'Comedy, Drama, Fantasy', 2017, '2017-03-24', 134, 'Anshai Lal', 'Anvita Dutt', 'Anushka Sharma, Diljit Dosanjh, Suraj Sharma, Mehreen Pirzada', 'https://images-na.ssl-images-amazon.com/images/M/MV5BMjA5Mjc3MTYyMl5BMl5BanBnXkFtZTgwMTgzNDkzMTI@._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/uCTr7MGFK0U\" frameborder=\"0\" allowfullscreen></iframe>', 'tt5502766', 7.5, 0, 0, 0, 0, 2),
(69, 'SPIRITED AWAY', 'Film animasi karya sutradara ternama Jepang Hayao Miyazaki ini bercerita tentang kisah seorang gadis berumur 10 tahun bernama Chihiro dan kedua orangtuanya yang masuk ke sebuah tempat yang terlihat sebagai sebuah taman hiburan yang terabaikan. \r\n\r\nSetelah kedua orangtuanya berubah menjadi babi raksasa, Chihiro bertemu dengan sosok misterius Haku yang menjelaskan kepadanya bahwa tempat mereka berada adalah sebuah resort di mana makhluk supernatural berisitrahat dari alam duniawi. Untuk dapat membebaskan kedua orangtuanya, Chihiro harus bekerja di sebuah bath house yang dikepalai oleh penyihir bernama Yubaba.', 'Animasi, Drama', 2001, '2003-03-28', 122, 'Hayao Miyazaki', 'Hayao Miyazaki', 'Rumi Hiiragi, Miyu Irino, Mari Natsuki, Takashi Naitô', 'https://images-na.ssl-images-amazon.com/images/M/MV5BMTk3NTM1NTg1Ml5BMl5BanBnXkFtZTgwOTgzMTMyMDE@._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/ByXuk9QqQkk\" frameborder=\"0\" allowfullscreen></iframe>', 'tt0245429', 8.6, 94, 0, 0, 0, 2),
(70, 'CRITICAL ELEVEN', 'Melalui Critical Eleven, pemenang Piala Citra Reza Rahadian dan Adinia Wirasti menghidupkan Ale dan Anya dari novel national bestseller karya Ika Natassa, pasangan muda yang bertemu dan saling terpikat dalam penerbangan Jakarta-Sydney. Pertemuan itu membawa mereka ke jenjang pernikahan, dan membuat keduanya mengambil keputusan besar sebagai pasangan karena pengorbanan yang harus dilakukan salah satu dari mereka: pindah ke New York.\r\n\r\nKota yang tidak pernah tidur ini ternyata membawa berkah bagi keduanya: kehamilan Anya yang mengubah hidup mereka. Sampai Ale dan Anya diterjang sebuah insiden yang membuat mereka tidak hanya mempertanyakan cinta, namun juga bergumul dengan ego dan harus memilih: menyerah dalam amarah atau menyembuhkan luka dan bertahan dalam ketidakpastian. Pilihan sulit itu bertambah pelik dengan kehadiran seseorang yang sudah lama mencintai Anya.', 'Drama', 2017, '2017-01-15', 120, 'Monty Tiwa', 'Jenny Jusuf, Ika Natassa', ' Reza Rahadian, Mark C. Fullhardt, Hannah Al Rashid', 'http://www.21cineplex.com/data/gallery/pictures/149155653948865_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/tn-IiueNqsY\" frameborder=\"0\" allowfullscreen></iframe>', 'tt6426714', 0, 0, 0, 0, 0, 0),
(98, 'SMURFS THE LOST VILLAGE', 'Ketika Smurfette (Demi Lovato) menemukan sebuah peta misterius, ia bersama para sahabatnya Brainy, Clumsy dan Hefty pergi dalam sebuah petualangan menuju sebuah hutan terlarang yang dihuni oleh hewan ajaib untuk mecari sebuah desa misterius sebelum penyihir jahat Gargamel menemukannya. Dengan melalui perjalan yang dipenuhi rintangan dan bahaya, para Smurf akan menemukan sebuah rahasia terbesar dalam sejarah kaum Smurf.', 'Animation, Adventure, Comedy', 2017, '2017-04-07', 89, 'Kelly Asbury', 'Stacey Harman, Pamela Ribon, Peyo (based on the characters and works of)', 'Demi Lovato, Rainn Wilson, Joe Manganiello, Jack McBrayer', 'https://images-na.ssl-images-amazon.com/images/M/MV5BMTg1NjgyMTYzM15BMl5BanBnXkFtZTgwMzIxNDc4MDI@._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/vu1qZCG6Yo8\" frameborder=\"0\" allowfullscreen></iframe>', 'tt2398241', 5.8, 40, 23, 13, 0, 1),
(113, 'SATRIA HEROES REVENGE OF DARKNESS', 'Satu tahun telah berlalu semenjak satria Garuda Bima X mengalahkan Vudo. Warga Jakarta hidup dalam keadaan damai. Ray bersama Rena sedang menjaga kedamaian dan membangun dunia pararel kembali. \r\n\r\nDi bumi, Dimas Akhsara sedang mengembangkan bisnisnya ke Jepang dan bertemu dengan pimpinan Takarada Corp. Akan tetapi kekuatan jahat baru kembali menyerang dan Dimas menjadi korbannya. \r\n\r\nRay yang berada di dunia pararel memutuskan untuk kembali ke bumi untuk menyelidiki. Tanpa sepengetahuan Ray, musuh lama kembali bangkit dan memporak-porandakann Jakarta. Pertarungan sengit tidak terhindari.', 'Drama', 2017, '2014-11-01', 120, 'Kenzo Maihara, Arnandha Wyanto', 'Reino Barack, Ishimori Production', 'Christian Loho,Fernando Surya, Yayan Ruhian, Adhitya Alkatiri', 'http://www.21cineplex.com/data/gallery/pictures/149205837172577_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/uKTu5gX1g4Q\" frameborder=\"0\" allowfullscreen></iframe>', '', 0, 0, 0, 0, 0, 0),
(114, 'SETERU', 'Bertahun-tahun tawuran antara SMA Kesatuan Bangsa dan SMA Budi Pekerti di kota Yogyakarta berlangsung hingga memakan korban jiwa dan mewariskan dendam antar angkatan. SMA Kesatuan Bangsa didominasi siswa keturunan kelas menengah ke atas, sementara SMA Budi Pekerti didominasi oleh siswa pribumi kelas menengah bawah. \r\n\r\nPimpinan kedua sekolah sepakat untuk menyerahkan pentolan tawuran dari masing-masing sekolah untuk dibina oleh Letnan Kolonel RAHMAT (Mathias Muchus) sebagai Komandan Kodim yang menaungi kedua sekolah tersebut. Letkol Rahmat kemudian menugaskan pembinaan anak-anak tersebut kepada Letnan Satu MAKBUL (Alfie Alfandy), seorang perwira cemerlang yang dikenal keras kepada anak buah. Anak-anak itu, yang dipimpin MARTIN TAN (Bio One) dan RIDWAN (Yusuf Mahardika) dimasukkan ke Batalyon Inftantri 403 Wirasada Pratista di Kentungan, Yogyakarta.\r\n\r\nTempaan pembinaan meluluhkan perbedaan dan permusuhan di antara kedua kelompok itu, bahkan mereka bersatu menjadi kelompok olah raga futsal yang berprestasi. Tapi itu semua tidak mudah karena masih ada teman-teman mereka sendiri yang masih ingin memelihara dendam. Bahkan, ketika mereka bersiap meraih prestasi, teror dan dendam masih membayangi mereka. Dapatkah mereka mengatasi dendam dan perbedaan?', 'Drama', 2017, '2017-12-01', 120, 'Hanung Bramantyo', 'Bagus Bramanti', 'Yusuf Mahadika, Bio One Mathias, Muchus Mathias, Alfie Alfandy', 'http://www.21cineplex.com/data/gallery/pictures/149189477457886_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/0mnsgj8W6rU\" frameborder=\"0\" allowfullscreen></iframe>', '', 0, 0, 0, 0, 0, 0),
(115, 'SELEBGRAM', 'Kamal (Aldi Maldini) baru saja lulus kuliah. Kehidupannya berubah drastis ketika Bapaknya bangkrut. Semua kekayaannya ludes. Kisah cinta yang dibina bersama Billa (Barbie) juga kandas. Calon mertuanya memutuskan rencana pernikahan mereka.\r\n\r\nHidup Kamal bagaikan diujung tanduk ketika bapaknya berutang banyak pada rentenir. Meskipun begitu, Kamal selalu mencari jalan untuk membantu Bapaknya. Dia berharap bisa menjadi Selebgram agar bisa mendapat endorse dan menghasilkan uang. Sayang, menjadi Selebgram bukanlah perkara mudah.\r\n\r\nKamal putus asa. Cinta dan harta telah berpaling darinya. Ditengah keputus asaan itu, Kamal bertemu dengan Cello (Syifa Hadju). Gadis tunawicara itu mengubah jalan hidupnya. Cinta menggetarkan hati Kamal dan membuatnya mampu menghadapi semua persoalan dibantu dengan Sherly (Ria Ricis), Kakak Cello.\r\n\r\nBagaimana Kamal mengatasi semua masalahnya? Berhasilkah Kamal mewujudkan mimpinya untuk jadi Selebgram? Saksikan film hiburan ini di bioskop kesayangan Anda, 10 Mei 2017.', 'Comedy', 2017, '2017-05-10', 120, 'Wishnu Kuncoro', 'Herri Arissa, Puji Lestari', ' Ria Ricis, Billa Barbie, Arief Didu, Opie Kumis, Aldi Maldini, Syifa Hadju', 'http://www.21cineplex.com/data/gallery/pictures/149179495071313_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/7pzvWzZEIXE\" frameborder=\"0\" allowfullscreen></iframe>', '', 0, 0, 0, 0, 0, 0),
(119, 'STIP & PENSIL', 'Toni (Ernest Prakasa), Aghi (Ardit Erwandha), Bubu (Tatjana Saphira) dan Saras (Indah Permatasari) adalah anak anak orang kaya yang dimusuhi anak anak di SMU sekolahnya. Karena dibanding yang lain mereka selalu merasa sok jago dan songong.', 'Comedy, Family', 2016, '2016-10-07', 92, 'Steve Carr', 'Chris Bowman (screenplay), Hubbel Palmer (screenplay), Kara Holden (screenplay), James Patterson (based on the book by), Chris Tebbetts (based on the book by)', 'Griffin Gluck, Lauren Graham, Alexa Nisenson, Andrew Daly', 'http://www.21cineplex.com/data/gallery/pictures/148997964817267_300x430.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/spIsyTJ0DS8\" frameborder=\"0\" allowfullscreen></iframe>', 'tt4981636', 6.1, 51, 0, 0, 0, 1),
(120, 'KONG SKULL ISLAND', 'Pada tahun 1970an, sekelompok tim penjelajah dikirim untuk menelusuri sebuah pulau misterius bernama Skull Island yang terletak di samudera Hindia. Mereka segera menyadari ancaman yang terdapat di pulau tersebut sebab Skull Island adalah rumah dari kera raksasa bernama King Kong yang memiliki kekuatan dahsyat dan kecerdasan yang menyerupai manusia.', 'Action, Adventure, Fantasy', 2017, '2017-03-10', 119, 'Jordan Vogt-Roberts', 'Dan Gilroy (screenplay), Max Borenstein (screenplay), John Gatins (story), Dan Gilroy (story), Merian C. Cooper (original story), Edgar Wallace (original story)', 'Tom Hiddleston, Corey Hawkins, Brie Larson, Samuel L. Jackson', 'https://images-na.ssl-images-amazon.com/images/M/MV5BMTUwMzI5ODEwNF5BMl5BanBnXkFtZTgwNjAzNjI2MDI@._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/44LdLqgOpjo\" frameborder=\"0\" allowfullscreen></iframe>', 'tt3731562', 0, 0, 0, 0, 0, 2),
(121, 'GUARDIANS OF THE GALAXY VOL. 2', 'Pada seri kedua film Guardian of the Galaxy, Peter Quills (Chris Pratt) dan para Guardians kembali melanjutkan petualangan mereka dengan menjelajahi bagian terluar dari kosmos. Kali ini, persahabatan para Guardians akan diuji pada saat mereka mengungkap misteri dari', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'http://www.21cineplex.com/data/gallery/pictures/149086206646137_300x430.jpg', NULL, NULL, NULL, NULL, 0, 0, 0, 3),
(122, 'MIDDLE SCHOOL: THE WORST YEARS OF MY LIFE', 'Imajinatif tenang remaja Rafe Katchadorian lelah dari sekolah menengah obsesi dengan aturan di biaya apapun dan semua kreativitas. Putus asa untuk menggoyang, Rafe dan teman-temannya terbaik telah datang dengan rencana: mematahkan setiap aturan tunggal di sekolah dan membiarkan siswa berjalan liar.', 'Comedy, Family', 2016, '2016-10-07', 92, 'Steve Carr', 'Chris Bowman (screenplay), Hubbel Palmer (screenplay), Kara Holden (screenplay), James Patterson (based on the book by), Chris Tebbetts (based on the book by)', 'Griffin Gluck, Lauren Graham, Alexa Nisenson, Andrew Daly', 'https://images-na.ssl-images-amazon.com/images/M/MV5BNjQzMTczNjI0Ml5BMl5BanBnXkFtZTgwODY5MTY5OTE@._V1_SX300.jpg', NULL, 'tt4981636', 6.1, 51, 0, 0, 0, 3),
(123, 'DANUR', 'Gadis yang mendapatkan persahabatan dengan 5 hantu.', 'Horror', 2017, '2017-03-30', 78, 'Awi Suryadi', 'N/A', 'Prilly Latuconsina, Shareefa Daanish, Asha Kenyeri Bermudez, Kevin Bzezovski', 'https://images-na.ssl-images-amazon.com/images/M/MV5BYWNlODg3YTMtMTQxNC00NGFkLTk1MGQtMDhiYWU3ZjVjYjM2XkEyXkFqcGdeQXVyMzMzNjk0NTc@._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/YLU6Qfi0cDY\" frameborder=\"0\" allowfullscreen></iframe>', 'tt6496236', 6.8, 0, 0, 0, 0, 1),
(128, 'KING ARTHUR: LEGEND OF SWORD', 'Petualangan Arthur (Charlie Hunnam) bersama teman-temannya membawa dia mengangkat pedang Excalibur yang melegenda.  Kemudian dengan pedang &ldquo;magis&rdquo; Excalibur itu, Arthur bergabung dengan seorang wanita misterius bernama Guinevere.   Ia terus mempelajari kekuatan pedang tersebut untuk bisa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'http://www.21cineplex.com/data/gallery/pictures/149248561879841_300x430.jpg', NULL, NULL, NULL, NULL, 0, 0, 0, 3),
(129, 'ALIEN: COVENANT', 'Awak kapal koloni Perjanjian, terikat untuk sebuah planet terpencil di sisi jauh galaksi, menemukan apa yang mereka pikir adalah surga yang belum dipetakan, tapi ini benar-benar gelap, berbahaya di dunia. Ketika mereka menemukan sebuah ancaman di luar imajinasi mereka, mereka harus mencoba melarikan diri mengerikan', 'Sci-Fi, Thriller', 2017, '2017-05-19', 0, 'Ridley Scott', 'John Logan (screenplay), Dante Harper (screenplay), Jack Paglen (story by), Michael Green (story by), Dan O\'Bannon (based on characters created by), Ronald Shusett (', 'Katherine Waterston, Michael Fassbender, James Franco, Noomi Rapace', 'https://images-na.ssl-images-amazon.com/images/M/MV5BNzI5MzM3MzkxNF5BMl5BanBnXkFtZTgwOTkyMjI4MTI@._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/H0VW6sg50Pk\" frameborder=\"0\" allowfullscreen></iframe>', 'tt2316204', 0, 0, 0, 0, 0, 0),
(130, 'AFTERMATH', 'Dua orang asing\' hidup menjadi terikat erat bersama-sama setelah bencana kecelakaan pesawat. Terinspirasi oleh peristiwa-peristiwa aktual, SETELAH bercerita tentang rasa bersalah dan balas dendam setelah pengendali lalu lintas udara (Scoot McNairy) kesalahan yang menyebabkan kematian seorang mandor bangunan (Arnold Schwarzenegger) istri dan putrinya.', 'Drama, Thriller', 2017, '2017-04-07', 92, 'Elliott Lester', 'Javier Gullón', 'Arnold Schwarzenegger, Maggie Grace, Kevin Zegers, Scoot McNairy', 'https://images-na.ssl-images-amazon.com/images/M/MV5BMWFkYmM2ZTctNmZjZi00MWQwLWI0MjctZDdiNjJlMTlmNzdkL2ltYWdlXkEyXkFqcGdeQXVyMjM4NTM5NDY@._V1_SX300.jpg', '<iframe width=\"854\" height=\"480\" src=\"https://www.youtube.com/embed/ZN8toxhSn9Y\" frameborder=\"0\" allowfullscreen></iframe>', 'tt4581576', 6, 43, 0, 0, 0, 1),
(131, 'STIP DAN PENSIL', NULL, 'Drama', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 4);

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `film_id` int(11) NOT NULL,
  `email` varchar(256) NOT NULL,
  `rating` tinyint(2) NOT NULL,
  `review` text NOT NULL,
  `tanggal` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`id`, `film_id`, `email`, `rating`, `review`, `tanggal`) VALUES
(4, 26, 'meloniaseven@gmail.com', 7, 'Great. Love this one. Super funny. So many lovable characters (I love the batman!!)', '2017-03-08 21:56:21'),
(5, 33, 'meloniaseven@gmail.com', 6, 'Meh, not that great.\r\nSangat mengecewakan ya buat yg udah nonton semua seri xmen. Ini film yg memang buat jadi akhirnya dr xmen, tapi ya ampun, mengecewakan deh pokoknya. Ceritanya ga jelas, karakteristiknya Wolverine The Legend yg dr awal cerita itu immortal + ageless, sekarang tiba-tiba jadi tua dan sakit\"an. Darimana bisa kayak gitu? Ga dijelasin. Sama kayak film xmen sebelumnya (Future Past), ga jelas darimana awalnya. Top deh.', '2017-03-08 21:56:21'),
(6, 35, 'meloniaseven@gmail.com', 6, 'Film apa ini, ga jelas banget', '2017-03-08 21:58:49'),
(8, 29, 'scooby@gmail.com', 9, 'Top deh. Suka banget gw sama film ini.', '2017-03-08 21:59:50'),
(9, 33, 'scooby@gmail.com', 9, 'action-nya keren banget brooohhh!! love laura!!', '2017-03-08 21:59:50'),
(12, 35, 'scooby@gmail.com', 7, 'yahh oke lahh', '2017-03-09 17:36:27'),
(14, 29, 'meloniaseven@gmail.com', 9, 'dayumm sooo coooolllll', '2017-03-13 21:08:06');

-- --------------------------------------------------------

--
-- Table structure for table `tweets`
--

CREATE TABLE `tweets` (
  `id` int(11) NOT NULL,
  `film_id` int(11) NOT NULL,
  `tweet` varchar(256) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tweets`
--

INSERT INTO `tweets` (`id`, `film_id`, `tweet`, `status`) VALUES
(1, 0, 'kebebasan kami tersembunyi dibalik topeng ini perlu celah untuk memasuki dinding yang rapat HASHTAG HASHTAG HASHTAG ', 1),
(2, 9, 'raihlah harimu mereka terlalu muda untuk itu     HASHTAG HASHTAG HASHTAG URL', 0),
(3, 9, 'USERNAME USERNAME judul JUDULFILM pemain utama tengkorak dan susumyoui r 21', 0),
(4, 9, '  JUDULFILM  berjaya kumpul kutipan sebanyak as 1 bilion URL HASHTAG', 0),
(5, 9, 'damn bryannnnn sengajaaa buat aku jelessssss aaaaaa nak JUDULFILM         ', 0),
(6, 9, 'saya menemui rakaman control your temper  yang hebat ini di HASHTAG URL HASHTAG', 1),
(7, 9, 'haah baru teringat dalam JUDULFILM siapa yang gay eh hahahaha tak perasan', 0),
(8, 9, 'soundtarck JUDULFILM ini kok jauh sekali dari musik yang magical dan malah mirip musik kondangan  ', 1),
(9, 9, 'dengar lagu JUDULFILM terus bayang ke sweet an cerita tu even beast tu ganas but i like haha', 0),
(10, 9, 'kolaborasi veronica dan anak dalam JUDULFILM URL', 1),
(11, 9, 'USERNAME fast furious kalau ga ada bom bukan fast furious  JUDULFILM nantinya  ali URL', 0),
(12, 9, 'kasih rekomendasi lagu utk didenger dong   JUDULFILM URL', 1),
(13, 9, 'ingat nak tgok movie JUDULFILM tapi   kat todays takde lol malas sia nak pergi aeon dah lay mahal hm', 0),
(14, 9, 'emma yang tak berapa emma bahhahaha haha yes pls lentokkan kepala anda      HASHTAG URL', 1),
(15, 9, 'JUDULFILM ni best tapi part menyanyi tak boleh banyak lagi ke        ', 0),
(16, 9, 'hdh jd ingin dinyanyiin lagu JUDULFILM', 1),
(17, 9, 'JUDULFILM ada tayang lagi ke busy travel sampai ketinggalan pasal movie kejap ', 0),
(18, 9, 'dahh  aku nk ttop fyp ni bru nk tengok HASHTAG ni  ketinggalan zmn aku ni  org lain dok pkt kalot tngok fast 8 dah sobs   ', 0),
(19, 9, 'USERNAME hahaha  baru tgk boss baby jewww nk tgk JUDULFILM depa boys mesti bosan sat gi      ', 1),
(20, 9, '  cinta tak pernah salah dan cinta tak pernah menyakiti HASHTAG JUDULFILM ', 1),
(21, 9, 'ngos ngos an nyanyi ini    HASHTAG HASHTAG HASHTAG URL', 1),
(22, 9, 'si kucrit lg maen keyboard HASHTAG maaf ada banyak gangguan USERNAME  komplek  URL', 0),
(23, 9, 'aku sorang je ke yg sedih plus mata bergenang ermmm tgk cerita JUDULFILM tu       ', 1),
(24, 9, 'cinta tak pernah salah dan cinta tak pernah menyakiti HASHTAG JUDULFILM HASHTAG', 1),
(25, 9, 'date semalam tengok JUDULFILM harini baru boleh tengok fast 8', 1),
(26, 9, 'saya menemui rakaman JUDULFILM belle let go yang hebat ini di HASHTAG URL HASHTAG', 1),
(27, 9, 'USERNAME pegi awak boleh tebus frust dgn JUDULFILM tu ', 1),
(28, 9, 'filem muzikal JUDULFILM paling laris tahun ini amp kutip lebih usd1 bilion rm4 4 juta URL', 0),
(29, 9, 'semalam baru sempat nak movie date JUDULFILM emm tak tau la nak komen best ke tak tapi emma lawa la haha', 1),
(30, 9, 'JUDULFILM mengajar kita rupa yg hodoh x mustahil bertemu cinta sejati yg cantik selagi ada istana serta jam dan lilin yg bercakap', 1),
(31, 9, 'JUDULFILM masuk kategori film berpendapatan usd 1 miliar URL HASHTAG URL', 0),
(32, 9, 'JUDULFILM masuk kategori film berpendapatan usd 1 miliar URL HASHTAG HASHTAG URL', 0),
(33, 9, 'JUDULFILM masuk kategori film berpendapatan usd 1 miliar URL URL', 0),
(34, 9, 'cinta tak pernah salah dan cinta tak pernah menyakiti HASHTAG JUDULFILM ', 1),
(35, 9, 'USERNAME kalo masih jm hrusnya promo in film barunya dia dong yg JUDULFILM       ', 0),
(36, 9, 'tak de la best mana JUDULFILM tu   ngantuk je aku tengok   ', 1),
(37, 9, 'benda paling aku tak tahan dalam JUDULFILM ialah beast tu pun nak ada solo singing tk igt yg kartun ada ke tak ', 0),
(39, 9, 'retweeted astro awani USERNAME  HASHTAG HASHTAG hiburan  JUDULFILM berjaya kumpul kutipan  URL', 0),
(40, 9, 'USERNAME hahahaha fail lain  aku boleh je download macam biasa kau cari fail JUDULFILM tu kat faibersmoviechannel ke ', 1),
(41, 9, 'tu lagu aku  kau pergi amik lagu JUDULFILM kau tu URL', 0),
(42, 9, 'pendapatan the boss baby 349m rupiah ngalahin JUDULFILM dan smurf ', 0),
(43, 9, 'aku baru tengok JUDULFILM ni best rupanya lagi detailed daripada versi animasi punya banyak character development ', 0),
(44, 9, 'saya menemukan rekaman keren dari JUDULFILM di HASHTAG URL USERNAME HASHTAG', 0),
(45, 9, 'HASHTAG USERNAME  cinta adalah gt angel pieters  biru gt USERNAME ft USERNAME  JUDULFILM w   URL', 0),
(46, 9, 'saya menemukan rekaman keren dari JUDULFILM di HASHTAG URL HASHTAG', 1),
(47, 9, 'bawak irfan pg tengok JUDULFILM and dia tidur sepanjang movie tu       ', 0),
(48, 9, 'curiga ga sih kalo sebenernya dalang film JUDULFILM itu belle dia tuh sebenernya hermione tau gak', 1),
(50, 9, 'USERNAME blm lagi citer JUDULFILM pun tak tngk lagi hahhahah takde geng nk p tngk smpai jadi malas dah', 1),
(51, 9, 'nyanyi nyanyi     JUDULFILM   URL', 1),
(52, 9, 'suka sangat version ni rasa nak nangis  JUDULFILM cover  nathan hartono amp aisyah aziz URL via USERNAME', 1),
(53, 9, 'USERNAME tak kereta bosan jalan cerita sama baik tengok JUDULFILM', 0),
(54, 9, 'merajuk xdpt tgok JUDULFILM  aeon bandaraya melaka shopping centre in melaka URL', 0),
(55, 9, 'nemenin robby nonton ff8 bengong karena gasuka iya sekarang ngerti posisi robby nonton JUDULFILM tp di dalem dia tidur   ', 1),
(56, 9, ' JUDULFILM berjaya kumpul kutipan sebanyak as 1 bilion URL URL', 1),
(58, 4, 'sinopsis film JUDULFILM 2017   the fate of the furious  tetap tegang agedan lucu seimbang dan yang jela  URL', 0),
(59, 4, 'HASHTAG taktau lagu mana nak habaq dia punya best tuuu           ', 1),
(61, 4, 'JUDULFILM     sunway velocity in kuala lumpur wp kuala lumpur URL', 0),
(62, 4, 'kelajuan membunuh       sel sel rambut  HASHTAG HASHTAG  URL', 0),
(63, 4, 'remind balik movie the italian job dia cantik but now  hmm biasa2  HASHTAG', 1),
(64, 4, 'takleh tengok HASHTAG  dapat tengok fast and furious tokyo drift pun jadi lah   ', 0),
(65, 4, 'HASHTAG hallo brian kamu lucu sekalee            HASHTAG HASHTAG  cgv cinemas jwalk URL', 0),
(66, 4, 'okay dah ready nak tengok JUDULFILM esok semoga customer tak ramai datang sebab sis sakit leher lah nak duduk depan2 sangat huhu', 1),
(67, 4, 'JUDULFILM last night  done  not bad lhaaa  best jgak  skali masuk 12 org  hahaha      ', 0),
(68, 4, 'sedih tgok HASHTAG paul walker xde    pape pun cite ni tak pernah tak best terbaikk', 0),
(69, 4, 'JUDULFILM padu gila  ditambah lagi dengan baby comel tu   ', 0),
(70, 4, 'semua benda sumbat satu cerita entertaining but just ok for me HASHTAG', 0),
(71, 4, 'USERNAME oi jangan ngerepotin ayana btw JUDULFILM nice       kalian nonton gih USERNAME tp kalian sama sama eror lg ntar', 0),
(72, 4, 'USERNAME USERNAME na bs me to HASHTAG dkhne ja rha', 0),
(73, 4, 'harap2 la fast amp furious ni dah abis cite dia sebab dah cukup sempurna dah2 la tu main2 ngan family diorng  HASHTAG', 1),
(74, 4, 'JUDULFILM seru sih enak ikutinnya walau sempet boring ditengah2 HASHTAG', 0),
(75, 4, 'bila rasanya dpt tgok HASHTAG dgn HASHTAG USERNAME  ', 1),
(76, 4, 'karena jalan saya agak pincang saat itu lalu kami menonton JUDULFILM sungguh sangat bagus kalian juga harus melihatnya ', 0),
(77, 4, 'karena kurang puas mari kita nonton lagi HASHTAG dalam versi 4dx URL', 0),
(78, 4, 'sumpah best  daebakk HASHTAG', 1),
(79, 4, '7 fakta mengejutkan di balik film fast amp furious 8  bookmyshow indonesia blog URL', 1),
(80, 4, 'sumpah best gila HASHTAG       ', 1),
(81, 4, 'sapa yang blom tgk HASHTAG tunggu sampai habis ada end credit setup cipher buat apa ntuk fast amp furious 9', 0),
(82, 4, 'JUDULFILM gempak gila woii', 0),
(83, 4, 'banyak sangat benda nak buat tapi still nak jugak tengok HASHTAG', 0),
(84, 4, 'fast 8 sumpah best teruk tak pernah mengecewakan 9 5 10 HASHTAG', 0),
(85, 4, 'JUDULFILM dari starting smpai habis menipu bnyk      toh', 1),
(86, 4, 'tolong la jangan upload HASHTAG lagi serius aku jeles   ', 0),
(87, 4, 'patut la aku takut bowok laju skarang           JUDULFILM URL', 1),
(88, 4, 'HASHTAG HASHTAG HASHTAG tak plan pun nak gi tgk movie  ada la orang tu call ckp dah beli tickets  s  URL', 1),
(89, 4, 'memang berbaloi lah habiskan duit tengok HASHTAG i kasi 10 bintang    ', 0),
(90, 4, 'dah macam cs go pulak scene tembak tembak dalam kapal terbang HASHTAG', 1),
(91, 4, 'lex luuuu esok baru kerja  ini malam kasi layan HASHTAG HASHTAG HASHTAG  URL', 1),
(92, 4, 'JUDULFILM film seru dan mengejutkan ini catatan kecilnya HASHTAG HASHTAG URL URL', 1),
(93, 4, 'f8 sumpah keren HASHTAG HASHTAG', 0),
(94, 4, 'fast 8 memang best gila   10000000000  10   ai bg  hihihi  HASHTAG HASHTAG HASHTAG HASHTAG', 0),
(95, 4, 'film HASHTAG sangat menghibur kapan lagicoba liat film mobil2 keren diancurin gituh ', 1),
(96, 4, 'mbah USERNAME titip review film yg penuh kebotakan ya d review JUDULFILM 2017 URL', 0),
(97, 4, 'yawlaaaaa keren banget masih shock        HASHTAG', 0),
(98, 4, 'bentar ya ini gwa baru kelar nonton JUDULFILM kan', 0),
(99, 4, 'layan HASHTAG dolok sementara mnunggu USERNAME ngambik mok nga HASHTAG         ', 0),
(100, 4, 'JUDULFILM  fav movie  with anisa and family at xxi bim bengkulu   URL', 0),
(101, 4, 'tak boleh move on dengan JUDULFILM semalam', 1),
(102, 4, 'akhirnya dapat juga jumpa budak ni dapat habiskan masa menonton JUDULFILM  URL', 1),
(103, 4, 'untuk seri terakhir  ini ok banget HASHTAG', 1),
(104, 4, 'masing2 dah sedap melayan JUDULFILM so tak payah le nak share gambo tiket la video dlm wayang la  org lain dah tengok dulu dah', 1),
(105, 4, '5 misteri JUDULFILM yang akhirnya terjawab di filmnya URL HASHTAG', 1),
(106, 4, 'btw JUDULFILM mayan sih tapi gak bagus bagus amat scene mobil zombie nya keren sisanya ya gitu deh ', 0),
(107, 4, 'wanted to watch JUDULFILM tadi tapi penuh gila kot setiap waktu   ', 1),
(108, 4, 'JUDULFILM bikin penonton kecewa mobil toreto ga ada stiker hello kitty nya masa', 0),
(109, 4, 'ini dia mobil mobil mewah yang digunakan di film JUDULFILM nomor 4 ternyata mobil jadul legendaris   URL', 0),
(110, 4, 'second time   harharhar   HASHTAG HASHTAG             kuala lumpur URL', 0),
(111, 4, 'sumpah keren bangetttt ni film       HASHTAG HASHTAG URL', 0),
(112, 4, 'aing udh nonton JUDULFILM tapi ga di foto dong tiketnyaaa  pencapaian besar milenial saat ini ', 1),
(113, 4, 'siap ajak tengok JUDULFILM pulak tu   ', 0),
(114, 4, 'nonton JUDULFILM jangan takut g paham hanya karena blm ntn seri sebelumnya URL USERNAME HASHTAG', 0),
(115, 4, 'takyah nak tengok wayang JUDULFILM pun dah keluar', 1),
(116, 4, 'tuh  ada yg jual tiket nonton JUDULFILM gara2 ditolak gebetan dan ga jadi nonton  ada yg   pic   URL', 0),
(117, 4, 'demi apa yak JUDULFILM 3d imax nya kereeeeeennn gilak sumpah     om botak nya bikin  URL', 1),
(118, 4, 'terkezut aku bila dia kata rock muka kecik mulut celupar haha HASHTAG', 0),
(119, 4, 'film keren JUDULFILM USERNAME  japanan gempol pasuruan URL', 0),
(120, 4, 'settle HASHTAG      cerita memang power tapi jangan sesekali persoalkan logik dalam  URL', 0),
(121, 4, 'bsk pengennyo nnton JUDULFILM di bioskop tp itu lah mtr dipake kan dk bs pegi', 0),
(122, 4, 'JUDULFILM yang entah kenapa ga ada di pilihan film yang ada di path    w ferdian at USERNAME   URL', 0),
(123, 4, 'sebenarnya aku tak puas tengok JUDULFILM hari tu in need of another round', 0),
(124, 4, 'biar kekinian kaya orang orang     HASHTAG  xxi theatre mall ratu indah URL', 1),
(125, 4, 'baru dpt chuutii la mat    HASHTAG  aeon mall ipoh klebang URL', 1),
(126, 4, 'watching JUDULFILM td siuk cetanya da tdur sikit sj finally dpt liat cinema alone   i feel awesome HASHTAG', 1),
(127, 4, 'vin diesel murka gara gara ending tambahan JUDULFILM URL URL', 1),
(128, 4, 'masuk wayang JUDULFILM pun still kena tunjuk ic hehe terbaik ahhh movie tuh', 0),
(129, 4, 'vin diesel murka gara gara ending tambahan JUDULFILM URL', 1),
(130, 4, 'fast and furious  8  golden screen cinemas gsc  USERNAME in kuala lumpur wp kuala lumpur URL', 0),
(131, 4, 'HASHTAG hari ni baru nk tngk   ', 0),
(132, 4, 'ternyata adegan zombie cars dan 3rd act di HASHTAG mostly nggak pake cgi pantes bisa sedahsyat itu       URL', 0),
(133, 4, '     JUDULFILM akhirnya dapat juga      with syafa at USERNAME   URL', 1),
(134, 4, 'belom move on sm film JUDULFILM   sayang banget ga ada paul walker lagi  disini ga ada mia and brian    ', 1),
(135, 4, 'fakta seru dibalik film JUDULFILM URL via USERNAME', 1),
(136, 4, 'JUDULFILM USERNAME  tgv cinemas in kuala lumpur URL', 0),
(137, 4, 'USERNAME HASHTAG HASHTAG info toyota murah angsuran hanya 1 jutaan  syarat amp ketentuanberlaku URL', 1),
(138, 4, 'yeahhh second time for HASHTAG  ade org belanjakan why not sebenarnya dia kene paksa belanja kak ngah hahahaha ', 1),
(139, 4, 'thorbaekkkk JUDULFILM   100 100                ternganga dr start smpai habis gempak habess ', 0),
(140, 4, 'JUDULFILM memang super duper gempak tapi tak lengkap tanpa brian o conner         ', 0),
(141, 4, 'tak pernah mengecewakan   HASHTAG HASHTAG URL', 1),
(142, 4, 'fakta seru dibalik film JUDULFILM URL', 1),
(143, 2, 'melobi itu melihat kedepan memperkirakan pergerakan lawan dan mengatasinnya  langkah seorang    JUDULFILM   URL', 0),
(144, 2, 'USERNAME sapu bersih untunglah masih ada JUDULFILM dan get out', 0),
(145, 2, 'gak tau knp gua ttp aja nganggep jessica chastain ini seorang agen cia yg newbie di film zero    JUDULFILM   URL', 1),
(146, 2, 'JUDULFILM kesian bgt minggu lalu tiba2 tayang di teater yg sangat terbatas hari ini udah tinggal satu studio aja ', 0),
(147, 2, 'maksud eug yang bagus itu JUDULFILM  yaa', 1),
(148, 2, 'paslon 2 kalo mau menang butuh earthquake kayak di JUDULFILM sih ', 0),
(149, 2, 'film JUDULFILM 2016 sebut indonesia dengan negatifnya mengakali pajak duhhh', 1),
(150, 2, 'sang dominant HASHTAG membuat kita menjadi submissive review         URL URL', 0),
(151, 2, 'kesel bgt padahal mau ngerasain gimana nonton JUDULFILM di bioskop eh udah kegeser sama film dari franchise overhyped itu', 0),
(152, 2, 'yg cakep dr HASHTAG selain JUDULFILMnya ya cara membangun konflik dan emosi penonton mau spoiler tp tar gue disambit netizen hhhhh ', 1),
(153, 2, 'gue narik napas aja tuh rasanya dialognya dah bahas hal yg berbeda apa mungkin gue aja yang lemot ya HASHTAG', 1),
(154, 2, 'tapi ga boong ya lima belas menit pertama HASHTAG gue bengong soalnya gue kurang baca john grisham dan percakapannya tuh cepet banget ', 1),
(155, 2, 'film terbaiknya jessica chastain ya sejauh ini yg gue tonton ya HASHTAG gugu mbatha raw juga gue suka disini ', 1),
(156, 2, 'jessica chastain pas banget memerankan madeline elizabeth sloane yang cantik pinter seksi ambisius dan gila hahahaha HASHTAG', 1),
(157, 2, 'resek ya gue kayak mendahului takdir filmnya HASHTAG pakek segala bilang udah tau filmnya bakal bagus hhhhhhh ', 1),
(158, 2, 'habis gimana emang HASHTAG sebagus itu sih padahal gue udah mewanti wanti supaya ngga terlalu kaget krn ceritanya pasti bagus ya ', 0),
(159, 2, 'sejak HASHTAG malem ini HASHTAG punya saingan  salim ', 0),
(160, 2, 'JUDULFILM laku lobi yang tak kenal kompromi URL URL', 1),
(161, 2, 'ini kenapa film JUDULFILM ini banyak sebut indonesia  ', 1),
(162, 2, 'ini keren sih   JUDULFILM   URL', 0),
(163, 2, 'performa jessica chastain di JUDULFILM dahsyat langsung ngefans ini film bagus yg sepertinya akan lewat begitu saja dihempas furious 8 ', 1),
(164, 2, 'JUDULFILM menceritakan tentang seorang wanita dengan kemampuan dalam berkarir yang tidak bisa dipandang  URL', 1),
(165, 2, 'sok sokan nonton film politik         JUDULFILM with faustina and robin at xxi summarecon mall bekasi   URL', 1),
(166, 2, 'sahabatku baru nonton JUDULFILM katanya liz sloane kayak aku  hmmmm gatau harus berkomentar apa', 0),
(167, 2, 'mbah USERNAME mbak jessica chastain keren banget di film JUDULFILM   ', 0),
(168, 2, 'baru saja selesai menonton film ini berawal dari rekomendasi teman salah satu film penutup di    JUDULFILM   URL', 1),
(169, 2, 'USERNAME min  the boss baby sama JUDULFILM besok tayang gak d cgv rita mall purwokerto  mksh', 1),
(170, 2, 'seriusan deh kalian ke bioskop tonton tuh JUDULFILM  fear mongering itu salah satu trik terlama di politik dan iklan tadi itu contohnya ', 1),
(171, 2, 'film JUDULFILM sudah tayang di bioskop cek sinopsis dan trailernya dibawah ini   URL', 0),
(172, 2, '95 JUDULFILM 2016  gila kayak runnaway jury campur ides of march klo sloane jd bos gw pasti gw stress berat 8 5 10 ', 0),
(173, 2, 'akhir2 ini banyak film yg ada indonesia di dialognya lll JUDULFILM get out gold ada lagi ', 1),
(174, 2, 'JUDULFILM keren banget  coba praktek lobbying dan campaign finances di indonesia seterbuka itu pasti dunia politik kita jd lebih seru ', 1),
(175, 2, 'gw sangat menikmati film seperti gw menikmati serial house of cards dan newsroom  acting     JUDULFILM   URL', 0),
(176, 2, ' winning is a drug  jessica chastain dengan elegannya mempersembahkan karakter    JUDULFILM at USERNAME   URL', 0),
(177, 2, 'pemimpin itu melihat kedepan memperkirakan pergerakan lawan dan mengatasinya langkah seorang    JUDULFILM   URL', 0),
(178, 2, 'indonesia p nama banyak x tacumu   HASHTAG', 1),
(179, 2, 'JUDULFILM baguusssss    selera pribadi sih emang demen yg film2 pengadilan gitu kl beda pendapat maapin yak tapi bagus kok ciyusss', 1),
(180, 2, 'iya HASHTAG baru tayang mulai hari ini di USERNAME sempat tayang midnight 11 maret 2017 yang lalu URL', 0),
(181, 2, 'steven spielberg mengaku suka skenarionya dan sempat dikabarkan akan menyutradarai HASHTAG sebelum diputuskan john madden HASHTAG', 1),
(182, 2, 'HASHTAG pelobi politik handal elizabeth menghadapi dilema terhadap uji coba uu kepemilikan senjata api yang akan menguntungkan rivalnya ', 1),
(183, 98, 'iseng nonton y bginian  mau nonton ff8 lg males  nungguin donlotan y aja buat    JUDULFILM   URL', 0),
(184, 98, 'bawak anak menakan mai tgok wayang bg depa happy   watching JUDULFILM in 3d at tgv the mines URL', 1),
(185, 98, 'obatnya dengerin lagu ini           i  m a lady from JUDULFILM by meghan trainor   URL', 0),
(186, 98, 'pesan moralnya  kalau kita baik maka kebaikan juga yg akan datang     JUDULFILM   URL', 1),
(187, 98, 'mending fast and furious 8 or danur or JUDULFILM pls this week banyak film seru tp cuma bisa besok perginya  reply asap pls', 1),
(188, 98, 'sayang nangis sbb smurfette mati     nasib hidup balik happy dia   HASHTAG', 1),
(189, 98, 'film rekomendasi smart mama smurf the lost village URL URL', 1),
(190, 98, 'anyone ade tak yang dah tengok movie JUDULFILM  best tak  ', 1),
(191, 98, 'HASHTAG tak mampu saingi HASHTAG rekap box office  URL URL', 1),
(192, 98, 'HASHTAG with USERNAME dapatkan hadiah toy box berikut ini URL URL', 0),
(193, 98, 'mcm budok2 je tapi gok hilang stress      HASHTAG URL', 0),
(194, 98, 'smart viewers ini dia HASHTAG USERNAME yang sangat terinspirasi dari HASHTAG USERNAME  URL', 1),
(195, 98, 'fiyashhh tengah kemaruk HASHTAG HASHTAG HASHTAG   mommy pula mengimbau zaman kanak kanak  URL', 1),
(196, 98, 'fiyashhh tengah kemaruk HASHTAG HASHTAG HASHTAG   mommy pula mengimbau  URL', 0),
(197, 98, 'movie time kami tengok JUDULFILM ramai ramai memang best wey salah satu aktiviti yang saya  URL', 1),
(198, 98, 'terinspirasi oleh film animasi terbaru JUDULFILM sony pictures pun meluncurkan game puzzle  URL', 0),
(199, 98, 'aku mah apa nntn sendiri mulu hahahah HASHTAG   JUDULFILM   URL', 0),
(200, 98, 'HASHTAG petualangan yang cukup asyik terutama di bagian tengah sampai akhir score pun asyik tidak lupa akan pesan moral 3 5', 1),
(201, 98, 'sebiru warna smurfs      watching JUDULFILM at lotus five star cinema mahkota parade melaka URL', 1),
(202, 98, 'kenapa dalam HASHTAG dorang tak ada nyanyi lagu happy song   btw i rate 7 10 for smurfs  ', 0),
(203, 98, 'ditonton 2x tidak membosankan JUDULFILM ini film yang menyenangkan   HASHTAG HASHTAG', 0),
(204, 98, 'dah org belanja  movie cartoon pun best HASHTAG URL', 1),
(205, 98, 'nemenin si cantik nonton manusia jamur   JUDULFILM with aditta and kinan at USERNAME   URL', 0),
(206, 98, 'jadi pas film udah mulai suasana udah gelap trus tetiba bunyi    JUDULFILM w ristantyo   URL', 1),
(207, 98, 'sbnrnya mo bawa dg li mar krn msh tllu kecil jd mamipapi jo    JUDULFILM w wendy at USERNAME   URL', 1),
(208, 98, 'bonding with keisha  treat ko sa kanya kasi with honors sya    JUDULFILM at sm cinema cauayan   URL', 1),
(209, 98, 'seronok tengok JUDULFILM sebab ramai budak  berlari dalam cinema   ', 1),
(210, 98, 'JUDULFILM lebih mencuit hati URL URL', 1),
(211, 98, 'bosen belajar tasya   JUDULFILM with tasyanabilah   URL', 1),
(212, 98, 'pas masuk bioskop bingung kok kosong ga taunya isinya balita semua    JUDULFILM at USERNAME   URL', 1),
(213, 98, 'beruntung pny ade yg hobi nntn smurf     JUDULFILM with doli at USERNAME   URL', 1),
(214, 98, 'HASHTAG hari ini ada movie of the week yang ngebahas film  JUDULFILM dan get out HASHTAG URL', 0),
(215, 98, 'game smurfs bubble story tersedia di ios dan android terinspirasi film animasi  JUDULFILM   sony  URL', 1),
(216, 98, 'kembalinya makhluk biru dalam mengungkap misteri di  JUDULFILM    movie review  flagig URL via USERNAME', 1),
(217, 98, 'u komik paling keren deh  URL HASHTAG HASHTAG HASHTAG HASHTAG HASHTAG  URL', 0),
(218, 98, 'suka banget dengan smurfette yg jadi center of this film amp membuktikan kalau perempuan jg punya peranan penting    HASHTAG', 0),
(219, 3, 'sebelum libur berakhir       JUDULFILM with juwita at USERNAME   URL', 1),
(220, 3, 'JUDULFILM 100 kali ganda lbh best drpd ghost in the shell   ', 1),
(221, 3, 'kayak kal el dan moazzam  penuh imajinasi di pikiran anak anak perebutan kasih sayang bahkan    JUDULFILM   URL', 1),
(222, 3, 'nonton bayi gila JUDULFILM  d  st moritz xxi  USERNAME in jakarta barat dki jakarta URL', 0),
(223, 3, 'USERNAME min   hari selasa JUDULFILM masih kan ', 0),
(224, 3, 'ada aja yak bayi kaya gini     JUDULFILM   URL', 0),
(225, 3, 'ada yang sudah nonton JUDULFILM yuk bagikan opinimu dalam HASHTAG ', 1),
(226, 3, 'bulan april kok pada byk film yg bagus yaa  fast amp furious 8 stip amp pensil JUDULFILM smurf ku ingin nonton iniii       ', 0),
(227, 3, 'JUDULFILM best gila tak rugi pi tengok haha', 1),
(228, 3, 'selamat paskah dari keluarga HASHTAG sedang tayang di bioskop URL', 0),
(229, 3, 'JUDULFILM terbaik ', 1),
(230, 3, 'gagal nonton ff8 sama JUDULFILM jadi danur aja at USERNAME  pic   URL', 0),
(231, 3, 'ih pengen banget nonton JUDULFILM t  t ', 0),
(232, 3, 'org org nonton ff8 gua mah JUDULFILM ', 0),
(233, 3, 'HASHTAG cerita sederhana tapi begitu menghibur dengan bayi bayi yang begitu menggemaskan dan menggelitik nurani ending yang bagus 4 5', 1),
(234, 3, 'tadi ngeliat ci USERNAME lagi nonton JUDULFILM di emporium  pake dress hitam putih  tapi gaberani sapa  ', 0),
(235, 3, 'review film JUDULFILM kombinasi lucu dan mengharukan URL via USERNAME', 1),
(236, 3, 'pada bawa anak kita bawa ayu       JUDULFILM with wahyu and ayudia at USERNAME   URL', 1),
(237, 3, 'bergema ketawa tgk movie td sebab telampau lucu HASHTAG', 1),
(238, 3, 'tak heran kau merindukannya krn mungkin secara tidak sengaja kau pernah memilikinya       HASHTAG pic   URL', 0),
(239, 3, 'hari tenang    JUDULFILM   URL', 1),
(240, 3, 'memilih untuk nonton JUDULFILM seorang diri ketika kegabutan melanda baru kali ini nonton sebioskop isinya anak kecil semua  takut  ', 1),
(241, 3, 'JUDULFILM       mbo cinemas in setapak kuala lumpur URL', 0),
(242, 3, 'tiba  tiba harini pengen nonton JUDULFILM lagi bareng krucil2    bareng krucil dunia jadi penuh tawa tanpa beban ', 0),
(243, 3, 'nonton pakai 100 poin junior dapat 1 tiket nonton film JUDULFILM berlaku setiap minggu mau   URL', 0),
(244, 3, 'upload di facebook instagram kamu jgn lupa tag cgv ada t shirt notebook amp puzzle JUDULFILM utk kamu yg beru  URL', 0),
(245, 3, 'HASHTAG ini bener2 ungkapan hati seorang anak tunggal yg tiba2 punya adek  lucu bangeet HASHTAG', 0),
(246, 3, 'tak boleh tidur  tengok ini baby saja     JUDULFILM   URL', 0),
(247, 3, 'JUDULFILM sayang kamu kok ga konsen si URL', 1),
(248, 3, 'USERNAME bila la nk tengok ni pun baru sampai bilik        tapi aku nk tgk JUDULFILM        ', 1),
(249, 3, 'USERNAME nnti tonton JUDULFILM dah w ngakak lepas bgt dr awal sampe akhir si parah  tp blm hd huft', 1),
(250, 3, 'ff8 membuat JUDULFILM menghilang berarti gak jd solo player wkwk', 0),
(251, 3, '           menonton JUDULFILM di xx1 panakukang URL', 0),
(252, 3, 'serasa nonton kisah diri sendiri     JUDULFILM at USERNAME   URL', 1),
(253, 3, 'JUDULFILM filmnya keren abis', 0),
(254, 3, 'perhatikan yg perlu perlu saja      HASHTAG HASHTAG HASHTAG        URL', 1),
(255, 3, 'haahaaa  imajinasi yg luar biasa  kepikiran aja bwt film ini         JUDULFILM   URL', 0),
(256, 3, 'tumben nonton film kartun kartun bayi pula bah     JUDULFILM   URL', 0),
(257, 3, 'kaloo udah nonton sama keluarga wajib bgt tontonannya kartun kemaren rencana nonton JUDULFILM e belum keluar ya gajadi nontonnya', 0),
(258, 55, 'JUDULFILM bukan hanya sibuk mencari tapi ingatlah memperbaiki diri  karena pernikahan itu sakral   pic   URL', 1),
(259, 55, 'mengganggu orang pacaran HASHTAG URL', 1),
(260, 55, 'saya suka video USERNAME URL film JUDULFILM  kopdar meet and greet dan nobar bersama kfmm tangerang', 0),
(261, 55, 'kembali HASHTAG lagi kali ini with lee USERNAME          URL', 0),
(262, 55, 'kasian sih film JUDULFILM rilis pas para film film besar rilis juga untung masih ada satu bioskop di sini yg nayangin ', 1),
(263, 55, 'pen ke bioskop nonton JUDULFILM maak   pen nyelesein nonton bicara rasa amp ramadhan cantik juga tapi kuota apa daya  ', 0),
(264, 55, 'rekomendasi selanjutnya adalah produksi dalam negeri insan muda yang berjudul JUDULFILM    URL', 1),
(265, 55, 'USERNAME JUDULFILM belum ada ya min ', 0),
(266, 55, '3 film nasional bertahan di bioskop the guys amp danur meski berkurang msh banyak layarnya JUDULFILM di jkt tinggal di blokm square', 0),
(267, 55, 'oiya ampe lupa kak USERNAME dan cing USERNAME sudah nonton film HASHTAG USERNAME  insyaa allah rekomended   ', 0),
(268, 55, 'nyok nonton film HASHTAG by USERNAME  komplit dah jalan ceritanya koplak tp gk lebay  mksi bunda USERNAME yg udah info in   ', 1),
(269, 55, 'setelah nonton film JUDULFILM silaturahim dengan cast cantik USERNAME      ketemu  URL', 0),
(270, 55, 'kemaren lupa nulis kalo bid ah cinta jg termasuk film religi saya saya tonton selain aac amp JUDULFILM      ', 0),
(271, 55, 'yok nonton film JUDULFILM sama saya        ', 0),
(272, 55, 'siap di kejar  HASHTAG HASHTAG  mega bekasi xxi URL', 0),
(273, 55, 'saya suka video USERNAME URL behind the scene JUDULFILM  13 april 2017 di bioskop', 0),
(274, 55, 'apa kabar HASHTAG hari pertama 60 430 hr kedua 71 915 total 132 345 pnton walau dg layar limited HASHTAG meraih 3 290 pnton 2 hr', 0),
(275, 55, 'serius nanya  film JUDULFILM itu bercerita tentang mui yah    ', 1),
(276, 55, 'seberapa pantas  JUDULFILM   URL URL', 1),
(277, 55, 'seberapa pantas  JUDULFILM   URL HASHTAG URL', 1),
(278, 55, 'regrann from USERNAME    bismilah perjuangan belum berakhir  JUDULFILM sudah  URL', 1),
(279, 55, 'banyak yang ngajak nonton the guys JUDULFILM tapi gapunya duit haha kalu dibayarin siii gapapa langsung mandi dandan mangkat dah      ', 0),
(280, 55, 'walau dg layar limited terbukti HASHTAG mampu meraih 3 290 pnonton dlm 2 hari selamat ', 1),
(281, 55, 'yeay full seat di studio 3 yang belum nonton JUDULFILM karya film maker muslim yuk di bioskop   pic   URL', 1),
(282, 55, 'saat yang lain tertarik nonton ff8 dan entah kenapa lebih tertarik sama JUDULFILM   ', 0),
(283, 55, 'okaay pokoknya jangan lupa nonton HASHTAG dan dukung terus HASHTAG', 1),
(284, 55, 'untuk perempuan yg belum menikah ini saatnya menggali hobby dan passion kita lebih dalam HASHTAG', 1),
(285, 55, 'film HASHTAG mengingatkan kita untuk selalu bersyukur disetiap keadaan ', 1),
(286, 55, 'film HASHTAG keren ga bikin baper bikin kita instropeksi diri terutama buat akhwat yg belum pada menikah hehhe', 1),
(287, 55, 'assalamualaikum  hi twips buat yang mau nonton recommended bgt nih film HASHTAG jgn lupa nonton ya dan dukung sll HASHTAG ', 0),
(288, 55, 'sementara itu JUDULFILM nambah 1 jadwal pertunjukan di blok m square jadi total ada 6 jadwal pertunjukan ', 1),
(289, 55, 'cinta itu cahaya sanubari kurniaan tuhan fitrah insani dan di mana terciptalah cintadi situ rindu bermula HASHTAG', 0),
(290, 55, 'btw pas nonton JUDULFILM kemaren kesel banget lah diapit sama 2 mbak2 yg tiap 10 menit buka hape balas whatsapp hhhh', 0),
(291, 55, 'thanksss for today   akhir nyaaa bisa liat JUDULFILM jugaa film yang ditunggu sejak  URL', 1),
(292, 55, 'film ini mengajrkan jangan risau akan jodoh toh semua sdh diatur mri sama sama sbuk memantaskan diri HASHTAG', 1),
(293, 55, 'saya suka video USERNAME URL JUDULFILM tayang hari ini ', 0),
(294, 55, 'pengen nonton film JUDULFILM di padang ada gak ya cc uni USERNAME     ', 0),
(295, 55, 'USERNAME soalnya saya sendiri jarang nonton film religi indo kayanya cuman aac sama JUDULFILM ini sih', 0),
(296, 55, 'komunitas film maker muslim adakan nobar  JUDULFILM  URL', 1),
(297, 55, 'HASHTAG terlalu singkat amp kosong bisa menghibur andai menekankan di komedi absurd amp punya protagonis simpatik 1 5 5 USERNAME', 0),
(298, 55, 'USERNAME ora bro  sedih nemen padahal pengen nntn JUDULFILM biar cepet dihalalin eeh haha', 0),
(299, 55, 'film JUDULFILM sudah bisa disaksikan di bioskop indonesia  famous id URL', 1),
(300, 55, 'saya suka video USERNAME URL trailer JUDULFILM  13 april di bioskop', 0),
(301, 55, 'jarang2 foto sama sutradara dan tokoh utama JUDULFILM    HASHTAG atberkah d pic   URL', 1),
(302, 55, 'JUDULFILM 2   setelah kejadian beberapa waktu yang lalu haura sadar banyak hal positif  URL', 0),
(303, 55, 'HASHTAG HASHTAG komunitas film maker muslim adakan nobar  JUDULFILM  URL URL', 1),
(304, 55, 'komunitas film maker muslim adakan nobar  JUDULFILM  URL URL', 1),
(305, 55, 'JUDULFILM jauh lebih laku daripada hitam dan putih sepertinya ya cuma 1 bioskop di jakarta masih bertahan 5 pertunjukan film ', 1),
(306, 55, 'untung belum ke bdg niatnya pngn nntn HASHTAG dan sekaligus nntn bigmatch  tapi tak apa lah yah persib masih bsa lain waktu  ', 1),
(307, 55, 'mengejarhalal siap dikejar      HASHTAG  URL', 1),
(308, 55, 'JUDULFILM review  alhamdulillah teman teman dari film maker muslim atau daqu movie telah berhasil  URL', 1),
(309, 55, 'i liked a USERNAME video URL sabar by muezza  ost film JUDULFILM 13 april 2017 di bioskop ', 0),
(310, 55, 'alhamdulillah akhirnya bisa nonton HASHTAG juga      satu kata buat film nya  keren      URL', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `email` varchar(256) NOT NULL COMMENT 'cannot be changed',
  `password` varchar(256) NOT NULL,
  `name` varchar(256) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `picture` varchar(256) DEFAULT './pictures/default.png',
  `role` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 for user, 0 for admin'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`email`, `password`, `name`, `birthdate`, `picture`, `role`) VALUES
('admin@abcmovies.co.id', '21232f297a57a5a743894a0e4a801fc3', 'Admin of ABCMovies', '1995-12-25', './pictures/1488602184adm.png', 0),
('meloniaseven@gmail.com', '3e4063d6f38d738b1e6608a2c1f119d8', 'Melania Laniwati', '1995-02-07', './pictures/1488602110mel.jpg', 1),
('scooby@gmail.com', '38371fef7d829c7f8b0e2fedf7a04334', 'Scooby Doo', '1992-01-03', './pictures/1488602156sco.jpg', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `film`
--
ALTER TABLE `film`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tweets`
--
ALTER TABLE `tweets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banner`
--
ALTER TABLE `banner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `film`
--
ALTER TABLE `film`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;
--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `tweets`
--
ALTER TABLE `tweets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=311;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

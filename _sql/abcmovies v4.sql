-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 13, 2017 at 01:38 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.5.37

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `abcmovies`
--

-- --------------------------------------------------------

--
-- Table structure for table `banner`
--

CREATE TABLE `banner` (
  `id` int(11) NOT NULL,
  `name` varchar(125) NOT NULL,
  `picture` varchar(256) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `banner`
--

INSERT INTO `banner` (`id`, `name`, `picture`, `status`) VALUES
(5, 'kong skull island', './pictures/banner/kong-banner-2.jpg', 1),
(6, 'galih dan ratna', './pictures/banner/galihratna_s.png', 1),
(7, 'beauty and the beast', './pictures/banner/new_beauty_and_the_beast__2017__banner_poster_by_artlover67-davww9h.jpg', 1),
(9, 'logan', './pictures/banner/wolverine-last-time-banner.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `film`
--

CREATE TABLE `film` (
  `id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `summary` text NOT NULL,
  `genre` varchar(256) DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `playing_date` date DEFAULT NULL,
  `length` smallint(3) DEFAULT NULL,
  `director` varchar(125) DEFAULT NULL,
  `writer` text,
  `actors` text,
  `poster` varchar(256) DEFAULT NULL,
  `trailer` text,
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
(1, 'GHOST IN THE SHELL', 'Motoko Kusanagi (Scarlett Johansson) adalah seorang agen cyborg yang hidup di era tahun 2029 dimana robot-robot dengan kecerdasan buatan hidup berdampingan dengan manusia. \r\n\r\nKusanagi dan sejumlah anggota tim keamanan, Public Security Section 9 memiliki kewajiban untuk menghentikan kejahatan yang dilakukan oleh hacker terkenal, the Puppet Master dan mengantisipasi pergerakan dari penjahat misterius yang ingin menghancurkan perkembangan teknologi dari Hanka Robotic.', 'Action, Drama, Sci-fi', 2017, '2017-03-29', 120, 'Rupert Sanders', 'William Wheeler', 'Scarlett Johansson, Michael Pitt, Juliette Binoche', 'http://www.21cineplex.com/data/gallery/pictures/148912889080786_300x430.jpg', '<iframe width="854" height="480" src="https://www.youtube.com/embed/G4VmJcZR0Yg" frameborder="0" allowfullscreen></iframe>', 'tt0113568', 8, 0, 0, 0, 0, 0),
(2, 'MISS SLOANE', 'Elizabeth Sloane (Jessica Chastain) adalah pelobi politik yang handal dan terkenal di Washington D.C. Strategi politiknya selalu berhasil mengalahkan lawan-lawannya.\r\n\r\nNamun sebuah kasus akan membawanya pada suatu dilema. Ia berada di posisi sulit saat sebuah Undang-undang kepemilikan senjata api sedang diuji. Elizabeth menjadi salah satu pelobi di pihak yang tengah berseteru akan Undang-undang tersebut.', 'Drama, Thriller', 2016, '2016-12-09', 132, 'John Madden', 'Jonathan Perera', 'Jessica Chastain, Gugu Mbatha-Raw, Michael Stuhlbarg, John Lithgow', 'https://images-na.ssl-images-amazon.com/images/M/MV5BMTAyODY4Njc4MjBeQTJeQWpwZ15BbWU4MDI0NTIzMDAy._V1_SX300.jpg', '<iframe width="854" height="480" src="https://www.youtube.com/embed/AMUkfmUu44k" frameborder="0" allowfullscreen></iframe>', 'tt4540710', 6.6, 63, 0, 0, 0, 0),
(3, 'THE BOSS BABY', 'Berkisah tentang kehidupan seorang bayi yang tampak berbeda dari bayi pada umumnya. Ia nakal, keras kepala, selalu mengenakan jas, membawa koper dan kopi adalah minuman kesukaanya. \r\n\r\nDi balik penampilannya, Boss Baby adalah utusan dari Baby Corp. Perusahaan yang bertugas untuk menyelidiki dan menghentikan bisnis dari Puppy Co., sebuah organisasi yang berniat untuk melemahkan bisnis makanan bayi demi keuntungan bisnis makanan hewan.', 'Animation, Comedy, Family', 2017, '2017-03-31', 0, 'Tom McGrath', 'Marla Frazee (book), Michael McCullers (screenplay)', 'Miles Christopher Bakshi, Alec Baldwin, Eric Bell Jr., Steve Buscemi', 'http://www.21cineplex.com/data/gallery/pictures/148939461451821_300x430.jpg', '<iframe width="854" height="480" src="https://www.youtube.com/embed/tquIfapGVqs" frameborder="0" allowfullscreen></iframe>', 'tt3874544', 4.7, 0, 0, 0, 0, 0),
(4, 'FAST & FURIOUS 8', 'Dalam seri ke delapan dari franchise Fast and Furious, Dom (Vin Diesel) akan mengkhianati teman-temannya dan bekerjasama dengan teroris bernama Chiper (Charlize Theron).\r\n\r\nTak lama setelah itu, tim yang tersisa direkrut oleh pasukan agen rahasia pemerintah pimpinan Frank Petty (Kurt Russel). Tugas mereka adalah menghentikan aksi teror yang direncanakan oleh Dom dan Chiper.', 'Action, Crime, Thriller', 2017, '2017-05-24', 130, 'F. Gary Gray ', 'Chris Morgan, Gary Scott Thompson (characters)', 'Vin Diesel, Dwayne Johnson, Jason Statham', 'http://www.21cineplex.com/data/gallery/pictures/148905122668489_452x647.jpg', '<iframe width="854" height="480" src="https://www.youtube.com/embed/19uRZ0vVVbA" frameborder="0" allowfullscreen></iframe>', 'tt1905041', 7.1, 61, 0, 0, 0, 0),
(5, 'LIFE', 'Enam orang astronot dipilih untuk ditempatkan di stasiun luar angkasa internasional. Pada awalnya mereka bersemangat ketika sebuah robot penjelajah menemukan menemukan bukti pertama yang menandakan kehidupan di Mars. Para astronot yang terbuai oleh kesuksesan mereka tidak menyadari jika kehidupan yang mereka temukan merupakan ancaman bagi umat manusia.', 'Horror, Sci-fi, Thriller', 2017, '2017-02-05', 108, 'Daniel Espinosa', 'Robert Ramsey, Matthew Stone', 'Jake Gyllenhaal, Rebecca Ferguson, Ryan Reynolds', 'http://www.21cineplex.com/data/gallery/pictures/148879301615406_300x430.jpg', '<iframe width="854" height="480" src="https://www.youtube.com/embed/cuA-xqBw4jE" frameborder="0" allowfullscreen></iframe>', 'tt0123964', 6.7, 63, 0, 0, 0, 0),
(6, 'GOLD', 'Kenny Wells (Matthew McConaughey) adalah seorang pebisnis yang mengalami kesulitan finansial. Kenny berusaha merubah peruntungannya dengan mencari tambang emas di hutan yang belum di petakan di wilayah Kalimantan, Indonesia.\r\n\r\nBersama temannya Michael Acosta (Edgar Ramirez) ahli geologi, Kenny menelusuri belantara hutan demi impiannya.', 'Adventure, Drama, Thriller', 2017, '2017-01-27', 120, 'Stephen Gaghan', 'Patrick Massett, John Zinman', 'Matthew McConaughey, Edgar Ramírez, Bryce Dallas Howard, Corey Stoll', 'https://images-na.ssl-images-amazon.com/images/M/MV5BNjEwNzMzMDI4Nl5BMl5BanBnXkFtZTgwMTM2ODkwMTI@._V1_SX300.jpg', '<iframe width="854" height="480" src="https://www.youtube.com/embed/IhYROWOayLw" frameborder="0" allowfullscreen></iframe>', 'tt1800302', 6.6, 49, 0, 0, 0, 0),
(7, 'PERFECT DREAM', 'Bagi DIBYO keberhasilan diukur dari seberapa besar ia mampu memenuhi ambisi hidupnya. Dari kehidupan jalanan menjalankan bisnis gelap, Dibyo berhasil menikahi Lisa, putri Marcel Himawan, seorang pengusaha besar di kalangan elite Surabaya. Dibyo bahkan berhasil mengembalikan kejayaan bisnis Marcel. \r\n\r\nHarta berlimpah tak membuat Dibyo puas. Ambisi Dibyo adalah menguasai wilayah lawan bisnisnya, Hartono si mafia nomor satu. Pertikaian antar-geng pun tak terelakkan.\r\n\r\nAmbisi Dibyo makin meluap setelah mengenal Rina, pemilik galeri foto yang mampu memberi kehangatan cinta seorang ibu yang tak pernah Dibyo dapatkan selama ini. \r\n\r\nLisa harus memilih, mengikuti ambisi suaminya atau berjuang mempertahankan keutuhan keluarga yang ia cintai!', 'Drama', 2017, '2017-03-20', 0, 'Hestu Saputra', 'Sinung Winahyo, Hestu Saputra', 'Rara Nawangsih, Tissa Biani Azzahra, Wulan Guritno, Olga Lydia', 'http://www.21cineplex.com/data/gallery/pictures/148834342534292_300x430.jpg', '<iframe width="854" height="480" src="https://www.youtube.com/embed/Ocd9naaBprI" frameborder="0" allowfullscreen></iframe>', 'tt6522380', 0, 0, 0, 0, 0, 0),
(8, 'STIP & PENSIL', 'Toni (Ernest Prakasa), Aghi (Ardit Erwandha), Bubu (Tatjana Saphira) dan Saras (Indah Permatasari) adalah anak anak orang kaya yang dimusuhi anak anak di SMU sekolahnya. Karena dibanding yang lain mereka selalu merasa sok jago dan songong. .\r\n\r\nSuatu hari mereka mendapat tugas essay untuk menulis masalah sosial dari Pak Adam (Pandji Pragiwaksono). Alih-alih menulis essay mereka malah sok bikin tindakan yang lebih kongkrit dengan membangun sekolah untuk anak anak orang miskin di kolong jembatan.\r\n\r\nAwalnya mereka menganggap hal itu enteng, tapi ternyata hal itu tidak semudah yang mereka bayangkan. Karena banyak sekali rintangan di sekelilingnya yang menghadang. Mulai dari kepala suku pemulung disana, Pak Toro (Arie Kriting), Si anak kecil yang bengal, Ucok (Iqbal Sinchan) dan Mak Rambe (Gita Bhebhita) emaknya Ucok yang gak setuju anaknya ikut sekolah gratis yang diadakan Toni cs. Belum lagi ledekan teman teman di sekolahnya yang diketuai oleh Edwin (Rangga Azof) yang selalu meremehkan mereka.\r\n\r\nBerhasilkah mereka mewujudkan keinginannya untuk mendirikan sekolah buat anak anak miskin itu. Temukan jawabannya di dalam film Stip dan pensil.', 'Drama', 2017, '2017-04-20', 120, 'Hestu Saputra', 'Joko Anwar', 'Ernest Prakasa, Tatjana Saphira, Indah Permatasari', 'http://www.21cineplex.com/data/gallery/pictures/148833767677226_300x430.jpg', '<iframe width="854" height="480" src="https://www.youtube.com/embed/3pc2NbyjsrA" frameborder="0" allowfullscreen></iframe>', 'tt6522380', 0, 0, 0, 0, 0, 0),
(9, 'BEAUTY AND THE BEAST', 'Ketika Belle (Emma Watson) pergi untuk mencari ayahnya, Maurice (Kevin Kline), ia menemukan jika ayahnya disekap di dalam sebuah kastil tua oleh The Beast (Dan Stevens). Belle kemudian bertukar tempat sebagai tahanan demi membebaskan ayahnya. \r\n \r\nGadis itu terkejut ketika menyadari jika benda-benda di dalam kastil itu hidup dan dapat berbicara. Benda-benda tersebut memberitahu jika watak Beast tidaklah seburuk penampilannya. Ketika ia mulai dekat dengan Beast, para penduduk kota telah melakukan persiapan untuk menolong sang gadis.', 'Animation, Family, Fantasy', 2017, '2017-03-23', 129, 'Bill Condon ', 'Evan Spiliotopoulos, Stephen Chbosky', 'Emma Watson, Dan Stevens, Luke Evans, Josh Gad', 'http://www.21cineplex.com/data/gallery/pictures/148816706164595_452x647.jpg', '<iframe width="854" height="480" src="https://www.youtube.com/embed/e3Nl_TCQXuw" frameborder="0" allowfullscreen></iframe>', 'tt0101414', 8, 95, 0, 0, 0, 0),
(10, 'MOON CAKE STORY', 'David adalah seorang pengusaha muda yang menjadi taipan dan berada pada puncak keberhasilan bisnisnya. Di sisi lain, Asih adalah seorang wanita yang hidup di tengah kampung kumuh Jakarta dengan bekerja serabutan dari joki 3 in 1 dan mencuci dari toko laundry. Asih harus menjadi tulang punggung bagi anak dan adiknya di tengah beragam persoalan Jakarta yang penuh kisah-kisah kemanusiaan. Asih menjadi perempuan yang selalu bertanya: mampukah mengubah kemiskinan, namun juga mempertahankan prinsip hidup? \r\n\r\nSementara, David lalu bertanya berulang kali pada dirinya: Kenapa penyakit terjadi pada dirinya, justru di tengah puncak suksesnya? Dan kenapa dirinya seperti didorong ke ingatan masa lampau ketika dirinya hidup miskin bersama ibunya? \r\n\r\nKisah film ini dimulai ketika David harus melewati jalan 3 in 1 agar cepat sampai ke kantornya dengan supirnya, David tanpa sengaja tertarik pada sosok joki bernama Asih yang menggandeng anaknya, Bimo (12 tahun). \r\n\r\nKisah pun berlanjut. David menemui kembali Asih untuk memberi uang bayaran joki yang belum terbayar, namun yang terjadi kemudian, pertemuan demi pertemuan tanpa sengaja membawa David mengenal Asih, Bimo, dan Sekar, adik Asih, serta kehidupan lingkungan kampung kumuh tempat tinggal Asih di belakang jalan menuju 3 in 1.\r\n\r\nDavid yang dalam proses kehilangan ingatan, justru semakin terbawa memori masa kecilnya, terlebih David menemukan sosok Asih sebagai sosok ibunya yang bekerja keras untuk menghidupi David dan Kakak nya Aline kesedihan dan kegembiraan selalu meliputi kehidupan keluarga David .. dalam menjalani hidup di kota besar Ibu David adalah, sosok ibu tunggal yang mampu hidup di tengah kemiskinan dan merawat David dengan prinsip-prinsip hidup tentang manusia dan kerja.\r\n\r\nSementara, Asih lewat David menemukan keindahan mengubah dan membangun hidupnya dan keluarganya tanpa kehilangan prinsip hidup. Sebuah nilai hidup yang perlu ditumbuhkan di kota-kota besar, ketika kemanusiaan terkubur oleh kekerasan kompetisi hidup kota besar… Film yang memberikan arti hidup dalam menjalani keras nya Ibukota.', 'Animation, Family, Fantasy', 1991, '1991-11-22', 84, 'Garin Nugroho', 'Garin Nugroho', 'Bunga Citra Lestari, Morgan Oey, Dominique Diyose, Melati Zein', 'http://www.21cineplex.com/data/gallery/pictures/148792288417018_300x430.jpg', '<iframe width="854" height="480" src="https://www.youtube.com/embed/KjqRVPp07NU" frameborder="0" allowfullscreen></iframe>', 'tt0101414', 8, 95, 0, 0, 0, 0),
(11, 'DEAR NATHAN', 'Tidak ada hal yang sangat diinginkan SALMA di sekolah barunya selain focus pada belajar dan menunjukkan prestasinya. Sebagai murid pindahan di SMA Garuda, Salma berusaha selektif memilih teman. Sayangnya pagi itu Salma terlambat datang dan seorang siswa yang tidak kenal menolongnya menyelinap kesekolah dan menyelamatkannya dari hukuman terlambat upacara bendera. Belakangan Salma tahu bahwa siswa penolong itu bernama NATHAN, murid paling berandal seantero sekolah yang hobi tawuran.\r\n\r\nSebagai murid baik-baik, tentu Salma berusaha menjauhi orang macam Nathan. Namun, masalah datang ketika Nathan dengan terang-terangan mengejar cinta Salma dan membuat heboh satu sekolah. Berbagai cara digunakan Salma untuk menghindar, tapi sepertinya kesempatan-kesempatan tak terduga justru mengantarnya semakin dekat dengan Nathan.\r\n\r\nSaat Salma memahami titik rapuh masa lalu Nathan, dia pun bersimpati dan perlahan jatuh cinta. Saat cinta Salma tumbuh, dia ingin merubah Nathan menjadi Nathan yang baru. Di saat Nathan serius membuka diri untuk diubah oleh Salma, kekasih masa lalu Nathan bernama SELI, datang untuk meminta cinta Nathan kembali.\r\n\r\nAkankah Salma mempertahankan Nathan sebagai cinta pertama dalam hidupnya?', 'Drama', 2018, '2018-11-22', 84, 'Indra Gunawan', 'Bagus Bramanti, Gea Rexy', 'Jefri Nichole, Amanda Rawles, Rayn Wijaya, Diandra Agatha', 'http://www.21cineplex.com/data/gallery/pictures/148732443788212_300x430.jpg', '<iframe width="854" height="480" src="https://www.youtube.com/embed/8GIQsLKMBkk" frameborder="0" allowfullscreen></iframe>', 'tt0101414', 8, 95, 0, 0, 0, 0),
(12, 'BARACAS', 'Bandung geger, oleh adanya pemuda yang terpaksa meninggalkan keluarganya untuk bergabung dengan BARACAS. Baracas adalah merupakan Kelompok Independen yang dibentuk oleh seseorang bernama AGUS untuk menjadi tempat bergabungnya kaum lelaki yang dikecewakan oleh wanita. Mereka meng-klaim dirinya sebagai kaum lelaki yang merana karena adanya peristiwa pengkhianatan, penolakan, dan lain-lain sebagainya. Mereka menghimpun kekuatan dan menyatukan perasaan untuk bersama-sama dengan sengaja melakukan upaya membenci wanita di seluruh dunia dan pada semua unsur yang bersangkutan dengan wanita. Mereka menganggap dirinya sebagai korban wanita. Apa yang sudah dilakukan oleh wanita kepada mereka dinggapnya sebagai kekejaman yang tidak bisa dimaafkan dan menjatuhkan harga diri laki-laki di seluruh dunia. AGUS, pendiri dan sekaligus ketua Baracas adalah mantan pacarnya SARAH, Agus kecewa kepada Sarah karena secara tiba-tiba Sarah memutuskan hubungan mereka. Alhasil Agus geram, Agus marah, tidak cuma ke Sarah tetapi kepada seluruh wanita yang ada di muka bumi ini.\r\n\r\nGerakan-gerakan perlawanan terhadap BARACAS dengan berbagai macam upaya. Mereka merunding pihak polisi dan pemerintahan kota Bandung, sengaja membiarkan Baracas untuk berkembang besar. Itulah sebabnya mereka demo. Itulah sebabnya mereka menuntut polisi untuk menindak anggota Baracas. Bahkan Baracas mendapatkan teror berupa serangan fisik dari orang-orang tertentu untuk merusak markas Baracas dengan menggunakan kekerasan. Perlawanan lain dilakukan juga oleh para wanita yang tak lain adalah para mantan anggota Baracas. Mereka bergabung untuk membuat kekuatan dengan tujuan meluluhkan perasaann anggota Baracas dengan melakukan berbagai cara ampuh untuk mengubah cara berpikir mereka. Mereka dibantu oleh CEU POPONG yang selalu memberi nasihat dan masukan apa-apa saja yang dilakukan oleh para mantan itu. Akankah para mantan itu berhasil meluluhkan perasaan mereka?', 'Comedy', 2017, '2017-03-23', 120, 'Pidi Baiq', 'Pidi Baiq', 'Ringgo Agus Rahman, Ajun Perwira, Stella Cornelia', 'http://www.21cineplex.com/data/gallery/pictures/14873264526232_300x430.jpg', '<iframe width="854" height="480" src="https://www.youtube.com/embed/0469OXCxnPQ" frameborder="0" allowfullscreen></iframe>', 'tt0101414', 8, 95, 0, 0, 0, 0),
(13, 'POWER RANGERS', 'Film yang menjadi seri pertama Power Rangers di abad ke 21 ini akan menampilkan beberapa tokoh utama dari seri Mighty Morphin Power Rangers yang akan diperankan oleh aktor terbaru. \\n \\nBerkisah mengenai lima remaja yang dipersatukan oleh  sebuah insiden yang', 'Action, Adventure, Sci-fi', 2017, '2017-02-23', 120, 'Dean Israelite', 'John Gatins, Matt Sazama, Burk Sharpless, Zack Stentz', 'Elizabeth Banks, Rj Cyler, Naomi Scott, Becky G.', 'http://www.21cineplex.com/data/gallery/pictures/148739512016390_300x430.jpg', '<iframe width="854" height="480" src="https://www.youtube.com/embed/5kIe6UZHSXw" frameborder="0" allowfullscreen></iframe>', 'tt4475970', 7.8, 0, 0, 0, 0, 0),
(14, 'SMURFS THE LOST VILLAGE', 'Ketika Smurfette (Demi Lovato) menemukan sebuah peta misterius, ia bersama para sahabatnya Brainy, Clumsy dan Hefty pergi dalam sebuah petualangan menuju sebuah hutan terlarang yang dihuni oleh hewan ajaib untuk mecari sebuah desa misterius sebelum penyihir jahat Gargamel menemukannya. Dengan melalui perjalan yang dipenuhi rintangan dan bahaya, para Smurf akan menemukan sebuah rahasia terbesar dalam sejarah kaum Smurf.', 'Animation, Adventure, Comedy', 2017, '2017-04-07', 89, 'Kelly Asbury', 'Stacey Harman, Pamela Ribon, Peyo (based on the characters and works of)', 'Ariel Winter, Julia Roberts, Ellie Kemper, Joe Manganiello', 'https://images-na.ssl-images-amazon.com/images/M/MV5BMTg1NjgyMTYzM15BMl5BanBnXkFtZTgwMzIxNDc4MDI@._V1_SX300.jpg', '<iframe width="854" height="480" src="https://www.youtube.com/embed/vu1qZCG6Yo8" frameborder="0" allowfullscreen></iframe>', 'tt2398241', 0, 0, 0, 0, 0, 0),
(15, 'BID''AH CINTA', 'Hubungan asmara antara Khalida dan Kamal tak direstui oleh dua keluarga yang saling berbeda dan bermusuhan, keluarga mereka mempunyai pemahaman tentang Islam yang berbeda satu sama lain. Islam puritan & Islam tradisional. Persoalan perbedaan pandangan agama ini menyeret hubungan asmara mereka ke dalam pusaran konflik. Khalida adalah anak H. Rohili, seorang yang sangat akrab dengan para pemuda di kampung itu. Di sisi lain, Kamal adalah anak lelaki H. Jamat, seorang haji kaya yang cukup disegani dan menjadi pendukung utama penyebaran Islam puritan di kampung yang dimotori kemenakannya bernama Ustadz Jaiz.\r\n\r\nPerbenturan antara H. Rohili dan H. Jamat pada akhirnya juga membenturkan hubungan Khalida dengan Kamal. Khalida yang dibesarkan dalam ajaran Islam tradisional merasa terganggu dengan perkembangan ini. Sebaliknya, Kamal yang banyak mendapat pengaruh dari ajaran Islam puritan H. Jamat dan berkepentingan dengan pekerjaannya di Yayasan pendidikan yang dipimpin oleh Ustadz Jaiz, merasa bingung dan tertekan dalam posisinya yang sulit. \r\n\r\nDi tengah lingkungan yang tak mungkin diseragamkan dan di mana perbedaaan merupakan suatu keniscayaan, bagaimanakah kelanjutan kisah cinta Khalida dan Kamal? Apakah cinta dapat menghapus segala kebencian yang ada?', 'Drama', 2017, '2017-03-16', 128, 'Casper Van Dien', 'Nurman Hakim', 'Cassi Thomson, Samantha Cope, Casper Van Dien, Randy Wayne', 'http://www.21cineplex.com/data/gallery/pictures/14867100493795_300x430.jpg', '<iframe width="854" height="480" src="https://www.youtube.com/embed/_o1fwxzZIuE" frameborder="0" allowfullscreen></iframe>', 'tt5084204', 5.7, 0, 0, 0, 0, 0),
(16, 'THE SPACE BETWEEN US', 'Sekelompok astronot diutus untuk pergi ke dalam misi penelitian di Mars. Ketika mendarat, salah seorang astronot menemukan jika ia tengah mengandung. Tidak lama kemudian astronot itu meninggal setelah ia melahirkan seorang bayi lelaki. \r\n \r\nEnam belas tahun kemudian, putra dari sang astronot, Gardner Elliot telah tumbuh menjadi seorang remaja yang menghabiskan hidupnya di planet Mars bersama para astronot. Gardner memiliki keinginan untuk bertemu dengan ayah kandungnya di bumi. Sementara itu ia juga menjalani persahabatan dengan Tulsa (Britt Robertson), seorang gadis yang tinggal di Colorado. \r\n \r\nKetika Gardner dan astronot lainnya kembali ke bumi, ia menyadari jika organ tubuhnya tidak terbiasa dengan atmosfir bumi. Ketika dirawat di rumah sakit, Gardner melarikan diri untuk mencari Tulsa. ', 'Adventure, Drama, Romance', 2017, '2017-02-03', 120, 'Peter Chelsom', 'Allan Loeb (screenplay), Stewart Schill (story by), Richard Barton Lewis (story by), Allan Loeb (story by)', 'Gary Oldman, Janet Montgomery, Trey Tucker, Scott Takeda', 'https://images-na.ssl-images-amazon.com/images/M/MV5BNjYzODU1OTkwN15BMl5BanBnXkFtZTgwMDA3MTMwMDI@._V1_SX300.jpg', '<iframe width="854" height="480" src="https://www.youtube.com/embed/x73-573aWfs" frameborder="0" allowfullscreen></iframe>', 'tt3922818', 6.3, 33, 0, 0, 0, 0),
(17, 'DANUR', 'Film ini bercerita tentang Risa di hari ulang tahunnya ke-8, Risa dengan polosnya meminta seorang teman agar ia tidak kesepian lagi. Namun ternyata ibunya, Elly, mulai curiga mendapati anaknya sering tertawa sendiri dan bermain seolah-olah dengan banyak teman, padahal Elly hanya melihatnya bermain sendiri! Elly mencari jalan untuk memisahkan Risa dari sahabat nya yang ternyata hantu. \r\n\r\nDengan terpaksa teman Risa pergi dari rumah nenek nya dan berpisah dengan teman teman nya. 9 Tahun kemudian Risa harus kembali ke rumah tersebut menjaga nenek bersama adik nya Riri, kejadian kejadian aneh dan gangguan roh halus mulai terjadi lagi. Puncak nya ketika Riri tiba tiba menghilang, Risa harus menyelamatkan adiknya Riri dari hantu jahat yang berencana membawa Riri ke dunia lain. Saksikan film Danur yang diangkat dari Gerbang dialog Danur karya Risa Saraswati.', 'Horror', 2017, '2017-03-30', 0, 'Awi Suryadi', 'N/A', 'Prilly Latuconsina, Shareefa Daanish, Asha Kenyeri Bermudez, Kevin Bzezovski', 'https://images-na.ssl-images-amazon.com/images/M/MV5BOWQ5ZTQyYjMtYTI1Ni00NDVhLTljZGMtMTQyNDEyZDJmNzQwL2ltYWdlXkEyXkFqcGdeQXVyMzMzNjk0NTc@._V1_SX300.jpg', '<iframe width="854" height="480" src="https://www.youtube.com/embed/YLU6Qfi0cDY" frameborder="0" allowfullscreen></iframe>', 'tt6496236', 0, 0, 0, 0, 0, 0),
(18, 'TRINITY, THE NEKAD TRAVELER', 'Awalnya TRINITY (Maudy Ayunda) adalah seorang Mbak-mbak kantoran yang hobi traveling sejak kecil. Namun hobinya ini sering terbentur dengan duit pas-pasan dan jatah cuti di kantor. Akibatnya Trinity sering diomeli BOSS (Ayu Dewi). Trinity memiliki sahabat yang punya hobi sama, yakni YASMIN (Rachel Amanda) dan NINA (Anggika Bolsterli), ditambah dengan sepupu Trinity, EZRA (Babe Cabita). Trinity selalu menuliskan pengalamannya dalam sebuah blog berjudul naked-traveler.com.\r\n\r\nDi rumah, BAPAK (Farhan) dan MAMAH (Cut Mini) selalu menanyakan kapan Trinity serius memikirkan jodoh. Tapi Trinity selalu menjawab : nanti kalau semua bucket list sudah terpenuhi. Bucket list adalah daftar hal-hal yang harus Trinity lakukan sebelum tua, kebanyakan sih isinya (lagi-lagi) tentang jalan-jalan. Bapak langsung pusing mendengarnya. Trinity mengalami dilema antara fokus ke pekerjaannya sekarang atau mengejar passion dia yang sebenarnya, hingga kisah cintanya dengan PAUL (Hamish Daud) seorang fotografer tampan yang juga hobi traveling.', 'Drama', 2017, '2017-03-30', 103, 'Rizal Mantovani', 'Rahabi Mandra', 'Maudy Ayunda, Hamish Daud, Babe Cabiita', 'http://www.21cineplex.com/data/gallery/pictures/14860059712454_300x430.jpg', '<iframe width="854" height="480" src="https://www.youtube.com/embed/TNOALGw6vqM" frameborder="0" allowfullscreen></iframe>', 'tt6496236', 0, 0, 0, 0, 0, 0),
(19, 'SHUT IN', 'Mary (Naomi Watts) yang berprofesi sebagai psikolog anak mencoba untuk membangun kehidupnya kembali setelah ia kehilangan suaminya dalam sebuah kecelakaan mobil. Akan tetapi, meskipun putranya yang bernama Stephen(Charlie Heaton) lolos dari maut, kecelakaan tersebut telah membuatnya jatuh ke dalam koma.\r\n \r\nMary yang membuka praktek di rumah mendapatkan seorang pasien baru bernama Tom (Jacob Tremblay), seorang anak yang ibunya baru saja meninggal. Saat mendengar jika Tom akan dibawa ke Boston, Mary memutuskan untuk merawat Tom dirumahnya sendiri. Tidak lama setelah itu, Tom melarikan diri di tengah badai salju dan dinyatakan meninggal oleh yang berwajib, meskipun tubuhnya tidak pernah ditemukan.\r\n \r\nMary yang merasa bersalah atas kematian Tom tiba-tiba mendengar suara dan melihat bayangan anak itu di dalam rumahnya. Kekuatan mental sang psikolog mulai diuji ketika beberapa kejadian misterius mulai bermunculan pada saat badai salju membuatnya terjebak di rumahnya sendiri.', 'Drama, Thriller', 2016, '2016-11-11', 91, 'Farren Blackburn', 'Christina Hodson', 'Naomi Watts, Oliver Platt, Charlie Heaton, Jacob Tremblay', 'https://images-na.ssl-images-amazon.com/images/M/MV5BMjM3MTAyMTE2MV5BMl5BanBnXkFtZTgwMzY5MzM0MDI@._V1_SX300.jpg', '<iframe width="854" height="480" src="https://www.youtube.com/embed/G7czL2a5R3c" frameborder="0" allowfullscreen></iframe>', 'tt2582500', 4.4, 22, 0, 0, 0, 0);

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
(4, 1, 'meloniaseven@gmail.com', 8, 'Great. Love this one. Super funny. So many lovable characters (I love the batman!!)', '2017-03-08 21:56:21'),
(5, 2, 'meloniaseven@gmail.com', 6, 'Meh, not that great.\r\nSangat mengecewakan ya buat yg udah nonton semua seri xmen. Ini film yg memang buat jadi akhirnya dr xmen, tapi ya ampun, mengecewakan deh pokoknya. Ceritanya ga jelas, karakteristiknya Wolverine The Legend yg dr awal cerita itu immortal + ageless, sekarang tiba-tiba jadi tua dan sakit"an. Darimana bisa kayak gitu? Ga dijelasin. Sama kayak film xmen sebelumnya (Future Past), ga jelas darimana awalnya. Top deh.', '2017-03-08 21:56:21'),
(6, 3, 'meloniaseven@gmail.com', 6, 'Film apa ini, ga jelas banget', '2017-03-08 21:58:49'),
(8, 1, 'scooby@gmail.com', 8, 'Top deh. Suka banget gw sama film ini.', '2017-03-08 21:59:50'),
(9, 2, 'scooby@gmail.com', 9, 'action-nya keren banget brooohhh!! love laura!!', '2017-03-08 21:59:50'),
(12, 3, 'scooby@gmail.com', 7, 'yahh oke lahh', '2017-03-09 17:36:27'),
(13, 2, 'admin@abcmovies.co.id', 8, 'cool action scenes', '2017-03-10 16:00:45');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `email` varchar(125) NOT NULL COMMENT 'cannot be changed',
  `password` varchar(32) NOT NULL,
  `name` varchar(125) NOT NULL,
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `film`
--
ALTER TABLE `film`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

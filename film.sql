-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: 127.0.0.1
-- Čas generovania: Št 11.Jún 2020, 08:35
-- Verzia serveru: 10.4.11-MariaDB
-- Verzia PHP: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `film`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `film_pouzivatelia`
--

CREATE TABLE `film_pouzivatelia` (
  `id_pouz` int(11) NOT NULL,
  `prihlasmeno` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci NOT NULL,
  `heslo` varchar(50) CHARACTER SET utf8 COLLATE utf8_slovak_ci NOT NULL,
  `email` varchar(50) NOT NULL,
  `admin` tinyint(4) DEFAULT 0,
  `meno` varchar(50) NOT NULL,
  `priezvisko` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Sťahujem dáta pre tabuľku `film_pouzivatelia`
--

INSERT INTO `film_pouzivatelia` (`id_pouz`, `prihlasmeno`, `heslo`, `email`, `admin`, `meno`, `priezvisko`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@admin.sk', 1, 'ADMIN', 'ADMIN'),
(2, 'cajka', '38a5018600e967dbd2deedc90b296fc9', 'test@test.sk', 0, 'Filip', 'Čajka'),
(3, 'fero', 'ac6545028a5d090df842d8d9d674fc6e', 'fero@fajka.sk', 0, 'František', 'Fajka'),
(4, 'jojo', '7510d498f23f5815d3376ea7bad64e29', 'jojo@jurko.com', 0, 'Jozef', 'Jurko'),
(6, 'Majoo', '73d5406bdbe52c9b19a9e6eb8b7b7e78', 'majo@majoneza.sk', 0, 'Marian', 'Majonéza'),
(7, 'meno', 'b075559d96925fe3c69a36e188a78b69', 'meno@meno.sk', 0, 'meno', 'meno'),
(8, 'majo', 'b6d116c31315a8a7eb4fefb79ee38483', 'mail@mail.com', 1, 'Martin', 'Zadaom'),
(9, 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 'user@user.sk', 0, 'USER', 'USER');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `film_premietanie`
--

CREATE TABLE `film_premietanie` (
  `id_prem` int(11) NOT NULL,
  `miesta` varchar(300) NOT NULL,
  `id_film` int(11) NOT NULL,
  `datum` date NOT NULL,
  `cas` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Sťahujem dáta pre tabuľku `film_premietanie`
--

INSERT INTO `film_premietanie` (`id_prem`, `miesta`, `id_film`, `datum`, `cas`) VALUES
(1, '7;8;17;18;3;1;2;9;10;11;12;13;15;16;19;20;21;22;23;24;25;26;27;28;29;30;31;32;33;34;35;36;37;38;39;40', 1, '2020-06-25', '15:55:00');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `film_rezervacie`
--

CREATE TABLE `film_rezervacie` (
  `id_rezer` int(11) NOT NULL,
  `id_pouz` int(11) NOT NULL,
  `id_film` int(20) NOT NULL,
  `miesta` varchar(300) NOT NULL,
  `datum` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Sťahujem dáta pre tabuľku `film_rezervacie`
--

INSERT INTO `film_rezervacie` (`id_rezer`, `id_pouz`, `id_film`, `miesta`, `datum`) VALUES
(53, 1, 1, '5;6', '2020-06-10'),
(56, 9, 1, '4;14', '2020-06-10');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `film_zoz`
--

CREATE TABLE `film_zoz` (
  `id_film` int(11) NOT NULL,
  `nazov` varchar(50) NOT NULL,
  `rok` year(4) NOT NULL,
  `reziser` varchar(20) NOT NULL,
  `popis` text NOT NULL,
  `foto` varchar(200) NOT NULL,
  `imdb` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Sťahujem dáta pre tabuľku `film_zoz`
--

INSERT INTO `film_zoz` (`id_film`, `nazov`, `rok`, `reziser`, `popis`, `foto`, `imdb`) VALUES
(1, 'Avengers: Nekonečná vojna', 2018, 'Joe Russo, Anthony R', 'Marvel Studios uvádí super hrdinský tým všech dob Avengers, ve kterém se přestaví ikoničtí super hrdinové – Iron Man, Neuvěřitelný Hulk, Thor, Captain America, Hawkeye a Black Widow. Když se objeví nečekaný nepřítel, který ohrožuje světovou bezpečnost, Nick Fury, ředitel mezinárodní mírové agentury, známé také jako S.H.I.E.L.D., zjistí, že potřebuje tým, aby odvrátil světovou katastrofu. Začíná provádět nábor po celém světě.', 'obrazky/fototest.jpg', '<span class=\"imdbRatingPlugin\" data-user=\"ur119222386\" data-title=\"tt4154756\" data-style=\"p4\"><a href=\"https://www.imdb.com/title/tt4154756/?ref_=plg_rt_1\"><img src=\"https://ia.media-imdb.com/images/G/01/imdb/plugins/rating/images/imdb_31x14.png\" alt=\" Avengers: Nekonečná vojna\r\n(2018) on IMDb\" />\r\n</a></span><script>(function(d,s,id){var js,stags=d.getElementsByTagName(s)[0];if(d.getElementById(id)){return;}js=d.createElement(s);js.id=id;js.src=\"https://ia.media-imdb.com/images/G/01/imdb/plugins/rating/js/rating.js\";stags.parentNode.insertBefore(js,stags);})(document,\"script\",\"imdb-rating-api\");</script>'),
(16, 'Avatar', 2009, 'James Cameron', 'Avatar před námi otevírá neuvěřitelný svět za hranicemi naší fantazie, svět střetu dvou naprosto odlišných civilizací. Nově objevená vzdálená planeta Pandora je mírumilovné místo s obyvatelstvem - Na’vi, žijícím v souladu s divukrásnou vegetací planety. Posádka vyslaná ze Země na své průzkumné misi objeví na Pandoře velmi cenný minerál, který by měl na Zemi nevyčíslitelnou hodnotu. Pobyt na Pandoře je ovšem pro člověka možný teprve po vytvoření jeho genetického dvojníka, hybrida Avataru, který může být ovládán psychikou oddělenou od lidského těla a fyzicky odpovídá původnímu obyvatelstvu Pandory, které má fluorescentní modrou kůži a dosahuje 3m výšky. Na tuto náročnou misi je vybrán mezi jinými také Jake Sully (Sam Wothington), bývalý námořník, který byl při jedné ze svých předešlých misí paralyzován od pasu dolů. A právě šance opět chodit přiměla Jakea, aby se do programu Avatar přihlásil.', 'obrazky/avatarf.jpg', '<span class=\"imdbRatingPlugin\" data-user=\"ur119222386\" data-title=\"tt0499549\" data-style=\"p4\"><a href=\"https://www.imdb.com/title/tt0499549/?ref_=plg_rt_1\"><img src=\"https://ia.media-imdb.com/images/G/01/imdb/plugins/rating/images/imdb_31x14.png\" alt=\" Avatar\r\n(2009) on IMDb\" />\r\n</a></span><script>(function(d,s,id){var js,stags=d.getElementsByTagName(s)[0];if(d.getElementById(id)){return;}js=d.createElement(s);js.id=id;js.src=\"https://ia.media-imdb.com/images/G/01/imdb/plugins/rating/js/rating.js\";stags.parentNode.insertBefore(js,stags);})(document,\"script\",\"imdb-rating-api\");</script>'),
(28, 'TO', 1990, 'Tommy Lee Wallace', 'TO může být čímkoli. Zubatým monstrem, které nezůstává jen na filmovém plátně. Čímsi zlověstným, co číhá ve sklepě. Ať už se bojíte čehokoliv, nikdo To nezná lépe než Stephen King. To, natočeného podle bestselleru Stephena Kinga z roku 1986, je zneklidňujícím výletem do temnoty vnitřních strachů, v němž hlavní role vytvořili Harry Anderson, Annette O\'Toole, John Ritter a Richard Thomas. Zlovolná síla na sebe v malém městečku v Nové Anglii bere podobu klauna (Tim Curry), který ovšem své okolí nebaví veselými kousky. On naopak děti děsí a zabíjí – dokud se některé z nich nezačnou bránit. Po třiceti letech se ale zlo vrací – horší, zuřivější a ještě nebezpečnější. A přátelé, kteří mají dosud v živé paměti hrozivé zážitky svého dětství, se znovu dají dohromady, aby To zničili jednou provždy.', 'obrazky/TO.jpg', '<span class=\"imdbRatingPlugin\" data-user=\"ur119222386\" data-title=\"tt0099864\" data-style=\"p4\"><a href=\"https://www.imdb.com/title/tt0099864/?ref_=plg_rt_1\"><img src=\"https://ia.media-imdb.com/images/G/01/imdb/plugins/rating/images/imdb_31x14.png\" alt=\" It\r\n(1990) on IMDb\" />\r\n</a></span><script>(function(d,s,id){var js,stags=d.getElementsByTagName(s)[0];if(d.getElementById(id)){return;}js=d.createElement(s);js.id=id;js.src=\"https://ia.media-imdb.com/images/G/01/imdb/plugins/rating/js/rating.js\";stags.parentNode.insertBefore(js,stags);})(document,\"script\",\"imdb-rating-api\");</script>'),
(29, 'Ježko Sonic', 2020, 'Jeff Fowler', 'Schopnost utíkat nejrychleji ze všech na světě (na jakémkoliv světě, v němž se právě vyskytuje) by ježek Sonic bez mrknutí oka vyměnil za opravdového kamaráda. Ze svého lesního útočiště sice chodí navštěvovat dobráckého maloměstského šerifa Toma (James Marsden), ale ten se tak úplně nepočítá, protože si Sonica při jeho rychlosti ještě nestačil všimnout. A Sonic se snaží nezanechávat stopy své přítomnosti, protože ví, že by pak po něm šli. Kdo? Všichni! Jenže i ten nejopatrnější ježek může udělat malou chybu, kterou bylo v jeho případě dočasné vypnutí elektřiny v polovině Ameriky. Bezradná a znepokojená vláda na odhalení příčin kolosálního výpadku najme toho vůbec nejschopnějšího génia, lehounce šíleného Doktora Robotnika (Jim Carrey). A ten velmi brzy zavětří Sonicovu stopu. A Sonic rázem zjistí, že ani nejrychlejší nohy nemusí k úspěšnému útěku stačit, zvlášť když ztratil magické kroužky, které mu umožňovaly rychle cestovat mezi různými světy. Jediný, kdo zná jejich aktuální polohu, je jeho „kámoš“, šerif Tom, a ten se do záchrany mimozemského ježka před neúnavným a vynalézavým Doktorem Robotnikem zrovna nežene. Pak si však jako správný ochránce zákona uvědomí, že slabší je třeba bránit, a začnou se dít věci…', 'obrazky/sonic.jpg', '<span class=\"imdbRatingPlugin\" data-user=\"ur119222386\" data-title=\"tt3794354\" data-style=\"p4\"><a href=\"https://www.imdb.com/title/tt3794354/?ref_=plg_rt_1\"><img src=\"https://ia.media-imdb.com/images/G/01/imdb/plugins/rating/images/imdb_31x14.png\" alt=\" Ježko Sonic\r\n(2020) on IMDb\" />\r\n</a></span><script>(function(d,s,id){var js,stags=d.getElementsByTagName(s)[0];if(d.getElementById(id)){return;}js=d.createElement(s);js.id=id;js.src=\"https://ia.media-imdb.com/images/G/01/imdb/plugins/rating/js/rating.js\";stags.parentNode.insertBefore(js,stags);})(document,\"script\",\"imdb-rating-api\");</script>'),
(30, 'Zorro: Tajomná tvár', 1998, 'Martin Campbell', 'Na území dnešní Kalifornie bojoval před lety proti bezpráví španělských kolonizátorů legendární hrdina Zorro. Muž s černou maskou přes oči a zastánce chudých byl ve skutečnosti ctihodný a zámožný Don Diego de la Vega (Anthony Hopkins). Královský místodržící Don Rafael Montero (Stuart Wilson) naneštěstí krátce před pádem španělské krutovlády odhalil Zorrovu identitu. Při dramatickém střetnutí tragicky umírá Vegova milovaná manželka a krutý Montero si odváží i jeho malou dcerku Elenu. Ctihodný Don Diego de la Vega alias obdivovaný a milovaný Zorro mizí na dlouhých dvacet let v temné vězeňské kobce. Uplynulo dvacet let. Don Montero se vrací do Kalifornie. Chce tu založit nezávislou republiku a hlavně vydrancovat nově objevené zlaté doly. De la Vega hnán touhou po pomstě prchá z vězení a přijímá za svého žáka buranského zlodějíčka Alejandra Murietu (Antonio Banderas), který mu kdysi před dvaceti lety jako malý chlapec zachránil život a nyní utápí svůj žal nad ztrátou bratra kde jinde než v alkoholu. De la Vegu a Murietu, minulého a budoucího Zorra, spojuje kromě náklonnosti k chudým i nenávist ke společnému nepříteli - Alejandrův bratr totiž zemřel rukou jednoho z Monterových hrdlořezů. Ovšem dříve než okusí sladkou pomstu a zachrání pracovitý lid Kalifornie před nástrahami proradného Montera, čeká na oba nejedna náročná zkouška. Mladý Alejandro se musí zdokonalit v boji i ve společenském chování a de la Vega se musí znovu vyrovnat se ztrátou své dcery, která přijela do Kalifornie s Monterou a považuje ho za svého otce...', 'obrazky/zorro.jpg', '<span class=\"imdbRatingPlugin\" data-user=\"ur119222386\" data-title=\"tt0120746\" data-style=\"p4\"><a href=\"https://www.imdb.com/title/tt0120746/?ref_=plg_rt_1\"><img src=\"https://ia.media-imdb.com/images/G/01/imdb/plugins/rating/images/imdb_31x14.png\" alt=\" Zorro: Tajomná tvár\r\n(1998) on IMDb\" />\r\n</a></span><script>(function(d,s,id){var js,stags=d.getElementsByTagName(s)[0];if(d.getElementById(id)){return;}js=d.createElement(s);js.id=id;js.src=\"https://ia.media-imdb.com/images/G/01/imdb/plugins/rating/js/rating.js\";stags.parentNode.insertBefore(js,stags);})(document,\"script\",\"imdb-rating-api\");</script>');

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `film_pouzivatelia`
--
ALTER TABLE `film_pouzivatelia`
  ADD PRIMARY KEY (`id_pouz`);

--
-- Indexy pre tabuľku `film_premietanie`
--
ALTER TABLE `film_premietanie`
  ADD PRIMARY KEY (`id_prem`);

--
-- Indexy pre tabuľku `film_rezervacie`
--
ALTER TABLE `film_rezervacie`
  ADD PRIMARY KEY (`id_rezer`);

--
-- Indexy pre tabuľku `film_zoz`
--
ALTER TABLE `film_zoz`
  ADD PRIMARY KEY (`id_film`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `film_pouzivatelia`
--
ALTER TABLE `film_pouzivatelia`
  MODIFY `id_pouz` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pre tabuľku `film_premietanie`
--
ALTER TABLE `film_premietanie`
  MODIFY `id_prem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pre tabuľku `film_rezervacie`
--
ALTER TABLE `film_rezervacie`
  MODIFY `id_rezer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT pre tabuľku `film_zoz`
--
ALTER TABLE `film_zoz`
  MODIFY `id_film` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

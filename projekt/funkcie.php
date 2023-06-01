<?php
date_default_timezone_set('Europe/Bratislava');
	
function hlavicka($nadpis) {
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $nadpis; ?></title>
<link href="styly.css" rel="stylesheet">
</head>

<body>

<header>
<h1><?php echo $nadpis; ?></h1>
</header>
<?php
}

#kontorla či prihlasmeno sa nachádza v db, ak áno vráti TRUE.
function kontrola_mena($prihlasmeno, $mysqli){
	$sql = "SELECT * FROM film_pouzivatelia WHERE prihlasmeno='$prihlasmeno'";
	$result = mysqli_query($mysqli, $sql);
	#
    if (mysqli_num_rows($result) > 0 ){
        return true;
       }else{
		return false;
	} 
}

#kontorla či film sa nachádza v db, ak áno vráti TRUE.
function kontrola_film($film, $mysqli){
	$sql = "SELECT * FROM film_zoz WHERE nazov='$film'";
	$result = mysqli_query($mysqli, $sql);
	#
    if (mysqli_num_rows($result) > 0 ){
        return true;
       }else{
		return false;
	} 
}


#kontorla či email sa nachádza v db, ak áno vráti TRUE.
function kontrola_email($email, $mysqli){
	$sql = "SELECT * FROM film_pouzivatelia WHERE email='$email'";
	$result = mysqli_query($mysqli, $sql);
	#
    if (mysqli_num_rows($result) > 0 ){
        return true;
       }else{
		return false;
		}
}
#skontroluje ci ma email spravny format
function sprava_email($email){
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		return true;
	  }
	  else{
		  return false;
	  }
}

function osetri($co) {
	return trim(strip_tags($co));
}

function nazov_ok ($nazov) {
	return strlen($nazov) >= 2 && strlen($nazov) <= 30;
}

function meno_ok ($nazov) {
	return strlen($nazov) >= 3 && strlen($nazov) <= 100;
}

function popis_ok ($popis) {
	return strlen($popis) >= 10;
}
//kontorla ci je rok od 1940 do teraz
function rok_ok ($rok) {
	return ((date("Y")) >= $rok && $rok > 1940);
}

//vyíše všetkých používateľov
function vypis_uzivatelov($mysqli) {
	if (!$mysqli->connect_errno) {
		$sql = "SELECT * FROM `film_pouzivatelia` ORDER BY `film_pouzivatelia`.`admin` DESC"; 
		if ($result = $mysqli->query($sql)) {
			echo '<table>';
			echo '<tr><th>ID</th><th>Username</th><th>Email</th><th>Práva</th></tr>';
			while ($row = $result->fetch_assoc()) {
				if ($row['admin'] == 0){
					$prava="user";
				}
				else{
					$prava="Admin";
				}
				echo '<tr><td>' . $row['id_pouz'] . '</td><td>' . $row['prihlasmeno'] . '</td><td>' . $row['email'] . '</td><td>' . $prava . '</td><td><a href="uprav.php?user=' . $row['id_pouz'] . '">' . 'uprav' . '</a></td>';
				echo "</tr>\n";
			}
			echo '</table>';
			$result->free();
		} else {
			echo '<p class="chyba">NEpodarilo sa získať údaje z databázy</p>';
		}
	}else{
		// NEpodarilo sa spojiť s databázovým serverom!
		echo '<p class="chyba">NEpodarilo sa spojiť s databázovým serverom!</p>';
		return false;
	}
}



//vyíše všetkých používateľov
function vypis_rezervacie($mysqli, $id, $admin) {
	if (!$mysqli->connect_errno) {
		if (!$admin){
			// vypíše rezervácie používatela
			$sql = "SELECT * FROM `film_rezervacie`, film_zoz WHERE id_pouz = '$id' AND film_rezervacie.id_film = film_zoz.id_film" ;
			if ($result = $mysqli->query($sql)) {  
				echo '<table>';
				echo '<tr><th>Film</th><th>dátum rezezvácie</th><th>Miesta</th></tr>';
				while ($row = $result->fetch_assoc()) {
					echo '<tr><td>' . $row['nazov'] . '</td><td>' . $row['datum'] . '</td><td>' . $row['miesta'] . '</td><td><a href="zrusrezervaciu.php?poz=' . $row['id_pouz'] . '&rez=' . $row['id_rezer'] . '">' . 'zmaž' . '</a></td>';
					echo "</tr>\n";
				}
				echo '</table>';
				$result->free();
			} else {
				echo '<p class="chyba">NEpodarilo sa získať údaje z databázy</p>';
			}
			
		}
		else{
			// vypíše všetky rezervácie a admin ich môže odstrániť
			$sql = "SELECT * FROM `film_rezervacie`, film_pouzivatelia, film_zoz WHERE film_rezervacie.id_pouz = film_pouzivatelia.id_pouz AND film_rezervacie.id_film = film_zoz.id_film";
			if ($result = $mysqli->query($sql)) {
				echo '<table>';
				echo '<tr><th>Meno</th><th>Film</th><th>Dátum rezezvácie</th><th>Miesta</th></tr>';
				while ($row = $result->fetch_assoc()) {
					echo '<tr><td>' . $row['prihlasmeno'] . '</td><td>' . $row['nazov'] . '</td><td>' . $row['datum'] . '</td><td>' . $row['miesta'] . '</td><td><a href="zrusrezervaciu.php?poz=' . $row['id_pouz'] . '&rez=' . $row['id_rezer'] . '">' . 'zmaž' . '</a></td>';
					echo "</tr>\n";
				}
				echo '</table>';
				$result->free();
			} else {
				echo '<p class="chyba">NEpodarilo sa získať údaje z databázy</p>';
			}
		}
	}
	else{
		// NEpodarilo sa spojiť s databázovým serverom!
		echo '<p class="chyba">NEpodarilo sa spojiť s databázovým serverom!</p>';
		return false;
	}
}

//pridá film do databázy a uložý fotku do priečinku obrazky
function pridaj_film($mysqli, $nazov, $popis, $rok, $reziser, $subor, $imdb) {
	if (!$mysqli->connect_errno) {
		$novy_nazov = '';
		if (!empty($subor) && !empty($subor['name'])) {
			if ($subor['error'] == UPLOAD_ERR_OK) {
				if (is_uploaded_file($subor['tmp_name'])) {
					$novy_nazov = 'obrazky/' . $subor['name'];
					$ok = move_uploaded_file($subor['tmp_name'], $novy_nazov);
					if ($ok) {
						echo '<p class="ok">Súbor bol nahratý na server.</p>';
					} else {
						echo '<p class="chyba">Súbor NEbol nahratý na server.</p>';
						$novy_nazov = '';
					}
				} else {
					echo '<p class="chyba">Súbor je podvrh.</p>';
				}
			} else { 
				// nastane, ak bol uploadovaný súbor väcší ako upload_max_filesize (chyba 2)
				// nastane aj vtedy, ak bol uploadovaný súbor väcší ako post_max_size (chyba 2)
				echo '<p class="chyba">Nastal problém pri uploadovaní súboru ' . $subor['name'] . ' - ' . $subor['error'] . '</p>';
			}
		}
		else{
			$novy_nazov = 'obrazky/error.jpg'; //ak nebol zadaný obrázok dostane obrázok error
		}
		$nazov = $mysqli->real_escape_string($nazov);
		$popis = $mysqli->real_escape_string($popis);
		$rok = $mysqli->real_escape_string($rok);
		$reziser = $mysqli->real_escape_string($reziser);

		$sql = "INSERT INTO film_zoz SET nazov='$nazov', popis='$popis', rok='$rok', reziser='$reziser', foto='$novy_nazov', imdb='$imdb'"; //dopyt pre vkladanie filmu do db
	
		if ($result = $mysqli->query($sql)) {
 	    echo '<p class="ok"> Film bol pridaný.</p>'. "\n"; //OK 
		} elseif ($mysqli->errno) {
			echo '<p class="chyba">Nastala chyba pri pridávaní filmu. (' . $mysqli->error . ')</p>';
		}
	}
	else{
		// NEpodarilo sa spojiť s databázovým serverom!
		echo '<p class="chyba">NEpodarilo sa spojiť s databázovým serverom!</p>';
		return false;
	}
}	// koniec pridaj_film



function uprav_film($mysqli, $nazov, $popis, $rok, $reziser, $subor, $imdb, $id) {
	if (!$mysqli->connect_errno) {
		$sql = "SELECT * FROM `film_zoz` WHERE `film_zoz`.`nazov` = '$id'";
		if (($result = $mysqli->query($sql)) && ($row = $result->fetch_assoc()) ) {
		$menosuboru = $row['foto'];
		}
		$novy_nazov = '';
		if (!empty($subor) && !empty($subor['name'])) {
			if ($subor['error'] == UPLOAD_ERR_OK) {
				if (is_uploaded_file($subor['tmp_name'])) {
					$novy_nazov = 'obrazky/' . $subor['name'];
					$ok = move_uploaded_file($subor['tmp_name'], $novy_nazov);
					if ($ok) {
						echo '<p class="ok">Súbor bol nahratý na server.</p>';
					} else {
						echo '<p class="chyba">Súbor NEbol nahratý na server.</p>';
						$novy_nazov = '';
					}
				} else {
					echo '<p class="chyba">Súbor je podvrh.</p>';
				}
			} else { 
				// nastane, ak bol uploadovaný súbor väcší ako upload_max_filesize (chyba 2)
				// nastane aj vtedy, ak bol uploadovaný súbor väcší ako post_max_size (chyba 2)
				echo '<p class="chyba">Nastal problém pri uploadovaní súboru ' . $subor['name'] . ' - ' . $subor['error'] . '</p>';
			}
		}
		

		else{
			$novy_nazov = $menosuboru;
		}
		
		$nazov = $mysqli->real_escape_string($nazov);
		$popis = $mysqli->real_escape_string($popis);
		$rok = $mysqli->real_escape_string($rok);
		$reziser = $mysqli->real_escape_string($reziser);

		$sql = "UPDATE film_zoz SET nazov='$nazov', popis='$popis', rok='$rok', reziser='$reziser', foto='$novy_nazov', imdb='$imdb' WHERE `film_zoz`.`nazov` = '$id'";
	
		if ($result = $mysqli->query($sql)) {
 	    echo '<p class="ok">Film bol upravený.</p>'. "\n"; 
		} elseif ($mysqli->errno) {
			echo '<p class="chyba">Nastala chyba pri úprave filmu. (' . $mysqli->error . ')</p>';
		}
	}
}	// koniec funkcie


// vrati udaje pouzivatela ako asociativne pole, ak existuje pouzivatel $username s heslom $pass, inak vrati FALSE
function over_pouzivatela($mysqli, $username, $pass) {
	if (!$mysqli->connect_errno) {

		$sql = "SELECT * FROM film_pouzivatelia WHERE prihlasmeno='$username' AND heslo=MD5('$pass')";  

		if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {
			$row = $result->fetch_assoc();
			$result->free();
			return $row;
		} else {
			// dopyt sa NEpodarilo vykonať, resp. používateľ neexistuje!
			return false;
		}
	} else {
		// NEpodarilo sa spojiť s databázovým serverom!
		echo '<p class="chyba">NEpodarilo sa spojiť s databázovým serverom!</p>';
		return false;
	}
}


function zrus_rezervaciu($mysqli, $idu, $idr ) {	// $idu - ID používatela,  $idr = ID rezervacie
	if (!$mysqli->connect_errno) {
		// vyberie miesta z rezervácie 
		$sql = "SELECT * FROM film_rezervacie WHERE id_pouz='$idu' AND id_rezer='$idr' ";
		if (($result = $mysqli->query($sql)) && ($row = $result->fetch_assoc()) ) {
			$miesta = $row['miesta'];
			
			$miesta = explode(";", $miesta);
			
		}else{
			echo '<p class="chyba">Rezervácia neexistuje</p>';
			return false;
		}
		$sql = "SELECT * FROM film_premietanie";
		// vyberie volne miesta 
		if (($result = $mysqli->query($sql)) && ($row = $result->fetch_assoc()) ) {
			$volne = $row['miesta'];
			
			$volne = explode(";", $volne);
			
		}

		$volne = (array_merge($miesta,$volne));
		// vytvorý nové volné miesta, a nahrá ich do DB
		$volne = implode(";", $volne);
		$sql = "UPDATE film_premietanie SET film_premietanie.miesta='$volne'";
		if ($result = $mysqli->query($sql)) {
	    //echo '<p class="ok">Vrátené miesta.</p>'. "\n"; 
	 	} else {
			echo '<p class="chyba">Nastala chyba pri vrátený miest';
			return false;
	  }

	   // Zmaže rezerváciu  
		$sql = "DELETE FROM film_rezervacie WHERE id_pouz='$idu' AND id_rezer='$idr' ";
		if ($result = $mysqli->query($sql)) {
			if ($mysqli->affected_rows > 0) {
				echo '<p class="ok"><strong>Rezervácia bola zrušená</strong></p>';
			} else {
				echo '<p class="chyba">Rezervácia neexistuje</p>';
			}
		} else {
			echo '<p class="chyba">Rezerváciu sa NEpodarilo zrušiť z databázy</p>';
		}
	}
	else{
		echo '<p class="chyba">NEpodarilo sa spojiť s databázovým serverom!</p>';
		return false;
	}
}


// zmeni heslo $pass pouzivatelovi s id cislom $id
function zmen_heslo($mysqli, $id, $pass) {
	if (!$mysqli->connect_errno) {
	  $sql="UPDATE film_pouzivatelia SET heslo=MD5('$pass') WHERE id_pouz='$id'";   
		if ($result = $mysqli->query($sql)) {
      echo '<p class="ok">Heslo bolo zmenené.</p>'. "\n"; 
    } else {
      echo '<p class="chyba">Nastala chyba pri zmene hesla.</p>'. "\n"; 
		}
	} else {
		echo '<p class="chyba">NEpodarilo sa spojiť s databázovým serverom!</p>';
	}
}


//pridá pouzivatela do databáze
function pridaj_pouzivatela($mysqli, $prihlasmeno, $heslo, $meno, $priezvisko, $email) {
	if (!$mysqli->connect_errno) {
		$prihlasmeno = $mysqli->real_escape_string($prihlasmeno);
		$heslo = $mysqli->real_escape_string($heslo);
		$meno = $mysqli->real_escape_string($meno);
		$priezvisko = $mysqli->real_escape_string($priezvisko);
		$email = $mysqli->real_escape_string($email);
		$sql = "INSERT INTO film_pouzivatelia SET prihlasmeno='$prihlasmeno', heslo=MD5('$heslo'), meno='$meno', priezvisko='$priezvisko', email='$email'"; // dopyt vkadanie pouzivatela do db
		if ($result = $mysqli->query($sql)) {
	    echo '<p class="ok">Používateľ bol pridaný.</p>'. "\n"; //OK
			return true;
	 	} else {
			echo '<p class="chyba">Nastala chyba pri pridávaní používateľa';
			return false;
	  	}
	} else {
		echo '<p class="chyba">NEpodarilo sa spojiť s databázovým serverom!</p>';
		return false;
	}
}


//upravenie informácií pouzivatela
function uprav_pouzivatela($mysqli, $prihlasmeno, $meno, $priezvisko, $email, $admin ,$id) {
	if (!$mysqli->connect_errno) {
		$prihlasmeno = $mysqli->real_escape_string($prihlasmeno);
		$meno = $mysqli->real_escape_string($meno);
		$priezvisko = $mysqli->real_escape_string($priezvisko);
		$email = $mysqli->real_escape_string($email);
		$admin = $mysqli->real_escape_string($admin);
		
		$sql = "UPDATE film_pouzivatelia SET prihlasmeno='$prihlasmeno', meno='$meno', priezvisko='$priezvisko', admin='$admin' ,email='$email' WHERE `film_pouzivatelia`.`id_pouz` = $id;";
		if ($result = $mysqli->query($sql)) {
			
	    echo '<p class="ok">Informácie boli upravené.</p>'. "\n";  //OK
			return true;
	 	} else {
			echo '<p class="chyba">Nastala chyba pri úpravené používateľa';
			return false;
	  }
	} else {
		echo '<p class="chyba">NEpodarilo sa spojiť s databázovým serverom!</p>';
		return false;
	}
}


//vypis ponuky všetkých filmov
function vypis_filmy($mysqli)
{
	
	include('usporiadanie.php'); //usporiadanie filmov

	if (!$mysqli->connect_errno) {
		$sql = "SELECT * FROM film_zoz ";
		if (isset($_POST['nazov2'])) $sql .= 'ORDER BY nazov DESC'; 
		elseif (isset($_POST['rok1'])) $sql .= 'ORDER BY `film_zoz`.`rok` ASC'; 
		elseif (isset($_POST['rok2'])) $sql .= 'ORDER BY `film_zoz`.`rok` DESC';
		elseif (isset($_POST['nazov1'])) $sql .= 'ORDER BY nazov ASC';
		
		


		if ($result = $mysqli->query($sql)) {
            while ($row = $result->fetch_assoc()) {
				echo '<figure>';
				echo '<figcaption>' . '<a href="filmoteka.php?film=' . $row['nazov'] . '">' . $row['nazov'] . '</a>' .'</figcaption>';
				echo "<img src='" . $row['foto'] . "'" . 'alt=' . $row['nazov'] . " width='270' height='320'>";
				echo '<figcaption>' . $row['imdb'] .'</figcaption>';
				echo '<figcaption>' . $row['rok'] .'</figcaption>';
				echo '<figcaption>' . $row['reziser'] .'</figcaption>';
				echo '</figure>';
            }
            $result->free();
        } elseif ($mysqli->errno) {
            echo '<p class="chyba">NEpodarilo sa vykonať dopyt! (' . $mysqli->error . ')</p>';
        }
	}
	else{
		echo '<p class="chyba">NEpodarilo sa spojiť s databázovým serverom!</p>';
		return false;
	}
}


function vypis_film($mysqli, $id) {
	if (!$mysqli->connect_errno) {
		$id = $mysqli->real_escape_string($id);
		$sql = "SELECT * FROM film_zoz WHERE nazov='$id'";
		if (($result = $mysqli->query($sql)) && ($row = $result->fetch_assoc()) ) {
			echo '<table border="1">';
			echo "<tr><th>ID filmu</th><td>{$row['id_film']}</td></tr>\n";
			echo "<tr><th>názov filmu</th><td>{$row['nazov']}</td></tr>\n";
			echo "<img src='" . $row['foto'] . "'" . 'alt=' . $row['nazov'] . " width='500' height='500'>";
			echo "<tr><th>popis</th><td>{$row['popis']}</td></tr>\n";
			echo "<tr><th>reziser filmu</th><td>{$row['reziser']}</td></tr>\n";
			echo "<tr><th>IMDB rating</th><td>{$row['imdb']}</td></tr>\n";
			echo '</table>';
			//admin môže film upraviť alebo nastaviť ako premietanie
			if (isset($_SESSION['admin']) && $_SESSION['admin']){
				echo '<br>';
			echo  '<a href="upravfilm.php?film=' . $row['nazov'] . '">' . "Uprav" . '</a>';
			echo '<br>';
			echo  '<a href="filmoteka.php?prem=' . $row['id_film'] . '">' . "Nastav ako priemietanie" . '</a>';
			}
		} else {
			// dopyt sa NEpodarilo vykonať!
			echo '<p class="chyba">NEpodarilo sa získať údaje z databázy</p>' . $mysqli->error ;
		}
	}
	else {
        echo '<p class="chyba">NEpodarilo sa spojiť s databázovým serverom!</p>';
        return false;
    }
}


//vypíše možonost rezervácie miest pomocov chceckboxov, množstvo sedadiel treba upraviť v udaje.php
//ukladá mista, dátum rezervácie a id pozívatela do `film_rezervacie`

function rezervacia($mysqli, $riadok, $stlpec){
	if (!$mysqli->connect_errno) {
		$sql = "SELECT * FROM `film_premietanie`";
		if (($result = $mysqli->query($sql)) && ($row = $result->fetch_assoc()) ) {
			$volne = explode(";", $row['miesta']); // zistý ktoré miesta sú volné 
		} else {
			// dopyt sa NEpodarilo vykonať!
			echo '<p class="chyba">NEpodarilo sa získať údaje z databázy</p>' . $mysqli->error ;
			return false;
		}
		$poc = 1;
		echo '<table class="center">';
		for ($x = 0; $x < $riadok; $x++) {
			echo  '<tr>';
			for ($y = 0; $y < $stlpec; $y++) {
				if (in_array($poc, $volne)){ //ak miesto nieje volné tak checkbox nie nie možné odoslať
					echo  '<td>' . "<input type='checkbox' name='checkboxvar[]' value=" . "$poc" . ">" . $poc . "<br>" . '</td>';
				}
				else{
					echo  '<td>' . "<input type='checkbox' disabled name='checkboxvar[]' value=" . "$poc" . ">" . $poc . "<br>" . '</td>';
				}
				$poc += 1;
			} 
			echo  '</tr>';
		}
		echo '</table>';
	}else {
        echo '<p class="chyba">NEpodarilo sa spojiť s databázovým serverom!</p>';
        return false;
    }
}

//nastavý film ako premietanie(zresetuje všetky miesta a zmaže predchádzajúce rezervácie)
//volné miesta sú v tabulke "film_premietanie" kde sú uložené ako string ktorý delí ";"
//pri rezervácií sa tento string zmenšuje
//pri zmene počtu sedadiel (v udaje.php) treba nastaviť nové premietanie (pridané sedadlá budú obsadené, lebo sa nenachádzajú v "film_premietanie.miesta" )

function nastav_premietanie($mysqli, $id, $sedadla, $datum, $cas){
	if (!$mysqli->connect_errno) {
	$poc = 1;
	$str = "";
	for ($x = 0; $x < $sedadla - 1; $x++) {
		$str .= "$poc" . ";";
		$poc += 1;
	}
	$str .= "$poc";
	//if (!$mysqli->connect_errno) {
		$sql = "UPDATE film_premietanie, film_zoz SET film_premietanie.miesta='$str', film_premietanie.datum='$datum', film_premietanie.cas='$cas' ,film_premietanie.id_film = film_zoz.id_film WHERE film_zoz.id_film =$id"; // definuj dopyt
		if ($result = $mysqli->query($sql)) {
	    echo '<p class="ok">Premietanie bolo upravené.</p>'. "\n";
	 	} else {
			echo '<p class="chyba">Nastala chyba pri nastavení premietania';
			return false;
	  }
	  $sql = "DELETE FROM `film_rezervacie`";
	  $mysqli->query($sql);
	//}
}else {
	echo '<p class="chyba">NEpodarilo sa spojiť s databázovým serverom!</p>';
	return false;
}
}


// vypíše info o premietaní
function premietanie_info($mysqli){
	if (!$mysqli->connect_errno) {
		$sql = "SELECT * FROM `film_premietanie`, film_zoz WHERE `id_prem` = 1 AND film_premietanie.id_film = film_zoz.id_film";
		if (($result = $mysqli->query($sql)) && ($row = $result->fetch_assoc()) ) {
			$datum=date_create($row['datum']);    // string na datum 
			echo '<section>';
			echo '<h2>' . $row['nazov'] . '</h2>';
			echo '<h2>' . date_format($datum, "d.m.Y") . '</h2>';  // datum format d.m.Y
			echo '<h2>' . $row['cas'] . '</h2>';
			echo '<table border="1">';
			echo "<tr><th>ID filmu</th><td>{$row['id_film']}</td></tr>\n";
			echo "<tr><th>Názov filmu</th><td>{$row['nazov']}</td></tr>\n";
			echo "<img src='" . $row['foto'] . "'" . 'alt=' . $row['nazov'] . " width='550' height='550'>";
			echo "<tr><th>Popis</th><td>{$row['popis']}</td></tr>\n";
			echo "<tr><th>Reziser filmu</th><td>{$row['reziser']}</td></tr>\n";
			echo "<tr><th>IMDB rating</th><td>{$row['imdb']}</td></tr>\n";
			echo '</table>';
			echo '<h2>' . $row['nazov'] . '</h2>';
			echo '<h2>' . date_format($datum, "d.m.Y") . '</h2>';   // datum format d.m.Y
			echo '<h2>' . $row['cas'] . '</h2>';
			echo '</section>';
		
		}else {
			echo '<p class="chyba">NEpodarilo sa získať údaje z databázy</p>' . $mysqli->error ;
		}
	}
	else {
        return false;
    }
}

//rezervuje miesto
function rezervuj($mysqli, $miesta, $id){
	$miesta = implode(";", $miesta);
	$datum = date("Y-m-d H:i:s");
	$sql = "SELECT * FROM `film_premietanie`, film_zoz WHERE `id_prem` = 1 AND film_premietanie.id_film = film_zoz.id_film";
	// získa informácie o filme 
	if (($result = $mysqli->query($sql)) && ($row = $result->fetch_assoc()) ) {
		$nazov = $row['nazov'];
		$id_film = $row['id_film'];
	}else {
		echo '<p class="chyba">NEpodarilo sa získať údaje z databázy</p>' . $mysqli->error ;
	}
	if (!$mysqli->connect_errno) {
	$sql = "INSERT INTO film_rezervacie SET  miesta='$miesta', datum='$datum', id_film='$id_film', id_pouz='$id'";
		if ($result = $mysqli->query($sql)) {
	    	//echo '<p>rezervacia bola pridana.</p>'. "\n"; 
	 	} else {
			echo '<p class="chyba">Nastala chyba pri pridávaní rezervácie';
			return false;
	  		}
		 } else {
		echo '<p class="chyba">NEpodarilo sa spojiť s databázovým serverom!</p>';
		return false;
		}


		$sql =	"SELECT * FROM `film_premietanie` WHERE `id_prem` = 1";
		if (($result = $mysqli->query($sql)) && ($row = $result->fetch_assoc()) ) {
			$volne = $row['miesta'];
		}else {
			echo '<p class="chyba">NEpodarilo sa získať údaje z databázy</p>' . $mysqli->error ;
			return false;
		}

		$miesta = explode(";", $miesta);
		$volne = explode(";", $row['miesta']);
		$volne = array_diff( $volne, $miesta);
		$volne = implode(";", $volne);

		//vezme miesta a odoberie tie ktoré budú rezervácie a potom volné miesta nahrá do DB

		$sql = "UPDATE film_premietanie SET film_premietanie.miesta='$volne' WHERE id_prem =1";
		if ($result = $mysqli->query($sql)) {
	    echo '<section><p class="ok">Rezervácia bola úspešne pridaná.</p></section>'. "\n"; 
			return true;
	 	} else {
			echo '<p class="chyba">Nastala chyba pri pridávaní rezervácie';
			return false;
	  }
}
?>
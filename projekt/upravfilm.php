<?php

include('db.php');
include('udaje.php');
include('funkcie.php');
hlavicka($nadpis);
include('navigacia.php');
?>

<section>
<h1>Uprav film</h1>
<?php 
//Uprava informacií o filme
if (isset($_SESSION['admin']) && $_SESSION['admin']) {
	
	$id=$_GET['film'];

if(!kontrola_film($id, $mysqli)){
	echo '<p class="chyba">Chybné ID</p>';
}
else{
	if (!$mysqli->connect_errno) {
		$id = $mysqli->real_escape_string($id);
		$sql = "SELECT * FROM film_zoz WHERE `nazov` = '$id'"; // definuj dopyt
		if (($result = $mysqli->query($sql)) && ($row = $result->fetch_assoc()) ){
			$nazov=$row['nazov'];
			$rok=$row['rok'];
			$reziser=$row['reziser'];
			$popis=$row['popis'];
			$foto=$row['foto'];
			$imdb=$row['imdb'];
		}
		
		}
		else {
			echo '<p class="chyba">NEpodarilo sa získať údaje z databázy</p>' . $mysqli->error ;
		}
	
	$chyby = array();
	if (isset($_POST["posli"])) {
	if (isset($_POST['nazov'])) $nazov = osetri($_POST['nazov']); else $nazov = '';	
	if (isset($_POST['popis'])) $popis = osetri($_POST['popis']); else $popis = '';	
	if (isset($_POST['rok'])) $rok = osetri($_POST['rok']); else $rok = '';	
	if (isset($_POST['reziser'])) $reziser = osetri($_POST['reziser']); else $reziser = '';
	if (isset($_POST['imdb'])) $imdb = ($_POST['imdb']); else $imdb = '';
	

	$file_type = $_FILES['foto']['type'];
	
	$povolene = array("image/jpeg", "image/gif", "image/png");
	//kontola typu súboru
	if ($_FILES['foto']['error'] != UPLOAD_ERR_NO_FILE){
		if(!in_array($file_type, $povolene)) $chyby['foto'] = 'Nezadali ste spravny format'; //kontrola formátu pre súbor
	}
	if (!nazov_ok($nazov)) $chyby['nazov'] = 'Názov filmu nemá správnu dĺžku (3-100 znakov)';
	if (empty($nazov)) $chyby['nazov'] = 'Nezadali ste film';
	if (!popis_ok($popis)) $chyby['popis'] = 'Popis nemá aspoň 10 znakov';
	if (empty($popis)) $chyby['popis'] = 'Nezadali ste popis';
	if (!rok_ok($rok)) $chyby['rok'] = 'Rok od 1940 do 2020';
	if (empty($rok)) $chyby['rok'] = 'Nezadali ste rok';
	if (!meno_ok($reziser)) $chyby['reziser'] = 'Meno nie je v správnom formáte';
	if (empty($reziser)) $chyby['reziser'] = 'Nezadali ste meno';
	
}

if(empty($chyby) && isset($_POST["posli"])) {
	
	uprav_film($mysqli, $nazov, $popis, $rok, $reziser, isset($_FILES) ? $_FILES['foto'] : '' , $imdb, $id);
} else {
	if (!empty($chyby)) {
		echo '<p class="chyba"><strong>Chyby pri úprave filmu</strong>:<br>';
		foreach($chyby as $ch) {
			echo "$ch<br>\n";
		}
		echo '</p>';
	}

?>
	<form method="post" enctype="multipart/form-data">
		<p>Povinné položky*</p>
		<p>
		<label for="nazov">Názov filmu (2-30 znakov)*:</label>
		<input type="text" name="nazov" id="nazov" size="30" value="<?php if (isset($nazov)) echo $nazov; ?>">
		<br>
		<label for="popis">Popis (min. 10 znakov)*:</label>
		<br>
		<textarea cols="40" rows="4" name="popis" id="popis"><?php if (isset($popis)) echo $popis; ?></textarea>
		<br>
		<label for="nazov">Rok premiery(1940 do 2020)*:</label>
		<input type="text" name="rok" id="rok" size="4" value="<?php if (isset($rok)) echo $rok; ?>">
		<br>
		<label for="meno">Režisér (3-20 znakov)*:</label>
		<input type="text" name="reziser" id="reziser" size="20" value="<?php if (isset($reziser)) echo $reziser; ?>">
		<br>
		<label for="foto">Foto:</label>
		<input type="file" name="foto" id="foto">
		<br>
		<br>
		<label for="imdb">Raiting( <a href="https://www.imdb.com/plugins" target="_blank"> IMDB</a> ):</label>
		<br>
		<textarea cols="80" rows="10" name="imdb" id="imdb"><?php if (isset($imdb)) echo $imdb; ?></textarea>
		<br>
    	<input type="submit" name="posli" value="Uprav film">
		</p>  
  </form>
<?php
}
}
} else {
	echo '<p class="chyba"><strong>K tejto stránke nemáte prístup.</strong></p>'; 
}
?>	

<?php
  
?>	
</section>

<?php
include('pata.php');
?>

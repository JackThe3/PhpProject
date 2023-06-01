<?php

include('db.php');
include('udaje.php');
include('funkcie.php');
hlavicka($nadpis);
include('navigacia.php');
?>

<section>
<h1>Pridaj film</h1>
<?php 
//Pridávanie filmu
if (isset($_SESSION['user']) && $_SESSION['admin']) {

$chyby = array();
if (isset($_POST["posli"])) {
	if (isset($_POST['nazov'])) $nazov = osetri($_POST['nazov']); else $nazov = '';	
	if (isset($_POST['popis'])) $popis = osetri($_POST['popis']); else $popis = '';	
	if (isset($_POST['rok'])) $rok = osetri($_POST['rok']); else $rok = '';	
	if (isset($_POST['reziser'])) $reziser = osetri($_POST['reziser']); else $reziser = '';
	if (isset($_POST['imdb'])) $imdb = ($_POST['imdb']); else $imdb = '';

	$file_type = $_FILES['foto']['type'];
	$povolene = array("image/jpeg", "image/gif", "image/png");
	if (!nazov_ok($nazov)) $chyby['nazov'] = 'Názov tovaru nemá správnu dĺžku (3-100 znakov)';
	if (kontrola_film($nazov, $mysqli)) $chyby['existujef'] = 'Film uz existuje';
	if (empty($nazov)) $chyby['nazov'] = 'Nezadali ste názov';
	if (!popis_ok($popis)) $chyby['popis'] = 'Popis nemá aspoň 10 znakov';
	if (empty($popis)) $chyby['popis'] = 'Nezadali ste popis';
	if (!rok_ok($rok)) $chyby['rok'] = 'Rok musí byť od 1940 po' . date("Y");
	if (empty($rok)) $chyby['rok'] = 'Nezadali ste rok';
	if (!meno_ok($reziser)) $chyby['reziser'] = 'Režisér nie je v správnom formáte';
	if (empty($reziser)) $chyby['reziser'] = 'Nezadali ste režiséra';
	if ($_FILES['foto']['error'] != UPLOAD_ERR_NO_FILE){
		if(!in_array($file_type, $povolene)) $chyby['foto'] = 'Nezadali ste spravny format súboru'; //kontrola formátu pre súbor
	}
}

if(empty($chyby) && isset($_POST["posli"])) {
	
	pridaj_film($mysqli, $nazov, $popis, $rok, $reziser, isset($_FILES) ? $_FILES['foto'] : '' , $imdb);
} else {
	// kontrola chyb 
	if (!empty($chyby)) {
		echo '<p class="chyba"><strong>Chyby pri pridávaní filmu</strong>:<br>';
		foreach($chyby as $ch) {
			echo "$ch<br>\n";
		}
		echo '</p>';
	}
?>
	<p>Povinné položky*</p>
	<form method="post" enctype="multipart/form-data">
		<p>
		<label for="nazov">Názov filmu (2-30 znakov)*:</label>
		<input type="text" name="nazov" id="nazov" size="30" value="<?php if (isset($nazov)) echo $nazov; ?>">
		<br>
		<label for="popis">Popis (min. 10 znakov)*:</label>
		<br>
		<textarea cols="40" rows="4" name="popis" id="popis"><?php if (isset($popis)) echo $popis; ?></textarea>
		<br>
		<label for="nazov">Rok premiery(od 1940 do <?php echo date('Y')?>)*:</label>
		<input type="text" name="rok" id="rok" size="4" value="<?php if (isset($rok)) echo $rok; ?>">
		<br>
		<label for="meno">Režisér (3-20 znakov)*:</label>
		<input type="text" name="reziser" id="reziser" size="20" value="<?php if (isset($reziser)) echo $reziser; ?>">
		<br>
		<br>
		<label for="foto">Foto(.jpeg, .png, .gif):</label>
		<input type="file" name="foto" id="foto">
		<br>
		<br>
		<label for="imdb">Raiting( <a href="https://www.imdb.com/plugins" target="_blank"> IMDB</a> ):</label>
		<br>
		<textarea cols="80" rows="10" name="imdb" id="imdb"><?php if (isset($imdb)) echo $imdb; ?></textarea>
		<br>
    	<input type="submit" name="posli" value="Pridaj tovar">
		</p>  
  </form>
<?php
}

} else {
	echo '<p class="chyba"><strong>K tejto stránke nemáte prístup.</strong></p>'; 
}
?>	
</section>

<?php
include('pata.php');
?>

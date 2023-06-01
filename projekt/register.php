<?php

include('db.php');
include('udaje.php');
include('funkcie.php');
hlavicka($nadpis);
include('navigacia.php');
?>

<section>
<h1>Registrácia</h1>
<?php 
//Registrovanie používateľa
$chyby = array();
if (isset($_POST["posli"])) {
	$prihlasmeno = osetri($_POST['prihlasmeno']);
	$heslo = $_POST['heslo'];
	$heslo2 = $_POST['heslo2'];
	$meno = osetri($_POST['meno']);
	$priezvisko = osetri($_POST['priezvisko']);
	$email = osetri($_POST['email']);
	if (!nazov_ok($prihlasmeno)) $chyby['prihlasmeno'] = 'Prihlasovacie meno nie je v správnom formáte';
	if (kontrola_mena($prihlasmeno, $mysqli)) $chyby['existuje'] = 'Prihlasovacie meno uz existuje';
	if (empty($prihlasmeno)) $chyby['prihlasmeno'] = 'Nezadali ste prihlasovacie meno';
	if (!nazov_ok($heslo)) $chyby['heslo'] = 'Heslo nie je v správnom formáte';
	if (!nazov_ok($heslo2)) $chyby['heslo2'] = 'Heslo (znovu) nie je v správnom formáte';
	if ($heslo != $heslo2) $chyby['heslo2'] = 'Nezadali ste 2x rovnaké nové heslo';
	if (empty($heslo)) $chyby['heslo'] = 'Nezadali ste heslo';
	if (empty($heslo2)) $chyby['heslo2'] = 'Nezopakovali ste heslo';
	if (!meno_ok($meno)) $chyby['meno'] = 'Meno nie je v správnom formáte';
	if (empty($meno)) $chyby['meno'] = 'Nezadali ste meno';
	if (!meno_ok($priezvisko)) $chyby['priezvisko'] = 'Priezvisko nie je v správnom formáte';
	if (empty($priezvisko)) $chyby['priezvisko'] = 'Nezadali ste priezvisko';
	if ($email != ""){
		if (kontrola_email($email, $mysqli)) $chyby['existujeemail'] = 'Email uz existuje';
	}
	if (sprava_email($email)) $chyby['neemail'] = 'Email nie je v správnom formáte';
}
//kontrola chyb
if(empty($chyby) && isset($_POST["posli"])) {
	pridaj_pouzivatela($mysqli, $prihlasmeno, $heslo, $meno, $priezvisko, $email);
} else {
	if (!empty($chyby)) {
		echo '<p class="chyba"><strong>Chyby vo formulári</strong>:<br>';
		foreach($chyby as $ch) {
			echo "$ch<br>\n";
		}
		echo '</p>';
	}
?>

	<p>Povinne polozky*</p>
	<form method="post">
		<p><label for="prihlasmeno">Prihlasovacie meno (2-20 znakov)*:</label> 
		<input name="prihlasmeno" type="text" size="20" maxlength="20" id="prihlasmeno" value="<?php if (isset($prihlasmeno)) echo $prihlasmeno; ?>" ><br>
		<label for="heslo">Heslo (5-30 znakov)*:</label> 
		<input name="heslo" type="password" size="30" maxlength="30" id="heslo"> 
		<br>
		<label for="heslo2">Heslo (znovu)*:</label> 
		<input name="heslo2" type="password" size="30" maxlength="30" id="heslo2">
		<br> 
		<label for="meno">Meno (3-20 znakov)*:</label>
		<input type="text" name="meno" id="meno" size="20" value="<?php if (isset($meno)) echo $meno; ?>">
		<br>
		<label for="priezvisko">Priezvisko (3-30 znakov)*:</label>
		<input type="text" name="priezvisko" id="priezvisko" size="30" value="<?php if (isset($priezvisko)) echo $priezvisko; ?>">
		<br>
		<label for="email">Email*:</label>
		<input type="text" name="email" id="email" size="30" value="<?php if (isset($email)) echo $email; ?>">
		<br>
		</p>
		<p>
			<input name="posli" type="submit" id="posli" value="Pridaj používateľa">
		</p>
	</form>
<?php
}

  
?>	
</section>

<?php
include('pata.php');
?>

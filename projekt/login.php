<?php
include('db.php');
include('udaje.php');
include('funkcie.php');
hlavicka($nadpis);
include('navigacia.php');

?>

<section>
<h1>Login</h1>
<?php
if (isset($_POST["prihlasmeno"]) && isset($_POST["heslo"]) && $row = over_pouzivatela($mysqli, $_POST["prihlasmeno"], $_POST["heslo"])) {
	$_SESSION['id_pouz'] = $row['id_pouz'];
	$_SESSION['user'] = $row['prihlasmeno'];
	$_SESSION['meno'] = $row['meno'];
	$_SESSION['priezvisko'] = $row['priezvisko'];
	$_SESSION['admin'] = $row['admin'];
	header("refresh: 0");


}
elseif (isset($_POST['odhlas'])) { // bol odoslany formular s odhlasenim
	session_unset();
	session_destroy();
	header("refresh: 0");
}


if (isset($_SESSION['user'])) {
?>
<p>Vitajte v systéme <strong><?php echo $_SESSION['user']; ?></strong>.</p>
<p>Ak chceš, môžeš si <a href="zmen_heslo.php">zmeniť heslo</a>.</p>
<p><strong>Rezervácie:</strong></p>
<?php

vypis_rezervacie($mysqli, $_SESSION['id_pouz'], $_SESSION['admin']);

?>

<?php
	if ($_SESSION['admin']) {
		echo '<p>Ak chceš, môžeš <a href="pridaj.php">Pridaj film</a>.</p>';
		echo '<p><strong>Použávatelia:</strong></p>';
		vypis_uzivatelov($mysqli);

		
	}
?>
<form method="post"> 
  <p> 
    <input name="odhlas" type="submit" id="odhlas" value="Odhlás ma"> 
  </p> 
</form> 
<?php

}  else {
?>
	<form method="post">
		<p><label for="prihlasmeno">Prihlasovacie meno:</label> 
		<input name="prihlasmeno" type="text" size="30" maxlength="30" id="prihlasmeno" value="<?php if (isset($_POST["prihlasmeno"])) echo $_POST["prihlasmeno"]; ?>" ><br>
		<label for="heslo">Heslo:</label> 
		<input name="heslo" type="password" size="30" maxlength="30" id="heslo"> 
		</p>
		<p>
			<input name="submit" type="submit" id="submit" value="Prihlás ma">
		</p>
		<?php echo '<p>Ak ešte nemáš účet. Môžeš <a href="register.php">sa zaregistrovať</a>.</p>'; ?>
	</form>
<?php
}

?>
</section>

<?php
include('pata.php');
?>

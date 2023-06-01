<?php

include('db.php');
include('udaje.php');
include('funkcie.php');
hlavicka($nadpis);
include('navigacia.php');
?>

<section>
<h1>Uprav</h1>
<?php 
//Upravovanie informácií uživateľa
$sql = "SELECT MAX(id_pouz) FROM film_pouzivatelia";	
if (($result = $mysqli->query($sql)) && ($row = $result->fetch_assoc()) ){  // 
	$max = $row['MAX(id_pouz)'];
	$result -> free_result();
}
if (isset($_SESSION['admin']) && $_SESSION['admin']) {
	if (!(isset($_GET['user']) && ((int)$_GET['user'] > 0) && ((int)$_GET['user'] <= $max))) {
		echo '<p class="chyba">Chybné ID</p>';
	}
	else{
	$id=$_GET['user'];

	if (!$mysqli->connect_errno) {
		$id = $mysqli->real_escape_string($id);
		$sql = "SELECT * FROM film_pouzivatelia WHERE `id_pouz` = $id";
		if (($result = $mysqli->query($sql)) && ($row = $result->fetch_assoc()) ){
			$pmeno=$row['prihlasmeno'];
			$meno=$row['meno'];
			$priezvisko=$row['priezvisko'];
			$email=$row['email'];
			$admin=$row['admin'];
		}
		}
		else {
			echo '<p class="chyba">NEpodarilo sa získať údaje z databázy</p>' . $mysqli->error ;
		}

	$chyby = array();
	if (isset($_POST["posli"])) {
		$prihlasmeno = osetri($_POST['prihlasmeno']);
		$meno = osetri($_POST['meno']);
		$priezvisko = osetri($_POST['priezvisko']);
		$email = osetri($_POST['email']);
		if (isset($_POST['admin'])){
			$admin = osetri($_POST['admin']);
		}
		//

		if (empty($prihlasmeno)) $chyby['prihlasmeno'] = 'Nezadali ste prihlasovacie meno';
		if (!meno_ok($meno)) $chyby['meno'] = 'Meno nie je v správnom formáte';
		if (empty($meno)) $chyby['meno'] = 'Nezadali ste meno';
		if (!meno_ok($priezvisko)) $chyby['priezvisko'] = 'Priezvisko nie je v správnom formáte';
		if (empty($priezvisko)) $chyby['priezvisko'] = 'Nezadali ste priezvisko';
		if (sprava_email($email)) $chyby['neemail'] = 'nespravny format email';
	}


	if(empty($chyby) && isset($_POST["posli"])) {
		uprav_pouzivatela($mysqli, $prihlasmeno, $meno, $priezvisko, $email, $admin, $id);
	} else {
		// ak bol odoslaný formulár, ale neboli zadané alebo boli zle zadané všetky povinné položky 
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
			<p><label for="prihlasmeno">Prihlasovacie meno (5-20 znakov)*:</label> 
			<input name="prihlasmeno" type="text" size="20" maxlength="20" id="prihlasmeno" value="<?php if ($pmeno) echo $pmeno; ?>" ><br>
			
			<label for="meno">Meno (3-20 znakov)*:</label>
			<input type="text" name="meno" id="meno" size="20" value="<?php if ($meno) echo $meno; ?>">
			<br>
			<label for="priezvisko">Priezvisko (3-30 znakov)*:</label>
			<input type="text" name="priezvisko" id="priezvisko" size="30" value="<?php if ($priezvisko) echo $priezvisko; ?>">
			<br>
			<label for="email">Email*:</label>
			<input type="text" name="email" id="email" size="30" value="<?php if (isset($email)) echo $email; ?>">
			<br>
			<?php if ( $admin == 0){
				echo 'Práva administrátora: <input type="radio" name="admin" id="admin_ano" value="1"';
				if (isset($admin) && $admin==1) echo ' checked'; 
				echo '> <label for="admin_ano">áno</label>';
				echo '<input type="radio" name="admin" id="admin_nie" value="0"';
				if (empty($admin)) echo ' checked'; 
				echo '> <label for="admin_nie">nie</label>';
			}
			else{
				$admin = "1";
			} 
			?> 
		</p>
		<p>
			
				<input name="posli" type="submit" id="posli" value="Uprav používateľa">
			</p>
		</form>

<?php
	}
}
	}
	else{
		echo '<p class="chyba">NEmáš práva administrátora.</p>';
	}


  
?>	
</section>

<?php
include('pata.php');
?>

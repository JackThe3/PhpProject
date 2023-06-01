<?php
include('db.php');
include('udaje.php');
include('funkcie.php');
hlavicka($nadpis);
include('navigacia.php');
?>

<section>
<h1>Filmotéka</h1>
<?php
$ero = array();
$date = date('Y-m-d');
	if(isset($_GET['film'])){
		$id=$_GET['film'];
		vypis_film($mysqli, $id);
	}
	else if(isset($_GET['prem']) && $_SESSION['admin']){
		$id=$_GET['prem'];
		echo '<p>Zvoľte čas a deň premietania:</p>';
		if(isset($_POST['submit'])){
			
			$datum = $_POST['datum'];
			$cas = $_POST['cas'];
			if (empty($datum)) $ero['datum'] = 'Nezadali ste dátum premietania';
			if (empty($cas)) $ero['cas'] = 'Nezadali ste čas premietania';
		}
		if(empty($ero) && isset($_POST["submit"])) {
			nastav_premietanie($mysqli, $id, $sedadla, $datum, $cas);
		} else {
			if (!empty($ero)) {
				echo '<p class="chyba"><strong>Chyby vo formulári</strong>:<br>';
				foreach($ero as $ch) {
					echo "$ch<br>\n";
				}
				echo '</p>';
			}
		}
		?>
		<p><strong>Premietanie</strong></p>
		<form method="post">
		<label for="datum">Dátum:</label>
		<input type="date" id="datum" name="datum" value = "<?php if(isset($datum))echo $datum ?>" min=<?php echo "'$date'"?>;>
		<br> 
		<label for="cas">Select a time:</label>
		<input type="time" id="cas" name="cas" value = "<?php echo $cas ?>" >
		<br><p>
			<input name="submit" type="submit" id="submit" value="Premietanie">
		</p>
		</form>
		<?php
	}
	else{
		vypis_filmy($mysqli);
	}
	

?>
</section>
<?php
include('pata.php');
?>

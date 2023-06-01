<?php
include('db.php');
include('udaje.php');
include('funkcie.php');
hlavicka($nadpis);
include('navigacia.php');
?>
<section>
<?php
//Rušenie rezervácie
if (isset($_SESSION['admin']) && ($_SESSION['admin']) && isset($_GET['poz']) && isset($_GET['rez']) ) { //ak je admin	
	zrus_rezervaciu($mysqli, $_GET['poz'], $_GET['rez']);
}
elseif(isset($_SESSION['id_pouz'])  && isset($_GET['poz']) && isset($_GET['rez']) && $_SESSION['id_pouz'] == $_GET['poz']){ // ak je pouzivatel
	zrus_rezervaciu($mysqli, $_GET['poz'], $_GET['rez']);
}
else {
	echo '<p class="chyba"><strong>K tejto stránke nemáte prístup.</strong></p>'; 
}
?>
</section>
<?php 
include('pata.php'); 
?>

<?php
include('udaje.php');
include('db.php');
include('funkcie.php');
hlavicka('FMFI Kino');
include('navigacia.php');

?>
<section>
<h2>Premietanie</h2>
</section>

<?php
    $error = array();
    if (isset($_POST['reser']) && ($_POST['reser'] == "Rezervuj") && !isset($_POST['checkboxvar'])) $error['zadaj'] = 'Treba vybrat miesto';
    
    if(empty($error) && isset($_POST['checkboxvar'])){
        rezervuj($mysqli, $_POST['checkboxvar'], $_SESSION['id_pouz']);
    }else{
        premietanie_info($mysqli); // vypíše info o premietanom filme
        if (!empty($error)) {
            echo '<section><p class="chyba"><strong>Chyby pri rezervácií miesta</strong>:<br>';
            foreach($error as $ch) {
                echo "$ch<br>\n";
            }
            echo '</p></section>';
        }
    
?>

<section class="platno">

<P>Plátno</P>
<hr>
</section>
<section class="platno">
<form method="post" class="platno">
<?php 
rezervacia($mysqli, $rad, $sedadla_v_rade);
 
?>
<br>
<?php
    if(isset($_SESSION['user'])){
       echo '<input type="submit" name="reser" value="Rezervuj">';
    }
    else{
        echo '<p>Pre rezervaciu je potrebne sa prihlasit</p>';
    }
}

?> 

</form>
<?php
include('pata.php');
?>

<?php  session_start();
?>
<nav>
<a href="index.php">index</a> 
<a href="premietanie.php">Premietanie</a>
<a href="filmoteka.php">Filmoteka</a>

<?php
if(isset($_SESSION['user'])){
    echo '<a href="login.php">' . $_SESSION['user'] . '</a>';
}else{
    
?>
    <a href="login.php">Login</a>
<?php } ?>

</nav>
<?php
ini_set("default_charset", "UTF-8");
include "db_init.php";
if (!isset($_SESSION))
session_start();
if (!isset($_SESSION['Login'])) {
  header("Location: index.php");
}
$inactive = 300; 
if (isset($_SESSION['timeout'])) {
$session_life = time() - $_SESSION['timeout'];

if($session_life > $inactive)
{  session_unset();
  session_destroy(); 
  header("Location: index.php");     
}
}


?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<title>Banka</title>
<link rel="stylesheet" href="styles/layout.css" type="text/css">
</head>
<body>
<div class="wrapper row1">
   <div id="logout">
   <?php 
  $log = $_SESSION['Login']; 
  $query = "SELECT * FROM zamestnanec WHERE PrihlasovacieMeno = '".mysql_real_escape_string($log)."'";
  $result = @mysql_query($query,$db);
  if (!($result))
    echo "fasa";
  $account = mysql_fetch_array($result, MYSQL_ASSOC)
  ?>
  <p style=" font-weight:bold;">Čas do odhlásenia : <span id = "timer" style="color:white;font-weight:normal;">00:04:59</span><br> Prihlásený použivateľ : <span id = "timer" style="color:white;font-weight:normal;"> <?php echo $account['MenoZamestnanca']; echo " ".$account['PriezviskoZamestnanca']." (".$account['PrihlasovacieMeno'].")";?></span></p> 
  <form method="POST"><input type="submit" name="logout" value="Odhlasit sa" style="height:20px; width: 90px; margin-left:5px;"></form>
  <?php if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php"); 
  }
    ?>
    <script>
  var seconds = Math.floor(59);
  var minutes = Math.floor(4);
  var i = 0;
  var x = setInterval(function() {
    var sec = Math.floor(seconds);
     if (i != 0) {
      if (sec == 59)
      {
        minutes = Math.floor(minutes - 1);
        min = Math.floor(minutes);
      }
      else min = Math.floor(minutes);
    }
    else
    min = Math.floor(minutes);
    seconds = Math.floor(seconds-1);
    if (sec < 10) {
      document.getElementById("timer").innerHTML="00:0"+min +":0" + sec;
      
    }
    else
    document.getElementById("timer").innerHTML = "00:0"+min +":" + sec;;
    if (sec == 0)
      seconds = Math.floor(59);
    i = Math.floor(i + 1);
  },1000);
  </script>
  </div>
  <script type="text/javascript">
setTimeout(onUserInactivity, 1000 * 300)
function onUserInactivity() {
   window.location.href = "index.php"
}
</script> 
  <header id="header" class="clear">
    <div id="hgroup">
      <h1><a href="#">Banka Ebejam</a></h1>
      <h2>Elterngeld group</h2>
    </div>
    <form action="#" method="post">
    </form>
    <nav>
      <ul>
        <li><a href="empTransfer.php">Prevod</a></li>
        <li><a href="empSelect.php">Výber</a></li>
        <li><a href="empInsert.php">Vklad</a></li>
        <li><a href="addManager.php">Pridať disponenta</a></li>
        <li><a href="addAcc.php">Pridať účet</a></li>
        <li class="last"><a href="addCard.php">Pridať kartu k účtu</a></li>
      </ul>
    </nav>
  </header>
</div>
<!-- content -->
<div class="wrapper row2">
  <div id="container" class="clear">
    <div id="forms">
    <!-- content body -->
    <form action="" method="POST" class="regForms">
    <table>
    <tr><td>Číslo účtu:</td><td><input type="text" name="CU" value = <?php if(isset($_POST['CU'])) { echo $_POST['CU']; }?>></td></tr>
    <?php 
      if (empty($_POST['CU']) && isset($_POST['submit'])) {
      ?> <tr><td></td><td><font size="3" color="red">Prosim vyplnte pozadovany udaj</font></td></tr>
      <?php }  ?>
    <tr><td>Prihlasovacie meno klienta:</td><td><input type="text" name="MK" value = <?php if(isset($_POST['MK'])) { echo $_POST['MK']; }?>></td></tr>
    <?php 
      if (empty($_POST['MK']) && isset($_POST['submit'])) {
      ?> <tr><td></td><td><font size="3" color="red">Prosim vyplnte pozadovany udaj</font></td></tr>
      <?php }  ?>
    <tr><td>Prihlasovacie meno zamestnanca:</td><td><input type="text" name="MZ" value = <?php if(isset($_POST['MZ'])) { echo $_POST['MZ']; }?>></td></tr>
    <?php 
      if (empty($_POST['MZ']) && isset($_POST['submit'])) {
      ?> <tr><td></td><td><font size="3" color="red">Prosim vyplnte pozadovany udaj</font></td></tr>
      <?php }  ?>
    <tr><td>Vkladaná čiastka:</td><td><input type="text" name="VC" placeholder="EUR"></td></tr>
    <tr><td>Miesto vkladu:</td><td><input type="text" name="MV" value = <?php if(isset($_POST['MV'])) { echo $_POST['MV']; }?>></td></tr>
    <?php 
      if (empty($_POST['MV']) && isset($_POST['submit'])) {
      ?> <tr><td></td><td><font size="3" color="red">Prosim vyplnte pozadovany udaj</font></td></tr>
      <?php }  ?>
    <tr><td><input type="submit" name="submit" value="Odoslať" style="height: 30px; width: 150px"></td></tr></td></tr>
    </table>
   </form>
   </div>
  </div>
</div>   
<?php

  if ((!(empty($_POST["CU"]))) && (!(empty($_POST["MK"]))) && (!(empty($_POST["MZ"]))) && (!(empty($_POST["VC"]))) && (!(empty($_POST["MV"])))) {
    $query = "SELECT * FROM ucet WHERE id_cislouctu = '".mysql_real_escape_string($_POST['CU'])."'";
    $result = @mysql_query($query,$db);
    $resultData = mysql_fetch_array($result, MYSQL_ASSOC);
    if(!($result))
      echo "<script type='text/javascript'>alert('Nesprávny účet!')</script>";
    else{
      $query = "SELECT * FROM klient WHERE PrihlasovacieMeno = '".mysql_real_escape_string($_POST['MK'])."'";
      $result = @mysql_query($query,$db);
      $resultData = mysql_fetch_array($result, MYSQL_ASSOC);
      $query2 = "SELECT * FROM disponent WHERE PrihlasovacieMeno = '".mysql_real_escape_string($_POST['MK'])."'";
      $result2 = @mysql_query($query,$db);
      if((!($result)) && !($result2)){
       echo "<script type='text/javascript'>alert('Neexistujúci klient/disponent!')</script>";
      }
      else{
        $query = "SELECT * FROM zamestnanec WHERE PrihlasovacieMeno = '".mysql_real_escape_string($_POST['MZ'])."'";
        $result = @mysql_query($query,$db);
        if(!($result))
          echo "<script type='text/javascript'>alert('Nesprávny zamestnanec!')</script>";
        else{
          $query = "UPDATE ucet SET uctovny_zostatok = uctovny_zostatok + '".mysql_real_escape_string($_POST["VC"])."' WHERE id_cislouctu = '".mysql_real_escape_string($_POST["CU"])."';";
          $result = @mysql_query($query,$db);             
          if(!($result))
           echo "<script type='text/javascript'>alert('Nesprávne údaje vkladanie sa nepodarilo1!')</script>";
          else {
           $date = date('Y-m-d');
           $query = "INSERT INTO operace(`MenoVykonavatela`, `VkladanaCiastka`, `MiestoVkladu` ,`id_Klient`, `id_ucet`, `DatumVykonania`) VALUES ('".mysql_real_escape_string($_POST["MZ"])."','".mysql_real_escape_string($_POST["VC"])."','".mysql_real_escape_string($_POST["MV"])."','".mysql_real_escape_string($_POST["MK"])."','".mysql_real_escape_string($_POST["CU"])."', '".mysql_real_escape_string($date)."');";
            $result = @mysql_query($query,$db);
            if(!($result)){
               echo "<script type='text/javascript'>alert('Nesprávne údaje vkladanie sa nepodarilo2!')</script>";
            }
            else {
               echo "<script type='text/javascript'>alert('Vklad prebehol úspešne.'); window.location.href='empInsert.php';</script>";

            }
          } 
        } 

      } 

    } 

  }
?>
<!-- Footer -->
<div class="wrapper row3">
  <footer id="footer" class="clear">
    <p class="fl_left">Copyright &copy; 2017 - All Rights Reserved</p>
    <p class="fl_right">Created by Jan Gula</a></p>
  </footer>
</div>
</body>
</html>

<?php
include_once "./userCrud.php";
session_start();
if(isset($_GET['logout']) AND $_GET['logout']==1) {
  unset($_SESSION['username']);
  unset($_SESSION['isAdmin']);
  unset($_SESSION['stuNum']);
  session_regenerate_id();
}
else if(isset($_SESSION['isAdmin']) AND isset($_SESSION['username'])) {
  header('Location: http://www.cse.msu.edu/~ortizder/proj2/account.php');
  exit;
}
else {session_regenerate_id();}
?>

<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<link href='http://fonts.googleapis.com/css?family=Roboto'
rel='stylesheet' type="text/css">

<link rel="stylesheet" type="text/css" href="./login.css"
</head>


<body>


<div id="formContainer">
  <form action="loginHandler.php" method='post'>
  User Login Manager  
  <div class="inputContainer">
    <input class="topInput" type="text" name="userid" placeholder="User ID">
    <input class="botInput" type="password" name="password" placeholder="Password">
  </div> <!-- end form container -->
  
  <div class="inputContainer">
    <input class="bigSubmit" id="loginButton" type="submit" value="Log In">
  </div>

  </form>
  <?PHP
  if ($_GET['wrong'] == 1 ) {
    echo '<p>Incorrect Username or Password</p>';
  }
  ?>
</div> <!-- end form Container -->
<hr>
</body>
</html>

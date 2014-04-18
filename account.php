<?php session_start(); 
include_once './userCrud.php';
if(!isset($_SESSION['username'])) {
  header('Location: http://www.cse.msu.edu/~ortizder/proj2/');
}

if(isset($_POST['newPassword']) and $_POST['newPassword']!='') {
  if($_POST['newPassword']!=$_POST['passwordRetype']) {
    $_SESSION['updated']=-1;
  }
  else if(validate_password($_SESSION['username'],$_POST['oldPassword'])) {
    update_password($_SESSION['username'],$_POST['newPassword']);
    $_SESSION['updated']=1;
  }
  else {
    $_SESSION['updated']=0;
  }
}

?>
<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type="text/css">

<script type="text/javascript" src="./activepage.js"></script>
<link rel="stylesheet" type="text/css" href="./login.css"></link>
</head>


<body>
<div id="nav">
  <div id="navcontent">
    <a href="schedule.php"><div>Schedule</div></a>
    <a href="account.php"><div>Account</div></a>
    <?PHP if($_SESSION['isAdmin']) {
      echo '<a href="admin.php"><div>Admin</div></a>';
    } ?>
	<a href="index.php?logout=1"><div>Log Out</div></a>
  </div>
</div> <!--End Navigation -->


<?PHP 
if(isset($_SESSION['updated']) AND $_SESSION['updated']==1) {
  echo "<p class='error'>Your Password has successfully changed.</p>";
}
else if(isset($_SESSION['updated']) AND $_SESSION['updated']==0) {
  echo "<p class='error'>Incorrect password.</p>";
}
else if(isset($_SESSION['updated']) AND $_SESSION['updated']==-1) {
  echo "<p class='error'>Paswords did not match</p>";
}

unset($_SESSION['updated']);
   
 ?>
<div id="formContainer">
  <form action="<?PHP echo $_SERVER['PHP_SELF']?>" method=post>
  Change Password 
  <div class="inputContainer">
    Old Password
    <input class="oneInput" type="password" name="oldPassword" placeholder="Old Password">

  </div> <!-- end Password container -->
 
  <div class="inputContainer">
    Enter New Password
    <input class="topInput" type="password" name="newPassword" placeholder="New Password">
	  <input class="botInput"  type="password" name="passwordRetype" placeholder="Re-Type Password">
  </div>

  <div class="inputContainer">
    <input class="bigSubmit" id="loginButton" type="submit" value="Submit">
  </div>

  </form>
</div> <!-- end form Container -->
<hr>
</body>

</html>

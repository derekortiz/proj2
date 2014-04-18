<?php session_start(); 
include_once './userCrud.php';
if(!isset($_SESSION['username'])) {
  header('Location: http://www.cse.msu.edu/~ortizder/proj2/');
}
?>

<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<link href='http://fonts.googleapis.com/css?family=Roboto'
rel='stylesheet' type="text/css">
<script type="text/javascript" src="./activepage.js"></script>

<link rel="stylesheet" type="text/css" href="./login.css">
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
<div id='content'>
	Edit Student User Information
	<table>
	  <col width="30px">
	  <col width="250px">
	  <col width="100px">
	  <col width="200px">
	  <tr><th>course#</th><th>Course Name</th><th>SequenceID</th><th>seats</th><th>MaxSeats</th></tr>
    <?PHP all_users_table(); ?>
	</table>
  <a class='resetPassword' href='adduser.php'>Add User</a>
</div> <!-- End Content-->
<hr>
</body>
</html>
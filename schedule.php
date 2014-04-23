<?php session_start(); 
include_once './userCrud.php';
include_once './courseCrud.php';

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
	<h2>Planned Classes</h3>
  <!-- Begin Schedule Output -->
  <form action="" method="post">
	<table width="725px">
	  <col width="30px">
	  <col width="250px">
	  <col width="125px">
	  <col width="75px">
    <col width="75px">
    <col width="214px">
	  <tr><th>course#</th><th>Course Name</th><th>SequenceID</th><th>seats</th><th>MaxSeats</th><th>Remove Class</th></tr>
  
    <?PHP echo
      print_schedule($_SESSION['stuNum']); ?>
	
    </table>
  </form>
  <!-- End Schedule Output -->

  <a class='resetPassword' href='addCourse.php'>Add a course</a>
</div> <!-- End Content-->
<hr>
</body>
</html>

<?php
// define the database from settings
include_once "./CrudSettings.php";

// userID queries
define("user_login_qry", "select * FROM User WHERE loginID='%s' AND
    password='%s';");

// retrieve query
define("student_find_qry","select * from Student WHERE stuName='%s';");
define("user_find_qry","select * from User WHERE loginID='%s';");

// password queries
define("validate_password_qry","select * FROM User WHERE loginID='%s';");
define("update_password_qry","update User set password='%s' where loginID='%s';");


// insert
define("student_insert_qry","INSERT INTO Student values('%s','%s');");
define("user_insert_qry","insert into User(loginID,password,isAdmin,isActive)
    VALUES ('%s','%s','%s', 1);");
define("student_user_insert_qry","INSERT INTO Student_User values('%s','%s');");

// deletes
define("user_delete_qry","delete from User where loginID='%s';");
define("student_delete_qry","delete from Student where stuNum='%s';");
define("student_user_delete_qry","delete from Student_User 
    where stuNum='%s' AND LoginID='%s';");

// updates
define("student_update_qry","update Student set stuNum='%s',stuName='%s'
    where stuNum='%s';");
define("user_update_qry","update User set loginID='%s',password='%s'
    where loginID='%s';");
define("student_user_update_qry","update Student_User
    set StuNum='%s', LoginID='%s'
    where StuNum='%s' AND LoginID='%s';");

//reads
define("student_user_qry","Select * FROM Student_User WHERE LoginID='%s';");
define("user_read_qry", 
    "select S.stuNum,stuName,U.loginID,password
    FROM User U,Student_User SU, Student S
    WHERE S.stuNum=SU.StuNum AND SU.LoginID=U.loginID
    ORDER BY S.stuNum;");
define("one_user_qry", 
    "select stuNum,stuName,loginID,password
    FROM User, Student WHERE stuNum='%s' AND loginID='%s';");

// form genertaion templates for admin/useredit pages
define("table_row", '<tr>
  <td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>
	<a class="resetPassword" href="edituserHandler.php?user=%3$s&action=reset">Reset Password</a>
	<a class="editUser" href="edituser.php?user=%3$s&sNum=%1$s">Edit</a>
	<a class="deleteUser" href="edituserHandler.php?user=%3$s&sNum=%1$s&action=delete">X</a>
	</form></td>
	</tr>'
);
define("edituser_tablerow","<tr>
	  <td><input class='userEditField' type='text' name='sNum' value='%s'</td>
		<td><input class='userEditField' type='text' name='name' value='%s'></td>
		<td><input class='userEditField' type='text' name='login' value='%s'></td>
		<td><input class='userEditField' type='text' name='pass' value='%s'></td>
	  </tr>");



// Begin function definitions +++++++++++++++++++++++++++++++++++++++

function user_delete($userID,$sNum){
	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME) or die(mysql_error());
	

  $query2 = sprintf(student_delete_qry, $sNum);
	$result2 = mysqli_query($connection, $query2);
  $query3 = sprintf(user_delete_qry, $userID);
	$result3 = mysqli_query($connection, $query3);
	if(!$result3 || !$result2 ) { 
		die("Could not delete User: " . mysqli_error($connection));
    $retVal=0;
  }
	mysqli_close($connection);
	return $retVal;
}

function add_student_user($sNum, $name, $login, $pass, $isAdmin) {
	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME) or die(mysql_error());
	$query1 = sprintf(student_insert_qry, $sNum, $name);
	$result1 = mysqli_query($connection, $query1);
  if(!$result1) { 
		die("Could not add Student: " . mysqli_error($connection));
  }
  else {
    $query2 = sprintf(user_insert_qry,$login,$pass,$isAdmin,$id);
	  $result2 = mysqli_query($connection, $query2);
	  if(!$result2) { 
      $undoQuery = sprintf(student_delete_qry, $sNum);
      mysqli_query($connection,$undoQuery);
		  die("Could not add User: " . mysqli_error($connection));
    }
    $studentUserInsertQuery = sprintf(student_user_insert_qry, $sNum,$login);
    $result3 = mysqli_query($connection, $studentUserInsertQuery);
    if(!$result3) { 
		  die("Could not add Student-User: " . mysqli_error($connection));
    }
  }
	mysqli_close($connection);
	return 1;
}

function update_student_user($sNum,$name,$login,$pass,$oldLogin,$oldsNum){
	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME) or die(mysql_error());
  //update student then user first
  $startTrans = mysqli_query($connection, "start transaction;");
  $lockRows =  mysqli_query($connection, "select stuNum,stuName,loginID,password
    FROM User, Student WHERE stuNum='%s' AND loginID='%s';");
  if( !lockRows ) {
    die("User rows could not be locked");
  }

  $query1 = sprintf(user_update_qry, $login,$pass,$oldLogin);
	$result1 = mysqli_query($connection, $query1);
	if(!$result1) { 
    $rollback = mysqli_query($connection, "rollback;");
		die("Could not update User: " . mysqli_error($connection));
  }
  
  $query2 = sprintf(student_update_qry,$sNum,$name,$oldsNum);
	$result2 = mysqli_query($connection, $query2);
	if(!$result2) { 
    $rollback = mysqli_query($connection, "rollback;");
    die("Could not update User: " . mysqli_error($connection));
  }

  // if all good commit changes
  $commit = mysqli_query($connection, "commit;");

	mysqli_close($connection);
	return $retVal;
}


//returns (isValid, isAdmin)
function valid_user($userID, $password){
	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME) or die(mysql_error());
	$query = sprintf(user_login_qry, $userID, $password);
	$result = mysqli_query($connection, $query);
	if(!$result) { 
		die("Could not read Users: " . mysqli_error($connection));
	}
  $retVal=array(0,0);
	while($row = $result->fetch_row()) {
    if ($row[0] == $userID and $row[1] == $password)
    {  
		  $retVal = array(1,$row[2]);
    }
	}
	mysqli_close($connection);
	return $retVal;
}

function validate_password($userID, $password){
	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME) or die(mysql_error());
	$query = sprintf(validate_password_qry, $userID);
	$result = mysqli_query($connection, $query);
	if(!$result) { 
		die("Could not read Users: " . mysqli_error($connection));
	}
  $retVal=0;
	while($row = $result->fetch_row()) {
    if ($row[0] == $userID and $row[1] == $password)
    {  
		  $retVal = 1;
    }
	}
	mysqli_close($connection);
	return $retVal;
}

function all_users_table() {
  	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME) or die(mysql_error());
	$query = sprintf(user_read_qry);
	$result = mysqli_query($connection, $query);
  $retVal = 1;
	if(!$result) { 
		die("Could not read Users: " . mysqli_error($connection));
	  $retVal = -1;
  }

  while($row = $result->fetch_row()) {
		echo sprintf(table_row, $row[0], $row[1], $row[2], $row[3]); 
	}

  mysqli_close($connection);
  return $retVal;
}


function edit_user_table($sNum,$user) {
  	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME) or die(mysql_error());
 	$query = sprintf(one_user_qry,$sNum,$user);
	$result = mysqli_query($connection, $query);
	if(!$result) { 
		die("Could not read Users: " . mysqli_error($connection));
  }

  $row = $result->fetch_row();

	echo sprintf(edituser_tablerow, $row[0], $row[1], $row[2], $row[3]); 

  mysqli_close($connection);
  return;
}

function users_insert($userID, $password) {
	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME) or die(mysql_error());
	$query = sprintf(user_insert_qry, $userID, $password);
	$result = mysqli_query($connection, $query);
	if(!$result) { 
		die("Could not insert user-id password combo.\n" . mysqli_error($connection));
	}

	mysqli_close($connection);
	return $retVal;
}

function update_password($userID, $newPassword) {
	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME) or die(mysql_error());
	$query = sprintf(update_password_qry,$newPassword,$userID);
	$result = mysqli_query($connection, $query);
	if(!$result) { 
		die("Could not update password.\n" . mysqli_error($connection));
	}

	mysqli_close($connection);
	return $result;
}

function get_stuNum($userID) {
  $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME) or die(mysql_error());
 	$query = sprintf(student_user_qry,$userID);
	$result = mysqli_query($connection, $query);
	if(!$result) { 
		return NULL;
  }
  $row = $result->fetch_row();
  $retVal = $row[0];
  mysqli_close($connection);
  return $retVal;
}
?>

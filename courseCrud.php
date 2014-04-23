<?PHP

// schedule query definitions
define("student_schedule_qry", "SELECT *
    FROM Course c,Schedule s 
    WHERE isCurrent=1 AND StuNum='%s' AND c.courseNo=s.CourseNo AND
    c.sequenceID=s.SequenceID;");

define("course_qry", "SELECT *
    FROM Course where courseNo NOT IN (SELECT CourseNo FROM Schedule
      WHERE StuNum=%s);");

// course list queries

define("add_course_qry", "INSERT INTO Schedule
	Values('%s', '%s', NULL, 1, '%s')");

define("remove_course_qry", "DELETE FROM Schedule
	WHERE CourseNo='%s' AND StuNum='%s';");
	
// pre-req check queries

define("check_prereq_qry", "SELECT p.requisiteCourseNo
		FROM Prerequisite p
		WHERE p.CourseNo = '%s' AND p.requisiteCourseNo NOT IN( Select pre.requisiteCourseNo
		FROM Prerequisite pre, Schedule s
		WHERE pre.CourseNo = '%s' AND s.StuNum = '%s' AND pre.requisiteCourseNo = s.CourseNo) LOCK IN SHARE MODE;");


// form genertaion templates for schedulle pages
define("schedule_row", '<tr>
  <td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
  <td>%s</td>
	<td>
	<a class="deleteUser"
  href="courseHandler.php?courseNo=%1$s&action=remove">X</a>
  </td>
	</tr>'
);

define("course_list_row", '<tr>
  <td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
  <td>%s</td>
  <td>%s</td>
	<td horizontal-align="center">
	<a class="resetPassword"
  href="courseHandler.php?courseNo=%1$s&action=add&seqID=%3$s">Schedule Course</a>
	</td>
	</tr>'
);






// Begin function definitions +++++++++++++++++++++++++++++++++++++++

function print_schedule($sNum) {
  $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME) or
    die(mysql_error());
  $query = sprintf(student_schedule_qry,$sNum);
  $result= mysqli_query($connection, $query);
  $row= $result->fetch_row(); 
  if(!$row) {
    echo "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
     <p class='error'> Not yet enrolled in any courses</p> ".$row[0];
  }
  while($row) {
    echo sprintf(schedule_row,$row[0],$row[1],$row[2],$row[4],$row[3]);
    $row= $result->fetch_row();
  } 
  return;
}

function make_course_list($sNum) {
  $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME) or
    die(mysql_error());
  $query = sprintf(course_qry,$sNum);
  $result= mysqli_query($connection, $query);

  while($row=$result->fetch_row()) {
    echo sprintf(course_list_row,$row[0],$row[1],$row[2],$row[4],$row[3],
        $row[5]);
  } 
  return;
}

function add_course( $sNum, $cNo, $seqID)
{
	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME) or die(mysql_error());
	$result1 = mysqli_query($connection, "start transaction;");
	
	/* Here, before we lock the schedule table, check for pre-reqs, and make
	sure the student has taken all of the necessary pre-reqs*/
	$preRequisites = mysqli_query($connection, sprintf(check_prereq_qry, $cNo, $cNo, $sNum));
	
	// Still have pre-requisites they need to take
	if( mysqli_num_rows($preRequisites)>0 )
	{
		echo "The following prerequisites must be taken first: <br>";
		while($row = $preRequisites->fetch_row() ) {
		echo $row[0] . "<br>";
		}
    // end here after outputting necessary prereqs
    die();
	}
		
	$lockResult = mysqli_query($connection, 
      sprintf("SELECT * FROM Course c WHERE courseNo=%s AND sequenceID=%s
        LOCK IN SHARE MODE;", $cNo, $seqID));
  // still able to read values from locked table
  $row = $lockResult->fetch_row();
  //unsucsessful lock
	if( !$lockResult )
	{
		$rollback = mysqli_query($connection, "rollback;");
		die("Could not lock table");
	} 
  // check if past deadline
  // if deadline < current date/time
  else if ($row[5] < Date('Y-m-d H:i:s') )  {
		$rollback = mysqli_query($connection, "rollback;");
    die("The scheduling deadline for this class has passed");
  }
  // check if class is full
  else if ($row[4]>=$row[3]) {a
		$rollback = mysqli_query($connection, "rollback;");
    die("The class you're trying to enroll in is full");
  }
  // if all good add course
	$query = sprintf(add_course_qry, $cNo, $sNum, $seqID );
	$result2 = mysqli_query($connection, $query);

  // rollback if issues
	if( !$result2 )
	{
    $error = mysqli_error($connection);
		$result3 = mysqli_query($connection, "rollback;");
		die("Could not add course to schedule: " . $error);
	}

  // otherwise commit changes
	$result3 = mysqli_query($connection, "commit;");
	return TRUE;
}

function remove_course( $sNum, $cNo)
{
	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME) or die(mysql_error());
	$query = sprintf(remove_course_qry, $cNo, $sNum);
	$result = mysqli_query($connection, $query);
	if( !$result )
	{
		die("Could not remove course from schedule: " . mysqli_error($connection));
	}
	return;
	
}








?>

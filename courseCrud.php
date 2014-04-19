<?PHP

// schedule query definitions
define("student_schedule_qry", "SELECT *
    FROM Course c,Schedule s 
    WHERE isCurrent=1 AND StuNum='%s' AND c.courseNo=s.CourseNo;");

define("course_qry", "SELECT *
    FROM Course");

// course list queries



// form genertaion templates for schedulle pages
define("schedule_row", '<tr>
  <td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
  <td>%s</td>
	<td>
	<a class="deleteUser"
  href="courseHandler.php?courseNo=%1$s">X</a>
  </td>
	</tr>'
);

define("course_list_row", '<tr>
  <td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
  <td>%s</td>
	<td horizontal-align="center">
	<a class="resetPassword"
  href="courseHandler.php?courseNo=%1$s">Schedule Course</a>
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
    echo sprintf(schedule_row,$row[0],$row[1],$row[2],$row[3],$row[4]);
    $row= $result->fetch_row();
  } 
  return;
}

function make_course_list() {
  $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME) or
    die(mysql_error());
  $query = sprintf(course_qry,$sNum);
  $result= mysqli_query($connection, $query);

  while($row=$result->fetch_row()) {
    echo sprintf(course_list_row,$row[0],$row[1],$row[2],$row[3],$row[4]);
  } 
  return;
}



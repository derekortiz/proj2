<?php session_start(); 
include_once './userCrud.php';
include_once './courseCrud.php':

if(!isset($_SESSION['username'])) {
  header('Location: http://www.cse.msu.edu/~ortizder/proj2/');
}

// we get the action type and course number through url parameter passing
// get student number from session variable set at login
if(isset($_GET['course'])) {
  $course=$_GET['course'];
}
if(isset($_GET['action'])) {
  $action=$_GET['action'];
}

if(!isset($_GET['action']) || !isset($_GET['course'])) {
  echo "Missing action parameters";
  exit;
}

switch($action) {
  case "add":
    break;
  case "remove":
    break;
  default:
    break;
}

header("http://cse.msu.edu/~ortizder/proj2/schedule.php")



// Need a function to remove a course from a students schedule
// removeCourse(stuNum, courseNo)

// Need function to schedule a course
// addCourse(stuNum,courseNo)


?>

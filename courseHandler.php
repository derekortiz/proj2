<?php session_start(); 
include_once './userCrud.php';
include_once './courseCrud.php';

if(!isset($_SESSION['username'])) {
  header('Location: http://www.cse.msu.edu/~ortizder/proj2/');
}

// we get the action type and course number through url parameter passing
// get student number from session variable set at login
if(isset($_GET['courseNo'])) {
  $course=$_GET['courseNo'];
}
if(isset($_GET['action'])) {
  $action=$_GET['action'];
}
if(isset($_GET['seqID'])) {
  $seqID=$_GET['seqID'];
}

if(!isset($_GET['action']) || !isset($_GET['courseNo'])) {
  echo sprintf("Missing action parameters courseNo:%s
      action:%s",$course,$action);
  exit;
}


switch($action) {
  case "add":
    add_Course($_SESSION['stuNum'], $course, $seqID);
    break;
  case "remove":
    remove_course($_SESSION['stuNum'], $course);
    break;
  default:
    break;
}

header('Location: http://www.cse.msu.edu/~ortizder/proj2/schedule.php');
exit;


// Need a function to remove a course from a students schedule
// removeCourse(stuNum, courseNo)

// Need function to schedule a course
// addCourse(stuNum,courseNo)


?>

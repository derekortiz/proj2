<?php session_start(); 
include_once './userCrud.php';
if(!isset($_SESSION['username'])) {
  header('Location: http://www.cse.msu.edu/~ortizder/proj2/');
}

echo "On schedule page"
?>
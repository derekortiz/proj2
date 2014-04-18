<?PHP
include_once "./userCrud.php";
session_start();


if(!$_SESSION['isAdmin'])
{ 
  header('Location: http://www.cse.msu.edu/~ortizder/proj2/index.php?wrong=1');
  exit;
}

if($_POST['Stud#'] != '' && $_POST['name'] != '' && $_POST['login'] != '' &&
    $_POST['pass'] != '') 
{
  add_student_user($_POST['Stud#'],$_POST['name'],$_POST['login'],$_POST['pass'],$_POST['isAdmin']);
}

header('Location: http://www.cse.msu.edu/~ortizder/proj2/admin.php');
exit;

?>



<?PHP
include_once "./userCrud.php";
session_start();
$user = $_POST['userid'];
$pass = $_POST['password'];

$returnArray = valid_user($user,$pass);

$valid = $returnArray[0];
$isAdmin= $returnArray[1];

if($valid)
{ 
  session_regenerate_id();
  $_SESSION['username']=$user;
  $_SESSION['isAdmin']=$isAdmin;

  header('Location: http://www.cse.msu.edu/~ortizder/proj2/account.php');
  exit;
}
else
{
  header('Location: http://www.cse.msu.edu/~ortizder/proj2/index.php?wrong=1');
  exit;
}
?>



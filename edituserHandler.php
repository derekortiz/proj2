<?PHP
include_once "./userCrud.php";
session_start();

// Check priveleges
if(!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin']!=1 )
{ 
  header('Location: http://www.cse.msu.edu/~ortizder/proj2/index.php');
  exit;
}

// get directives
if(isset($_GET['user'])) {
  $user=$_GET['user'];
}
if(isset($_GET['action'])){
  $action=$_GET['action'];
}
if(isset($_GET['sNum'])) {
  $sNum=$_GET['sNum'];
}


// execute action
switch($action) {
  case 'reset':
    update_password($user, 'Simple123!');
    header('Location: http://www.cse.msu.edu/~ortizder/proj2/admin.php');
    exit;
    break;
  case 'delete':
    $success=user_delete($user,$sNum);
    header('Location: http://www.cse.msu.edu/~ortizder/proj2/admin.php');
    exit;
    break;
  default:
    $success=update_student_user($_POST['sNum'],$_POST['name'],$_POST['login'],
    $_POST['pass'],$_POST['oldLogin'],$_POST['oldsNum']);
    header('Location: http://www.cse.msu.edu/~ortizder/proj2/admin.php');
    exit;
    break;
  }

?>



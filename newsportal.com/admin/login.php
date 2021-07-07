<?php
session_start();
$isFrontEnd = false;
//Include conf-path.php file, where all path configuration for the project are set up
require_once '../config/conf-path.php';
//Include conf-conn.php file, where the database connectivity is set up
require_once '../config/conf-conn.php';
//Include conf-string.php file, where necessary strings for the pages are set up
require_once '../config/conf-string.php';
//Include conf-query.php file, where the queries are set up
require_once '../config/conf-query.php';
//Include urlDiversion.php for using UrlDiversion class, which is used to update URL by manipulating previous $_GET varaibles
// For example: After pressing 'logout' e.g 'index.php?tag=comment&logout', the page redirects to the same page e.g ?tag=comment i.e from where logout was pressed.
require_once '../src/classes/urlDiversion.php';
//Include articleLister.php for using ArticleLister class, which is used to generate article box
require_once '../src/classes/articleLister.php';
//Include searchArticle.php for using SearchArticle class, where all the searching mechanism is set up
require_once '../src/classes/searchArticle.php';

//If a session for admins/users is already set, the page will be redirected to index(home) page
if (isset($_SESSION[$session_admin])){
  header('location: index.php');
}

//Include head.php file, where the head sub-tags i.e ('<meta>' & '<link>' etc) are written
require_once '../src/head.php';
//Include header.php file, where the <header>....</header> part is written
require_once '../src/header.php';

//Setting up error and success flags along with the log message to display any successful or unsuccessful log
$log_error = false;
$log_success = false;
$logMsg = '';

if (isset($_POST['login'])){
  //If login form is submitted,
  if (empty($_POST['username']) || empty($_POST['password'])){
    // If the username and password fields are empty, show an error
    $log_error = true;
    $logMsg = 'Fields cannot be empty';
  }else{
    //else, check for username in database to match the form username
    $result = $pdo->prepare($query_login_fetch_by_username_admin);
    $criteria = [
      'username' => $_POST['username']
    ];
    $result->execute($criteria);

    if ($result->rowCount() > 0){
      //If the username is in database, check for password
      $user = $result->fetch();
      if (password_verify($_POST['password'], $user['password'])){
        // If password is verified, login the user and redirect to index (home) page
        $_SESSION[$session_admin] = $user['user_id'];
        $_SESSION[$admin_role] = $user['role'];
        header('location: index.php');
      }else{
        //If password is not verified, show an error
        $log_error = true;
        $logMsg = 'Your password is incorrect';
      }
    }else{
      // If username is not found, show an error
      $log_error = true;
      $logMsg = 'Your username is incorrect';
    }
  }
}else if (isset($_GET['loggedOut'])){
  // If login.php?loggedOut url is set, i.e if the user logged out, show an exit message
  $log_success = true;
  $logMsg = 'We hope to see you again soon';
}

?>
<nav class="navigators"></nav>
<img src="<?php echo RES_PATH; /*RES_PATH, a path for .../..../.../res directory, and is defined in conf-path.php*/
?>images/banners/randombanner.php"/>
<main>
  <div class="title"> <i class="icon-enter"></i> <span>Dashboard Login</span></div>
  <?php

  ?>
  <div class="outAdminLoginBox">
    <div class="insideBox adminLoginBox">
      <form method="POST" action"">
        <?php
        // If error flag is set, display the log message in error box
        //If success flag is set, display the log message in success box
        if ($log_error) echo '<span><div class="log_div"><i class="icon-blocked"></i>&nbsp;' . $logMsg .'</div></span>';
        else if ($log_success) echo '<span><div class="log_div_success"><i class="icon-exit"></i>&nbsp;' . $logMsg .'</div></span>';
        ?>
        <!--  Form elements setup-->
        <span><input type="text" name="username" placeHolder="Username"/></span>
        <span><input type="password" name="password" placeHolder="Password"/></span>
        <a href = "#">Forgot Password?</a>
        <span><input type="submit" name="login" value="Log In"/></span>
      </form>
    </div>
  </div>
</main>

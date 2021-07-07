<?php
  if ( $entry_point != null){
    //Setup necessary flags and logs
    $loginFailed = false;
    $logMsg = '';

    // If the login form is cancelled, go back the history
    if (isset($_POST['cancel'])){
      $urlGen = new UrlDiversion();
      $url = $urlGen->getBackURL($get_login);
      header('location: ' . $_SERVER['PHP_SELF'] . $url);

    // If login form is posted, check necessary validations, and create a session if validated. Other wise display error log
    }else if (isset($_POST['login'])){
      if (empty($_POST['username']) || empty($_POST['password'])){
        $loginFailed =true;
        $logMsg = 'Fields cannot be empty';
      }else{
        $result = $pdo->prepare($query_login_fetch_by_username);
        $criteria = [
          'username' => $_POST['username']
        ];
        $result->execute($criteria);
        //Check username
        if ($result->rowCount() > 0){
          $user = $result->fetch();
          //Check password
          if (password_verify($_POST['password'], $user['password'])){
            $_SESSION[$session_name] = $user['user_id'];
            $urlGen = new UrlDiversion();
            $url = $urlGen->getBackURL($get_login);
            header('location: ' . $_SERVER['PHP_SELF'] . $url);
          }else{
            $loginFailed = true;
            $logMsg = 'Your password is incorrect';
          }
        }else{
          $loginFailed = true;
          $logMsg = 'Your username is incorrect';
        }
      }
    }
?>
<!-- HTML login form -->
      <div class="extraLayer">
      </div>
      <div class="insideBox">
        <form method="POST" action"#">
          <?php
          if ($loginFailed){
            echo '<span><div class="log_div"><i class="icon-blocked"></i>&nbsp;' . $logMsg .'</div></span>';
          }
          ?>
          <span><input type="text" name="username" placeHolder="Username"/></span>
          <span><input type="password" name="password" placeHolder="Password"/></span>
          <a href = "#">Forgot Password?</a>
          <span><input type="submit" name="login" value="Log In"/><input type="submit" name="cancel" value="Cancel"/></span>
        </form>
      </div>
<?php
  }else{
    header('location: error.php');
  }
?>

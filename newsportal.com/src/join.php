<?php
  if ( $entry_point != null){
    $log_error = false;
    $log_success = false;
    $logMsg = '';
    //If the form is cancelled, go back the history
    if (isset($_POST['cancel'])){
      unset($_GET[$get_signup]);
      $urlGen = new UrlDiversion();
      $url = $urlGen->getBackURL($get_signup);
      header('location: ' . $_SERVER['PHP_SELF'] . $url);
    // If the registration form is submitted, check necessary validations and create a user if validated. else  generate error log
    }else if (isset($_POST['join'])){
      // If fields are empty
      if (empty($_POST['firstname']) || empty($_POST['surname']) || empty($_POST['email']) || empty($_POST['username']) || empty($_POST['password']) || empty($_POST['repassword'])){
        $log_error = true;
        $logMsg = 'Fields cannot be empty';
        // if names are invaid characters
      }else if (!preg_match("/^[a-zA-Z ]*$/", $_POST['firstname']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['surname']) ){
        $log_error = true;
        $logMsg = 'Please enter valid name';
        // If email is invalid
      }else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ){
        $log_error = true;
        $logMsg = 'Please enter a valid email address';
        // If the two passwords don't match
      }else if ($_POST['password'] != $_POST['repassword']){
        $log_error = true;
        $logMsg = 'Passwords didn\'t match';
      }else{
        //Check if the username is already in database record
        $checkUser = $pdo->prepare($query_login_fetch_by_username);
        $criteria = ['username' => $_POST['username']];
        $checkUser->execute($criteria);
        if ($checkUser->rowCount() > 0){
          $log_error = true;
          $logMsg = 'Username already taken';
        }else{
          // If error free, insert the user to database record
          $result = $pdo->prepare($query_join_insert);
          $criteria = [
            'firstname' => htmlspecialchars($_POST['firstname']),
            'surname' => htmlspecialchars($_POST['surname']),
            'user_email' => htmlspecialchars($_POST['email']),
            'username' => htmlspecialchars($_POST['username']),
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'role' => 'VIEWER'
          ];
          $result->execute($criteria);
          //Display logs
          if ($result){
            $log_success = true;
            $logMsg = 'Registration successful, ' . htmlspecialchars($_POST['firstname']) . ' ' . htmlspecialchars($_POST['surname']);
          }else{
            $log_error = true;
            $logMsg = 'Registration unsuccessful. Please try again!';
          }
        }
      }
    }
?>
<!-- HTML registration form -->
      <div class="extraLayer">
      </div>
      <div class="insideBox">
        <span id="msg"></span>
        <form method="POST" action="#">
          <?php
            if ($log_error) echo '<span><div class="log_div"> <i class="icon-blocked">&nbsp;' . $logMsg . '</i></div></span>';
            else if ($log_success) echo '<span><div class="log_div_success"> <i class="icon-checkmark2">&nbsp;' . $logMsg . '</i></div></span>';
          ?>
          <span><input type="text" name="firstname" placeHolder="First Name (e.g Mandip)"/></span>
          <span><input type="text" name="surname" placeHolder="Last Name (e.g Koirala)"/></span>
          <span><input type="email" name="email" placeHolder="Email-Address (e.g example@gmail.com)"/></span>
          <span><input type="text" name="username" placeHolder="Username (e.g mandip400)"/></span>
          <span><input type="password" name="password" placeHolder="Password (e.g *****)"/></span>
          <span><input type="password" name="repassword" placeHolder="Re-Password (e.g *****)"/></span>
          <span><input type="submit" name="join" value="Join"/><input type="submit" name="cancel" value="Cancel"/></span>
        </form>
      </div>
<?php
  }else{
    header('location: error.php');
  }
?>

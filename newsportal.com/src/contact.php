<?php
  if ( $entry_point != null){
    $log_error = false;
    $log_success = false;
    $logMsg = '';
    // If contact form is submitted, validate the inputs and send the message if validated, else show error log
    if (isset($_POST['click'])){
      if (empty($_POST['firstname']) || empty($_POST['surname']) || empty($_POST['email']) || empty($_POST['message'])){
        $log_error = true;
        $logMsg = 'Fields cannot be empty';
      }else if (!preg_match("/^[a-zA-Z ]*$/", $_POST['firstname']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['surname'])){
        $log_error = true;
        $logMsg = 'Please enter valid name';
      }else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $log_error = true;
        $logMsg = 'Please enter a valid email address';
      }else{
        //Send message to backend system
        $message_send = $pdo->prepare($query_contact_insert);
        $criteria = [
          'fname' => $_POST['firstname'],
          'sname' => $_POST['surname'],
          'email' => $_POST['email'],
          'detail' => $_POST['message'],
          'send_date' => date('y-m-d')
        ];
        $message_send->execute($criteria);
        //Display log
        if ($message_send){
          $log_success = true;
          $logMsg = 'Thank you for contacting us! Your query has been submitted';
        }else{
          $log_error = true;
          $logMsg = 'You query could not be submitted at the moment. Please try again later';
        }
      }
    }
?>

<main>
  <div class="contactarea">
    <form id="form_contact" method="POST" action="#form_contact">
      <?php
        if ($log_error) echo '<div class="log_div"> <i class="icon-blocked">&nbsp;' . $logMsg . '</i></div>';
        else if ($log_success) echo '<div class="log_div_success"> <i class="icon-checkmark2">&nbsp;' . $logMsg . '</i></div>';

        if (isset($_SESSION[$session_name])){
          $getUser = $pdo->prepare($query_login_fetch_by_userid);
          $criteria = [ 'id' => $_SESSION[$session_name]];
          $getUser->execute($criteria);
          $user = $getUser->fetch();
          echo '<input type="text" name="firstname" value="'. $user['firstname'] . '" readonly/>';
          echo '<input type="text" name="surname" value="'. $user['surname'] . '" readonly/>';
          echo '<input type="email" name="email" value="'. $user['user_email'] . '" readonly/>';
        }else{
          echo '<input type="text" name="firstname" placeHolder="First Name (e.g Mandip)" />';
          echo '<input type="text" name="surname" placeHolder="Last Name (e.g Koirala)"/>';
          echo '<input type="email" name="email" placeHolder="Email-Address (e.g example@gmail.com)"/>';
        }
      ?>
      <legend>Write you message</legend>
      <textarea name="message" rows="7" ></textarea>
      <input type="submit" name="click" value="Message Us"/>
    </form>
  </div>
</main>

<?php
  }else{
    header('location: error.php');
  }
?>

<?php
  if ($entry_point != null){
    $log_error = false;
    $log_success= false;
    $logMsg = '';

    if ($_GET['user'] == 'add'){
      echo '<div class="title"><span>Add User</span></div>';
      if (isset($_POST['add'])){
        if (empty($_POST['firstname']) || empty($_POST['surname']) || empty($_POST['email']) || empty($_POST['username']) || empty($_POST['password']) || empty($_POST['repassword'])){
          $log_error = true;
          $logMsg = 'Fields cannot be empty';
        }else if (!preg_match("/^[a-zA-Z ]*$/", $_POST['firstname']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['surname']) ){
          $log_error = true;
          $logMsg = 'Please enter valid name';
        }else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ){
          $log_error = true;
          $logMsg = 'Please enter a valid email address';
        }else if ($_POST['password'] != $_POST['repassword']){
          $log_error = true;
          $logMsg = 'Passwords didn\'t match';
        }else{
          $checkUser = $pdo->prepare($query_login_fetch_by_username);
          $criteria = ['username' => $_POST['username']];
          $checkUser->execute($criteria);
          if ($checkUser->rowCount() > 0){
            $log_error = true;
            $logMsg = 'Username already taken';
          }else{
            $result = $pdo->prepare($query_join_insert);
            $criteria = [
              'firstname' => htmlspecialchars($_POST['firstname']),
              'surname' => htmlspecialchars($_POST['surname']),
              'user_email' => htmlspecialchars($_POST['email']),
              'username' => htmlspecialchars($_POST['username']),
              'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
              'role' => $_POST['role']
            ];
            $result->execute($criteria);
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
      <div class="insideBox adminLoginBox addBox">
        <form method="POST" action="">
          <?php
            if ($log_error) echo '<span><div class="log_div"> <i class="icon-blocked">&nbsp;' . $logMsg . '</i></div></span>';
            else if ($log_success) echo '<span><div class="log_div_success"> <i class="icon-checkmark2">&nbsp;' . $logMsg . '</i></div></span>';
          ?>
          <span><input type="text" name="firstname" placeHolder="First Name (e.g Mandip)"/></span>
          <span><input type="text" name="surname" placeHolder="Last Name (e.g Koirala)"/></span>
          <span><input type="email" name="email" placeHolder="Email-Address (e.g example@gmail.com)"/></span>
          <span><input type="text" name="username" placeHolder="Username (e.g mandip400)"/></span>
          <span>
            <select class="role" name="role">
              <option value="ADMIN">ADMIN</option>
              <option value="USER">USER</option>
              <option value="VIEWER">VIEWER</option>
            </select>
          </span>
          <span><input type="password" name="password" placeHolder="Password (e.g *****)"/></span>
          <span><input type="password" name="repassword" placeHolder="Re-Password (e.g *****)"/></span>
          <span><input type="submit" name="add" value="Add User"/></span>
        </form>
      </div>

<?php
    }else if ($_GET['user'] == 'edit'){
      echo '<div class="title"><span>Edit User</span></div>';
      echo '<div class="insideBox adminLoginBox addBox">';

      if (isset($_POST['edit'])){
        if ($_POST['role'] == $_POST['user_role']){
          $log_error = true;
          $logMsg = $_POST['user_name'] . ' is already ' . $_POST['role'];
        }else{
          $result = $pdo->prepare($query_update_user);
          $criteria = ['role'=> $_POST['role'], 'user_id' => $_POST['user_id']];
          $result->execute($criteria);
          if ($result){
            $log_success = true;
            $logMsg = '<b> ' . $_POST['user_name'] . '</b> has been successfully changed from <b>' . $_POST['user_role'] . '</b> to <b>' . $_POST['role'] . '</b>';
          }else{
            $log_error = true;
            $logMsg = 'Category title conflicts with an existing category';
          }
        }
      }else if (isset($_POST['change'])){
        unset($_POST['change']);
        echo '<form method="POST" action="">';
        echo '<input type="hidden" name="user_name" value="' . $_POST['username'] . '"/>';
        echo '<input type="hidden" name="user_role" value="' . $_POST['role'] . '"/>';
        echo '<input type="hidden" name="user_id" value="' . $_POST['user_id'] . '"/>';
        echo '<span><select name="role" class="role">';
        echo '<option value="ADMIN"';
        if ($_POST['role'] == 'ADMIN') echo 'selected';
        echo '>ADMIN</option>';
        echo '<option value="USER"';
        if ($_POST['role'] == 'USER') echo 'selected';
        echo '>USER</option>';
        echo '<option value="VIEWER"';
        if ($_POST['role'] == 'VIEWER') echo 'selected';
        echo '>VIEWER</option>';
        echo '</select></span>';
        foreach ($_POST as $key => $value){
          echo '<span> ' . strtoupper($key) . ' is ' .$value . '</span>';
        }
        echo '<span><input type="submit" value="Change User Role" name ="edit"/><input type="submit" value="Cancel" name ="cancel"/></span>';
        echo '</form>';
      }
      if ($log_error) echo '<span><div class="log_div"> <i class="icon-blocked">&nbsp;' . $logMsg . '</i></div></span>';
      else if ($log_success) echo '<span><div class="log_div_success"> <i class="icon-checkmark2">&nbsp;' . $logMsg . '</i></div></span>';
      echo '</div>';

    }

    else if ($_GET['user'] == 'delete'){
      echo '<div class="title"><span>Delete User</span></div>';
      echo '<div class="insideBox adminLoginBox addBox">';
      echo '<form method="POST" action="">';
      if (isset($_POST['change'])){
        echo '<span style="color:slateGray;font-weight:bolder;">Are you sure you want to delete user &nbsp;<b>';
        echo $_POST['firstname'] . ' ' . $_POST['surname'] . '</b>&nbsp;(' . $_POST['role'] . ') ?</span>';
        echo '<span><input type="hidden" name="user_id" value="'. $_POST['user_id'] .'"/>';
        echo '<input type="hidden" name="user" value="'. $_POST['firstname'] . ' ' . $_POST['surname'] .'"/></span>';
        echo '<span><input type="submit" name="delete" value="Yes"/><input type="submit" name="nodelete" value="No"/></span>';
      }else if (isset($_POST['delete'])){
        $result = $pdo->prepare($query_delete_users);
        $criteria = ['user_id' => $_POST['user_id']];
        $result->execute($criteria);
        if ($result){
          $log_success = true;
          $logMsg = 'User ' . $_POST['user'] . ' successfully deleted';
        }else{
          $log_error = true;
          $logMsg = 'Could not delete user ' . $_POST['user'] . '. Please try again!';
        }
      }
      if ($log_error) echo '<span><div class="log_div"> <i class="icon-blocked">&nbsp;' . $logMsg . '</i></div></span>';
      else if ($log_success) echo '<span><div class="log_div_success"> <i class="icon-checkmark2">&nbsp;' . $logMsg . '</i></div></span>';
      echo '</form></div>';
    }else{
    header('location: ../src/error.php');
    }

    echo '<div class="title"><span>Tabular View (Admins)</span></div>';

    $table = new TableGenerate();
    $table->setHeadings(['Role', 'Firstname', 'Surname', 'Email', 'Username', 'User ID']);

    $result = $pdo->prepare($query_fetch_users_admin);
    // $criteria = ['user_id' => $_SESSION[$session_admin]];
    $result->execute();

    $row = $result->fetchAll(PDO::FETCH_ASSOC);
    foreach($row as $r){
      $table->addRow($r);
    }
    echo $table->getHTML($_GET['user']);

    echo '<div class="title"><span>Tabular View (Users)</span></div>';
    $table = new TableGenerate();
    $table->setHeadings(['Role', 'Firstname', 'Surname', 'Email', 'Username', 'User ID']);

    $result = $pdo->prepare($query_fetch_users_users);
    // $criteria = ['user_id' => $_SESSION[$session_admin]];
    $result->execute();

    $row = $result->fetchAll(PDO::FETCH_ASSOC);
    foreach($row as $r){
      $table->addRow($r);
    }
    echo $table->getHTML($_GET['user']);

    echo '<div class="title"><span>Tabular View (Viewers)</span></div>';
    $table = new TableGenerate();
    $table->setHeadings(['Role', 'Firstname', 'Surname', 'Email', 'Username', 'User ID']);

    $result = $pdo->prepare($query_fetch_users_viewers);
    // $criteria = ['user_id' => $_SESSION[$session_admin]];
    $result->execute();

    $row = $result->fetchAll(PDO::FETCH_ASSOC);
    foreach($row as $r){
      $table->addRow($r);
    }
    echo $table->getHTML($_GET['user']);

  }else{
    header('location: error.php');
  }
 ?>

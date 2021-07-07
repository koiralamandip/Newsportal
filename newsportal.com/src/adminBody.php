<?php
  if ( $entry_point != null){
    if (count($_GET) == 0){
      //Fetch name of the logged in user
      $getUser = $pdo->prepare($query_login_fetch_by_userid);
      $criteria = [ 'id' => $_SESSION[$session_admin]];
      $getUser->execute($criteria);
      $row = $getUser->fetch();

      //Display a greeting messagge to the user in backend
      echo '<div class="greet_admin"> <h2>Welcome, '. $row['username'] .'</h2>';
      echo 'Click on your privilege option and start managing your profile</div>';
      // If the user is ADMIN, allow to manage categories
    }else if (isset($_GET['category']) && $_SESSION[$admin_role] == 'ADMIN'){
      require_once '../src/manage_category.php';
      // If the user is ADMIN, allow to manage users
    }else if (isset($_GET['user']) && $_SESSION[$admin_role] == 'ADMIN'){
      require_once '../src/manage_user.php';
      //Allow to manage messages and comments
    }else if (isset($_GET['tag'])){
      require_once '../src/manage_tag.php';
      // Allow to manage article of the logged in user
    }else if (isset($_GET['article'])){
      require_once '../src/manage_article.php';
    }
?>

<?php
  }else{
    header('location: error.php');
  }
?>

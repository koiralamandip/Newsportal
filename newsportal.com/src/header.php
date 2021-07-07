<?php

  //For setting other get variables in url
  if (count($_GET) > 0){
    //If the url already contains get variables, connect the next one with '&'
    $connector = '&';
  }else{
    //else connect the get variable to url with '?'
    $connector = '?';
  }

  //If the page is not viewed directly (because, header.php shoudn't be browsed. Only should be included on other pages)
  if ( $entry_point != null){
?>
<header>
  <div>
    <?php
      /*
      * Check if there is a current session running
      * Yes: Get the session user's record and display the name along with LOG OUT option at the top of the page
      * No: Simply display JOIN US and LOG IN option at the top of the page
      * --------------------------------------------------------------------------------------------------------
      * $session_name = the string used to represent the session holder name (defined in conf-string.php file)
      * $user_table = the name of the table where users' records are stored in the database (defined in conf-string.php file)
      */

      //If the index page has been redirected from (=>"Go to Sites" from backend) or if the index page is not FrontEnd page (i.e backend index.php)
      if (isset($_GET['redirected']) || !$isFrontEnd){
        //If session for backend is set up, i.e backend user is logged in then get user details...
        if(isset($_SESSION[$session_admin])){
          //Preparing the query to execute
          $getUser = $pdo->prepare($query_login_fetch_by_userid);
          $criteria = [ 'id' => $_SESSION[$session_admin]];
          if (isset($_GET['redirected'])){
            // If the page has been redirected from backend to frontend and frontend session is already set, then preserve the last frontend session to temp
            //and update it to admin's id for the current page. So that both the frontend user and (backend's redirected user) can login to index.php at the same time
            if (isset($_SESSION[$session_name])) $_SESSION['tempuser'] = $_SESSION[$session_name];
            $_SESSION[$session_name] = $_SESSION[$session_admin];
          }

          //executing the query with the values stored in $criteria array
          $getUser->execute($criteria);

          //Fetching the resulted record into $row
          $row = $getUser->fetch();
          $role = $row['role'];
          echo "<span> Hy, " . $row['firstname'] . "</span>";
          echo "<a href='" . $_SERVER['REQUEST_URI'] . $connector . $get_logout . "'><i class='icon-exit'></i>&nbsp;Log Out</a>";
        }

      }else{
        if (isset($_SESSION['tempuser'])) $_SESSION[$session_name] = $_SESSION['tempuser'];
        if (isset($_SESSION[$session_name])){
          //Preparing the query to execute
          $getUser = $pdo->prepare($query_login_fetch_by_userid);
          $criteria = [ 'id' => $_SESSION[$session_name]];

          //executing the query with the values stored in $criteria array
          $getUser->execute($criteria);

          //Fetching the resulted record into $row
          $row = $getUser->fetch();
          $role = $row['role'];
          // If user is logged in, display Name and Logout option
          echo "<span> Hy, " . $row['firstname'] . "</span>";
          echo "<a href='" . $_SERVER['REQUEST_URI'] . $connector . $get_logout . "'><i class='icon-exit'></i>&nbsp;Log Out</a>";
        }else{
          $urlGen = new UrlDiversion();
          $url = $urlGen->getBackURL('loggedOut');
          if (count($_GET) > 0){
            $connector = '&';
          }else{
            $connector = '?';
          }
          // If user is not logged in, show LogIn and Join Us Options
          echo "<a href='" . $_SERVER['PHP_SELF'] . $url . $connector . $get_signup . "'><i class='icon-user-plus'></i>&nbsp;Join Us</a>";
          echo "<a href='" . $_SERVER['PHP_SELF'] . $url . $connector . $get_login . "'><i class='icon-enter'></i>&nbsp;Log In</a>";
        }
      }
    ?>
  </div>
  <!-- Heading title -->
  <h1>News Janahit</h1>
</header>

<?php
  }else{
    //If this page is directly viewed via url, redirect to error.php page
    header('location: error.php');
  }
?>

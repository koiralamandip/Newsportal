<?php
  //Clear the session varibales on pressing the logout option by the user, and redirect the page to where the logout was pressed
  if ($entry_point != null){
    session_unset();
    session_destroy();
    //UrlDiversion class is used to redirect the page to specific page relative to the current url..
    $urlGen = new UrlDiversion();
    $url = $urlGen->getBackURL($get_logout);
    if ($url == '') $url = '?';
    header('location: ' . $_SERVER['PHP_SELF'] . $url . 'loggedOut'); //$_SERVER['PHP_SELF'] (cite);

  }else{
    header('location: error.php');
  }

 ?>

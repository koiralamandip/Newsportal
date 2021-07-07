<?php
  /*A variable set up to detect entry point for the website..
  *i.e to prevent users from directly browsing to header.php, footer.php etc through URL, by only setting $entry_point = true in index.php
  * so that users can see header and footer in index.php but not access individual pages directly
  */
  $entry_point = true;
  //A session variable for frontend users
  $session_name = 'loggedUserID';
  //A session variable for backend users
  $session_admin = 'loggedAdminID';
  //The role of backend logged in user ... i.e either ADMIN or USER but not VIEWER
  $admin_role = 'role_admin';
  //A get variable to be set up while in login page
  $get_login = 'login';
  //A get variable to be set up while in join us page
  $get_signup = 'signup';
  //A get variable to be set up while in contact page
  $get_contact = 'contact';
  //A get variable to be set up while in logout page
  $get_logout = 'logout';
?>

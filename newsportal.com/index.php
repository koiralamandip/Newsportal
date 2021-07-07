<?php
session_start();
$isFrontEnd = true;
//Include conf-path.php file, where all path configuration for the project are set up
require_once 'config/conf-path.php';
//Include conf-conn.php file, where the database connectivity is set up
require_once 'config/conf-conn.php';
//Include conf-string.php file, where necessary strings for the pages are set up
require_once 'config/conf-string.php';
//Include conf-query.php file, where the queries are set up
require_once 'config/conf-query.php';
//Include urlDiversion.php for using UrlDiversion class, which is used to update URL by manipulating previous $_GET varaibles
// For example: After pressing 'logout' e.g 'index.php?tag=comment&logout', the page redirects to the same page e.g ?tag=comment i.e from where logout was pressed.
require_once 'src/classes/urlDiversion.php';
//Include articleLister.php for using ArticleLister class, which is used to generate article box
require_once 'src/classes/articleLister.php';
//Include searchArticle.php for using SearchArticle class, where all the searching mechanism is set up
require_once 'src/classes/searchArticle.php';
//Include tableGenerate.php for using TableGenerate class, where the table generation is done for displaying a list of data
require_once 'src/classes/tableGenerate.php';
//Include head.php file, where the head sub-tags i.e ('<meta>' & '<link>' etc) are written
require_once 'src/head.php';
//Include header.php file, where the <header>....</header> part is written
require_once 'src/header.php';
//Include menu.php file, where the navigation menubar is written
require_once 'src/menu.php';

//If 'Log Out' is pressed, a GET variable 'logout' is set, which includes 'src/logout.php';
if (isset($_GET[$get_logout])) require 'src/logout.php';
//If 'Log In' is pressed, a GET varaiable 'login' is set, which includes 'src/login.php';
else if (isset($_GET[$get_login])) require_once 'src/login.php';
//If 'Join Us' is pressed, a GET varaiable 'signup' is set, which includes 'src/join.php';
else if (isset($_GET[$get_signup])) require_once 'src/join.php';
?>
    <!-- Image to display in main page -->
    <img src="<?php echo RES_PATH; /*RES_PATH, a path for .../..../.../res directory, and is defined in conf-path.php*/
    ?>images/banners/randombanner.php"/>
    <?php
      if (!isset($_GET[$get_contact])){
        //Include content.php file, where the main section of a page is written i.e the search area and news display
        require_once 'src/content.php';
      }else{
        //Include contact.php file, where the contact form is written
        require_once 'src/contact.php';
      }
      //Include footer.php file, where the footer section is written
      require_once 'src/footer.php';
    ?>
  </body>
</html>

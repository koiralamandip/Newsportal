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
//Include tableGenerate.php for using TableGenerate class, where the table generation is done for displaying a list of (categories, users) in backend
require_once '../src/classes/tableGenerate.php';
//Include articleWriter.php for using ArticleWriter class, where the article adding/editing form is pre-setup
require_once '../src/classes/articleWriter.php';
//Incluude articleEditor.php for using ArticleEditor class, where the article edit/delete table is pre-defined
// Articles along with the article boxes as shown in frontend is displayed in one column and edit/delete option in second column
require_once '../src/classes/articleEditor.php';

//If a session for admins/users is not set, the page will be redirected to login page
if (!isset($_SESSION[$session_admin])){
  if (isset($_GET['loggedOut'])){
    // If the session was cleared by logging out
    header('location: login.php?loggedOut');
  }else{
    //If the session was not set initially
    header('location: login.php');
  }
}
//Include head.php file, where the head sub-tags i.e ('<meta>' & '<link>' etc) are written
require_once '../src/head.php';
//Include header.php file, where the <header>....</header> part is written
require_once '../src/header.php';

//If the page url is 'index.php?logout', require the logout page for logging out a user
if (isset($_GET[$get_logout])) require '../src/logout.php';
?>

<nav class="navigators"></nav>

<!--  Main body for the page-->
<main class="adminMainDiv">
  <!-- This div contains the left navigation Privilege options for adding, editing and deleting data -->
  <div class="left-nav">
    <div class="title"><span>Your Privileges</span></div>
    <ul>
      <?php
        if ($_SESSION[$admin_role] == 'ADMIN'){
          // If the role of logged in user is 'ADMIN', then display the below options to give privileges for them
          echo '<li><i class="icon-books"></i> Manage Categories
                  <ul>
                    <li><i class="icon-plus"></i> <a href="?category=add">Add Category</a></li>
                    <li><i class="icon-edit"></i> <a href="?category=edit">Edit Category</a></li>
                    <li><i class="icon-delete"></i> <a href="?category=delete">Delete Category</a></li>
                  </ul>
                </li>';
          echo '<li><i class="icon-users"></i> Manage Users
                  <ul>
                    <li><i class="icon-user-plus"></i> <a href="?user=add">Add User</a></li>
                    <li><i class="icon-edit"></i> <a href="?user=edit">Edit User</a></li>
                    <li><i class="icon-user-minus"></i> <a href="?user=delete">Delete User</a></li>
                  </ul>
                </li>';
        echo '<li><i class="icon-contact"></i> <a href="?tag=message">Manage Messages</a></li>';
        }
      ?>
      <!-- If the role of logged in user is not 'ADMIN', only the below two 'Manage Comments' and 'Manage Articles' privileges are given-->
      <li><i class="icon-bubbles3"></i> <a href="?tag=comment">Manage Comments</a></li>
      <li><i class="icon-newspaper"></i> Manage Articles
        <ul>
          <li><i class="icon-plus"></i> <a href="?article=add">Add Article</a></li>
          <li><i class="icon-edit"></i> <a href="?article=edit">Edit Article</a></li>
          <li><i class="icon-delete"></i> <a href="?article=delete">Delete Article</a></li>
        </ul>
      </li>
      <hr>
      <!-- Redirection to frontend page -->
      <li><i class="icon-home"></i> <a href="<?php echo ROOT;?>?redirected">Go to Site</a></li>
    </ul>
  </div>
  <!-- This div contains the main display where the data managing options are shown -->
  <div class="action">
    <?php
      //Include adminBody.php, where the setup for displaying the contents are written
      require_once '../src/adminBody.php';
    ?>
  </div>
</main>

<?php
  //Include footer.php to display the footer
  require_once '../src/footer.php';
?>

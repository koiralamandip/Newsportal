<?php
  if ( $entry_point != null){
?>
<!-- If $menuLong is not null (that happens in frontend only), set the classname of nav to 'navig' else set to 'navigators' -->
<nav class="<?php if ($menuLong != null) echo 'navig'; else echo 'navigators';?>">
  <ul>
    <!-- Menu items to display-->
    <li><a href= <?php echo ROOT;?> ><i class="icon-home"></i> Home</a></li> <!-- <i class="icon-home"> (cite)-->
    <li><a href="<?php echo ROOT;?>?tag=latest"><i class="icon-newspaper"></i> Latest Articles</a></li>
    <li id="dropper"><a href=""><i class="icon-menu"></i> Select Category</a>
      <div id="dropdown">
        <ul>
          <!-- Fetch all the categories from database and display in menu section -->
          <?php
            $category_list = $pdo->prepare($query_categories_fetch);
            $category_list->execute();
            foreach($category_list as $row){
              echo "<li><a href='" . ROOT . "?cat=" . $row['category_id'] ."'>" . $row['category_title'] . "</a></li>";
            }
          ?>
        </ul>
      </div>
    </li>
    <li><a href="?contact"><i class="icon-contact"></i> Contact us</a></li>
  </ul>
</nav>

<?php
  }else{
    header('location: error.php');
  }
?>

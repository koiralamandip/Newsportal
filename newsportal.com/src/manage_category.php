<?php
  if ($entry_point != null){
    $log_error = false;
    $log_success= false;
    $logMsg = '';

    //If  "Add Category" is pressed, show up a add category form, do necessary validation on submission and insert a new category
    if ($_GET['category'] == 'add'){
      if (isset($_POST['add'])){
        if (empty($_POST['category_name'])){
          $log_error = true;
          $logMsg = 'Please enter a category name first';
        }else{
          $result = $pdo->prepare($query_categories_fetch_by_title);
          $criteria = ['title'=> $_POST['category_name']];
          $result->execute($criteria);
          if ($result->rowCount() > 0){
            $log_error = true;
            $logMsg = 'Category title conflicts with an existing category';
          }else{
            $addCategory = $pdo->prepare($query_insert_category);
            $criteria = ['category_title' => htmlspecialchars($_POST['category_name'])];
            $addCategory->execute($criteria);

            if ($addCategory){
              $log_success = true;
              $logMsg = 'Category added successfully';
            }else{
              $log_error = true;
              $logMsg = 'Category addition unsuccessful. PLease try again!';
            }
          }
        }
      }
      echo '<div class="title"><span>Add Category</span></div>';
?>
<!--  Category addition form-->
      <div class="insideBox adminLoginBox addBox">
        <form method="POST" action="">
          <?php
            if ($log_error) echo '<span><div class="log_div"> <i class="icon-blocked">&nbsp;' . $logMsg . '</i></div></span>';
            else if ($log_success) echo '<span><div class="log_div_success"> <i class="icon-checkmark2">&nbsp;' . $logMsg . '</i></div></span>';
           ?>
          <span><input type="text" name="category_name" placeHolder="Category Title (e.g Business)"/></span>
          <span><input type="submit" value="Add Category" name ="add"/></span>
        </form>
      </div>

<?php
  // If "Edit Category" is pressed, show up the table of categories to edit, edit the category title if validated and make changes to the data
    }else if ($_GET['category'] == 'edit'){
      echo '<div class="title"><span>Edit Category</span></div>';
      echo '<div class="insideBox adminLoginBox addBox">';
      if (isset($_POST['edit'])){
        if (empty($_POST['category_title'])){
          $log_error = true;
          $logMsg = 'Please enter a valid category name';
        }else{
          $result = $pdo->prepare($query_categories_fetch_by_title);
          $criteria = ['title'=> $_POST['category_title']];
          $result->execute($criteria);
          if ($result->rowCount() > 0){
            $log_error = true;
            $logMsg = 'Category title conflicts with an existing category';
          }else{
            $editCategory = $pdo->prepare($query_update_category);
            $criteria = ['category_id' => $_POST['category_id'], 'category_title' => htmlspecialchars($_POST['category_title'])];
            $editCategory->execute($criteria);
            if ($editCategory){
              $log_success = true;
              $logMsg = 'Category edited successfully';
            }else{
              $log_error = true;
              $logMsg = 'Category ammendment unsuccessful. PLease try again!';
            }
          }
        }
      }else if (isset($_POST['change'])){
        unset($_POST['change']);
        echo '<form method="POST" action="">';
        echo '<span><input type="hidden" name="category_id" value="'. $_POST['category_id'] .'"/></span>';
        unset($_POST['category_id']);
        echo '<span>'. $_POST['category_title'] . '</span>';
        foreach ($_POST as $key => $value){
          echo '<span><input type="text" name="'. $key .'" value="'. $value .'"/></span>';
        }
        echo '<span><input type="submit" value="Edit Category" name ="edit"/><input type="submit" value="Cancel" name ="cancel"/></span>';
        echo '</form>';
      }
      if ($log_error) echo '<span><div class="log_div"> <i class="icon-blocked">&nbsp;' . $logMsg . '</i></div></span>';
      else if ($log_success) echo '<span><div class="log_div_success"> <i class="icon-checkmark2">&nbsp;' . $logMsg . '</i></div></span>';
      echo '</div>';
    }

    // Code for deleting cateogry
    else if ($_GET['category'] == 'delete'){
      echo '<div class="title"><span>Delete Category</span></div>';
      echo '<div class="insideBox adminLoginBox addBox">';
      echo '<form method="POST" action="">';
      if (isset($_POST['change'])){
        echo '<span style="color:slateGray;font-weight:bolder;">Are you sure you want to delete the category &nbsp;<b>"' . $_POST['category_title'] . '"&nbsp; </b>?</span>';
        echo '<span><input type="hidden" name="category_id" value="'. $_POST['category_id'] .'"/><input type="hidden" name="category_title" value="'. $_POST['category_title'] .'"/></span>';
        echo '<span><input type="submit" name="delete" value="Yes"/><input type="submit" name="nodelete" value="No"/></span>';
      }else if (isset($_POST['delete'])){
        $result = $pdo->prepare($query_delete_category);
        $criteria = ['category_id' => $_POST['category_id']];
        $result->execute($criteria);

        if ($result){
          $log_success = true;
          $logMsg = 'Category "' . $_POST['category_title'] . '" deleted';
        }else{
          $log_error = true;
          $logMsg = 'Could not delete category "' . $_POST['category_title'] . '". Please try again!';
        }
      }
      if ($log_error) echo '<span><div class="log_div"> <i class="icon-blocked">&nbsp;' . $logMsg . '</i></div></span>';
      else if ($log_success) echo '<span><div class="log_div_success"> <i class="icon-checkmark2">&nbsp;' . $logMsg . '</i></div></span>';
      echo '</form></div>';
    }else{
      header('location: ../src/error.php');
    }

    echo '<div class="title"><span>Realtime Preview</span></div>';
    $menuLong = true;
    require_once '../src/menu.php';

    echo '<div class="title"><span>Tabular View</span></div>';
    $table = new TableGenerate();
    $table->setHeadings(['Category ID', 'Category Title']);

    $result = $pdo->prepare($query_categories_fetch);
    $result->execute();

    $row = $result->fetchAll(PDO::FETCH_ASSOC);
    foreach($row as $r){
      $table->addRow($r);
    }
    echo $table->getHTML($_GET['category']);
  }else{
    header('location: error.php');
  }
?>

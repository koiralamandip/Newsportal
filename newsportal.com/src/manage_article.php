<?php
  if ($entry_point != null){
    $log_error = false;
    $log_success= false;
    $logMsg = '';

    // Code for adding new article, add articles if validated
    if ($_GET['article'] == 'add'){
      if (isset($_POST['post'])){
        if (empty($_POST['heading']) || empty($_POST['detail'])){
          $log_error = true;
          $logMsg = 'All fields are mandatory';
        }else if (strlen($_POST['heading']) < 15){
          $log_error = true;
          $logMsg = 'Title must be at least 15 characters long';
        }else if (strlen($_POST['detail']) < 250){
          $log_error = true;
          $logMsg = 'Article must be at least 250 characters long';
        }else{
          $addArticle = $pdo->prepare($query_add_article);
          $criteria = [
            'heading' => $_POST['heading'],
            'detail' => $_POST['detail'],
            'publish_date' => date('y-m-d'),
            'category_id' => $_POST['category_id'],
            'user_id' => $_SESSION[$session_admin]
          ];
          $addArticle->execute($criteria);

          if($addArticle){
            $log_success = true;
            $logMsg = 'Article successfully posted';
          }else{
            $log_error = true;
            $logMsg = 'Could not post article. Please try again!';
          }
        }
      }
      echo '<div class="title"><span>Add Article</span></div>';
      if ($log_error) echo '<span><div class="log_div"> <i class="icon-blocked">&nbsp;' . $logMsg . '</i></div></span>';
      else if ($log_success) echo '<span><div class="log_div_success"> <i class="icon-checkmark2">&nbsp;' . $logMsg . '</i></div></span>';
      $article = new ArticleWriter();
      echo $article->getWriteBox($pdo, $query_categories_fetch, "Post Article", false);
    }

    // Code for editing articles
    else if ($_GET['article'] == 'edit'){
      echo '<div class="title"><span>Edit Your Article</span></div>';

      if (isset($_POST['post'])){
        if (empty($_POST['heading']) || empty($_POST['detail'])){
          $log_error = true;
          $logMsg = 'All fields are mandatory';
        }else if (strlen($_POST['heading']) < 15){
          $log_error = true;
          $logMsg = 'Title must be at least 15 characters long';
        }else if (strlen($_POST['detail']) < 250){
          $log_error = true;
          $logMsg = 'Article must be at least 250 characters long';
        }else{

          $editArticle = $pdo->prepare($query_edit_article);
          $criteria = [
            'detail' => $_POST['detail'],
            'heading' => $_POST['heading'],
            'category_id' => $_POST['category_id'],
            'id' => $_POST['news_id']
          ];
          $editArticle->execute($criteria);

          if($editArticle){
            $log_success = true;
            $logMsg = 'Article edited successfully';
          }else{
            $log_error = true;
            $logMsg = 'Could not edit article. Please try again!';
          }
        }
      }else if (isset($_POST['change'])){
        unset($_POST['change']);
        $article = new ArticleWriter();
        $article->setAttributes($_POST);
        echo $article->getWriteBox($pdo, $query_categories_fetch, "Edit Article", true);
      }
      if ($log_error) echo '<span><div class="log_div"> <i class="icon-blocked">&nbsp;' . $logMsg . '</i></div></span>';
      else if ($log_success) echo '<span><div class="log_div_success"> <i class="icon-checkmark2">&nbsp;' . $logMsg . '</i></div></span>';

    }
    //Code for deleting articles
    else if ($_GET['article'] == 'delete'){
      echo '<div class="title"><span>Delete Your Article</span></div>';
      echo '<div class="insideBox adminLoginBox addBox">';
      echo '<form method="POST" action="">';
      if (isset($_POST['change'])){
        echo '<span style="color:slateGray;font-weight:bolder;">Are you sure you want to delete the news &nbsp;<b>"' . $_POST['heading'] . '"&nbsp; </b>?</span>';
        echo '<span><input type="hidden" name="news_id" value="'. $_POST['news_id'] .'"/><input type="hidden" name="heading" value="'. $_POST['heading'] .'"/></span>';
        echo '<span><input type="submit" name="delete" value="Yes"/><input type="submit" name="nodelete" value="No"/></span>';
      }else if (isset($_POST['delete'])){
        $result = $pdo->prepare($query_delete_article);
        $criteria = ['news_id' => $_POST['news_id']];
        $result->execute($criteria);

        if ($result){
          $log_success = true;
          $logMsg = 'News deleted successfully';
        }else{
          $log_error = true;
          $logMsg = 'Could not delete the news. Please try again!';
        }
      }
      if ($log_error) echo '<span><div class="log_div"> <i class="icon-blocked">&nbsp;' . $logMsg . '</i></div></span>';
      else if ($log_success) echo '<span><div class="log_div_success"> <i class="icon-checkmark2">&nbsp;' . $logMsg . '</i></div></span>';
      echo '</form></div>';

    }else{
    header('location: ../src/error.php');
    }
    //Shw the table of articles
    echo '<div class="title"><span>Tablular view</span></div>';
    $fetch_news = $pdo->prepare($query_article_fetch_session_id);
    $criteria = ['id' => $_SESSION[$session_admin]];
    $fetch_news->execute($criteria);
    if ($fetch_news->rowCount() > 0){
      $articleEdit = new ArticleEditor();
      $articleEdit->setHeadings(['News Display']);
      foreach ($fetch_news as $row){
        $articleLister = new articleLister();
        $articleEdit->addRow($articleLister, $row);
      }
      echo $articleEdit->getChangeBox($_GET['article'], $pdo, $session_admin, $get_login, $get_signup, $query_comment_count);
    }else{
      echo '<span><b>You have no any articles associated</b></span>';
    }

  }else{
    header('location: error.php');
  }
 ?>

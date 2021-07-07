<?php
  if ($entry_point != null){
    $log_error = false;
    $log_success= false;
    $logMsg = '';


    // Code for showing messages and allowing to view and delete messages to ADMIN only
    if ($_GET['tag'] == 'message' && $_SESSION[$admin_role] == 'ADMIN'){
      echo '<div class="title"><span>Messages</span></div>';

      echo '<div class="insideBox adminLoginBox addBox">';
      echo '<form method="POST" action="">';
      if (isset($_POST['change'])){
        echo '<span style="color:slateGray;font-weight:bolder;">Are you sure you want to delete the message from';
        echo '<b>&nbsp;'. $_POST['message_fname'] . ' ' . $_POST['message_sname'] .'&nbsp;</b>?</span>';
        echo '<span><input type="hidden" name="message_id" value="'. $_POST['message_id'] .'"/>';
        echo '<span><input type="submit" name="delete" value="Yes"/><input type="submit" name="nodelete" value="No"/></span>';
      }else if (isset($_POST['delete'])){
        $result = $pdo->prepare($query_delete_messages);
        $criteria = ['message_id' => $_POST['message_id']];
        $result->execute($criteria);

        if ($result){
          $log_success = true;
          $logMsg = 'Message deleted';
        }else{
          $log_error = true;
          $logMsg = 'Could not delete the message at the moment. Please try again!';
        }
      }
      if ($log_error) echo '<span><div class="log_div"> <i class="icon-blocked">&nbsp;' . $logMsg . '</i></div></span>';
      else if ($log_success) echo '<span><div class="log_div_success"> <i class="icon-checkmark2">&nbsp;' . $logMsg . '</i></div></span>';
      echo '</form></div>';


      $table = new TableGenerate();
      $table->setHeadings(['Message ID', 'Firstname', 'Surname', 'Email', 'Detail', 'Date']);
      $messages = $pdo->prepare($query_fetch_messages);
      $messages->execute();
      if ($messages->rowCount() > 0){
        $message = $messages->fetchAll(PDO::FETCH_ASSOC);
        foreach($message as $messageData){
          $table->addRow($messageData);
        }
      }else{
        echo '<span><b>Inbox is empty</b></span>';
      }
      echo $table->getHTML('delete');

    }else if ($_GET['tag'] == 'comment'){
      if (isset($_POST['change'])){
        $publishable = '';
        switch ($_POST['publishable']){
          case 'YES':
          $publishable = 'NO';
          break;
          case 'NO':
          $publishable = 'YES';
          break;
        }

        $commentView = $pdo->prepare($query_comment_views);
        $criteria = ['publishable' => $publishable, 'comment_id' => $_POST['id']];
        $commentView->execute($criteria);
      }

      echo '<div class="title"><span>Comments on your news</span></div>';

      $news = $pdo->prepare($query_article_fetch_userid);
      $criteria = ['id' => $_SESSION[$session_admin]];
      $news->execute($criteria);
      if ($news->rowCount() > 0){
        foreach($news as $newsData){
          echo '<div class="title"><span>News: '. $newsData['heading'].'</span></div>';
          $comments = $pdo->prepare($query_fetch_comments);
          $criteria = ['p_id'=> 0, 'n_id' => $newsData['news_id']];
          $comments->execute($criteria);
          if ($comments->rowCount() > 0){
            $table = new TableGenerate();
            $table->setHeadings(['Comment Type', 'ID', 'Comment By', 'Posted date', 'Detail', 'Publishable']);
            foreach($comments as $commentData){
              $row = ['type' => 'Comment', 'id' => $commentData['comment_id'], 'user' => $commentData['username'],
                      'post_date' => $commentData['publish_date'], 'detail' => $commentData['detail'],
                      'publishable' => $commentData['publishable']];
              $table->addRow($row);

              $replies = $pdo->prepare($query_fetch_comments);
              $criteria = ['p_id'=> $commentData['comment_id'], 'n_id' => $newsData['news_id']];
              $replies->execute($criteria);
              if ($replies->rowCount() > 0){
                foreach($replies as $replyData){
                  $row = ['type' => 'Reply to ('. $commentData['comment_id'] .')', 'id' => $replyData['comment_id'], 'user' => $replyData['username'],
                          'post_date' => $replyData['publish_date'], 'detail' => $replyData['detail'],
                          'publishable' => $replyData['publishable']];
                  $table->addRow($row);
                }
              }
            }
            echo $table->getHTML($_GET['tag']);
          }else{
            echo 'No comments in this news';
          }
        }
      }else{
        echo 'No news to have comments in';
      }

    }else{
    header('location: ../src/error.php');
    }

  }else{
    header('location: error.php');
  }
 ?>

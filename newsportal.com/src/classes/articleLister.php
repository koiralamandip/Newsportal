<?php

  class ArticleLister{
    public $category;
    public $id;
    public $title;
    public $detail;
    public $author;
    public $publish_date;
    public $comment_count;

    public $canComment = false;

    public $session;
    public $login;
    public $signup;

    function setAttributes($article, $session_name, $get_login, $get_signup){
      $this->session = $session_name;
      $this->login = $get_login;
      $this->signup = $get_signup;

      $this->category = $article['category_title'];
      $this->id = $article['news_id'];
      $this->title = $article['heading'];
      $this->detail = nl2br($article['detail']);

      if (isset($_SESSION[$session_name]) && $_SESSION[$session_name] == $article['user_id']) $this->author = 'You';
      else  $this->author = $article['username'];

      $this->publish_date = $article['publish_date'];
    }

    function articleBox($content, $readMore, $titleLink, $count){
      $data = '';
      $data .= '<div class="article_box">';
      $data .= '<div class="article_category"> <h3>' . $this->category . '</h3></div>';
      $data .= '<div class="article_title">';

      if ($titleLink) $data .= '<a href="' . ROOT . '?news=' . $this->id . '"> <h2>' . $this->title . '</h2></a></div>';
      else $data .= '<h2>' . $this->title . '</h2></div>';

      $data .= '<div class="article_brief">' . $content;

      if ($readMore) $data .= '...<a href="' . ROOT . '?news=' . $this->id . '"> Read more </a></div>';
      else $data .= '</div>';

      $data .= '<div class="article_info">';
      $data .= '<div class="article_user"> <h3>Posted by</h3>' . $this->author . '</div>';
      $data .= '<div class="article_date"> <h3>Published on</h3>' . $this->publish_date . '</div>';
      $data .=  '</div>';


      if (!(isset($_GET[$this->login]) || isset($_GET[$this->signup]))){
        $data .= '<div class="article_controls">';
        $data .= '<i class="icon-like" id="likeBtn"></i> <i class="icon-dislike" id="dislikeBtn"></i>';
        $data .= '<i class="icon-bubbles3" onclick="window.location.href=\'' . ROOT . '?news=' . $this->id .'#comment_art\';" id="';

        if(isset($_SESSION[$this->session])){
          $this->canComment = true;
          if ($readMore){
            $data .= 'commentOnViewBtn';
          }
        }
        else{
          $data .= 'commentOnLoginBtn';
        }
        if ($count == 0) $count= '0';
        $data .= '">(' . $count . ')</i>';
        $data .= '<i class="icon-facebook2" id="shareBtn" onclick="window.location.href=\'https://www.facebook.com/sharer/sharer.php?u=http%3A//'. $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?news=' . $this->id .'\' ;"';
        $data .= '></i>';
        $data .= '</div>';
      }

      return $data;
    }

    function getSmallArticleBox($pdo, $counter_query){
      $counter = $pdo->prepare($counter_query);
      $criteria = ['n_id' => $this->id];
      $counter->execute($criteria);
      $count = $counter->fetch();
      $comment_count = $count['co'];

      $data = $this->articleBox(substr($this->detail, 0, 422), true, true, $comment_count);
      $data .= '</div>';

      return $data;
    }

    function getLargeArticleBox($pdo, $queryFetch, $queryInsert, $counter_query){
      $counter = $pdo->prepare($counter_query);
      $criteria = ['n_id' => $this->id];
      $counter->execute($criteria);
      $count = $counter->fetch();
      $comment_count = $count['co'];
      $log = false;
      $logMsg = '';
      $data = $this->articleBox($this->detail, false, false, $comment_count);

      if (isset($_POST['comment'])){
        if ($_POST['detail'] == ''){
          $log = true;
          $logMsg = 'Cannot process empty comments';
        }else{
          $insert = $pdo->prepare($queryInsert);
          $criteria = [
            'detail' =>  htmlspecialchars($_POST['detail']),
            'publish_date' => date('y-m-d'),
            'parent' => 0,
            'user_id' => $_SESSION[$this->session],
            'news_id' => $this->id
          ];
          $insert->execute($criteria);
        }
      }else if (isset($_POST['reply'])){
        if ($_POST['detail'] == ''){
          $log = true;
          $logMsg = 'Cannot process empty replies';
        }else{
          $insert = $pdo->prepare($queryInsert);
          $criteria = [
            'detail' => htmlspecialchars($_POST['detail']),
            'publish_date' => date('y-m-d'),
            'parent' => $_POST['parent'],
            'user_id' => $_SESSION[$this->session],
            'news_id' => $this->id
          ];
          $insert->execute($criteria);
        }
      }

      $data .= '<div class="article_comment" id="comment_art">';
      $data .= '<div class="comment_title">Comments</div>';
      if ($log) $data .= '<div class="log_div"><i class="icon-blocked"></i>'. $logMsg .'</div>';
      if ($this->canComment) $data .= '<form class="comment_box" method="POST" action=""><textarea name="detail" rows=1></textarea><input type="submit" name="comment" value="Post"/></form>';

      $fetch_comment = $pdo->prepare($queryFetch);
      $criteria = ['p_id' => 0, 'n_id' => $this->id];
      $fetch_comment->execute($criteria);
      $count = $fetch_comment->rowCount();
      if ($count > 0){
        $top_count = 0;
        foreach($fetch_comment as $comment){

          if ($comment['publishable'] == 'NO'){
            if (!isset($_SESSION[$this->session]) || $_SESSION[$this->session] != $comment['user_id']) { $top_count += 1; }
            else{
              $data .= '<div class="comment_top_unpublished">';
              $data .= '<div class="comment_user_unpublished"><h3>You <i class="icon-eye-blocked"> Your comment is under review. Please wait a while before it gets posted</i></h3>';
              $data .= '<span>' .  $comment['publish_date'] . '</span>';
              $data .= '</div>'; // comment_user
              $data .= '<div class="comment_text">' . nl2br($comment['detail']) .'</div></div>';
            }
          }else{
            $data .= '<div class="comment_top">';
            $data .= '<div class="comment_user"><a href="' . $_SERVER['REQUEST_URI']  .'&show_up='. $comment['user_id'] .'"><h3>';

            if (isset($_SESSION[$this->session]) && $_SESSION[$this->session] == $comment['user_id']) $data .= 'You';
            else  $data .= $comment['username'];

            $data .= '</h3></a>';
            $data .= '<span>' .  $comment['publish_date'] . '</span>';
            $data .= '</div>'; // comment_user
            $data .= '<div class="comment_text">' . nl2br($comment['detail']) .'</div>';

            if ($this->canComment) $data .= '<form class="comment_box" method="POST" action=""><input type="hidden" name="parent" value=" ' . $comment['comment_id'] . ' "/>
            <textarea name="detail" rows=1></textarea><input type="submit" name="reply" value="Reply"/></form>';

            $fetch_reply = $pdo->prepare($queryFetch);
            $criteria = ['p_id' => $comment['comment_id'], 'n_id' => $this->id];
            $fetch_reply->execute($criteria);
            $btmcount = $fetch_reply->rowCount();
            if ($btmcount > 0){
              $bottom_count = 0;
              foreach($fetch_reply as $reply){

                if ($reply['publishable'] == 'NO'){
                  if (!isset($_SESSION[$this->session]) || $_SESSION[$this->session] != $reply['user_id']) { $bottom_count += 1;}
                  else{
                    $data .= '<div class="comment_bottom_unpublished">';
                    $data .= '<div class="comment_user"><h3>You <i class="icon-eye-blocked"> Your reply is under review. Please wait a while before it gets posted</i></h3>';
                    $data .= '<span>' .  $reply['publish_date'] . '</span>';
                    $data .= '</div>'; // comment_user
                    $data .= '<div class="comment_text">' . nl2br($reply['detail']) .'</div>';
                    $data .= '</div>'; // comment_bottom
                  }
                }else{
                  $data .= '<div class="comment_bottom">';
                  $data .= '<div class="comment_user"><a href="'. $_SERVER['REQUEST_URI'] .'&show_up=' . $reply['user_id'] .'"><h3>';

                  if (isset($_SESSION[$this->session]) && $_SESSION[$this->session] == $reply['user_id']) $data .= 'You';
                  else  $data .= $reply['username'];

                  $data .= '</h3></a>';
                  $data .= '<span>' .  $reply['publish_date'] . '</span>';
                  $data .= '</div>'; // comment_user
                  $data .= '<div class="comment_text">' . nl2br($reply['detail']) .'</div>';
                  $data .= '</div>'; // comment_bottom
                }
              }
              if ($bottom_count == $btmcount) $data .= 'No replies yet';
            }else{
              $data .= 'No replies yet';
            }
            $data .= '</div>'; // comment_top
          }

        }
        if ($top_count == $count) $data .= 'No comments yet';
      }else{
        $data .= 'No comments yet';
      }

      $data .= '</div>'; // article_comment
      $data .= '</div>'; // article_box

      return $data;
    }

    function handleNoArticleException(){
      echo '<div class="errorDiv"> <i class="icon-warning"></i> <span> No article found.</span>
              <br><br>Some suggestions for you:<br>
              <ul>
                <li>Make sure all words are spelled correctly.</li>
                <li>Try different keywords.</li>
                <li>Try more general keywords.</li>
                <li>Try fewer keywords.</li>
              </ul>
             </div>';
    }
  }

?>

<?php
  if ( $entry_point != null){
?>
<main>
  <!-- HTML to generate a search form -->
  <div class="searcharea">
    <form class="searchform" method="POST" action="">
      <i class="icon-newspaper"></i>
      <input type="text" class="txtbox" name="matcher" placeholder="Search article"/>
      <select name="filter" style="background-color:#eee;border:none;">
        <option disabled>Match in</option>
        <option value="Title">Article Title</option>
        <option value="Content">Article content</option>
        <option value="Category">Article category</option>
        <option value="Author">Author</option>
      </select>
      <input type="submit" name="search" class="btn icon-search" value="&#xe986;"/>
    </form>
  </div>
  <div class="contentarea">

    <?php
      $noArticle = false; //If no/yes article is there to be fetched
      $allArticle = false; //If the page should/not display all news without a filter

      /*
      * If search button is clicked, it checks for search text.
      * If empty, it sets the flag allArticles to denote that all news, without a filter are to be shown
      * else it searches articles according to the filter selected and displays to the user.
      */
      if (isset($_POST['search'])){
        if ($_POST['matcher'] == ''){
          $allArticle = true;
        }else{
          $searcher = new SearchArticle();
          $searcher->setQueries($query_array_search);
          $fetch_article = $searcher->getRequiredQuery($pdo, $_POST['filter']);
          $criteria = [ 'data' => '%' . $_POST['matcher'] . '%' ]; //Search text convert to %Search text% for searching in database
          $fetch_article->execute($criteria);
          $count = $fetch_article->rowCount();
          if ($count > 0){
            $info = '<div class="resultCountDiv"> <i class="icon-search"></i> <span> Found ' . $count;
            if ($count == 1) $info .= ' article';
            else $info .= ' articles';
            $info .= ' | Newest first | Filter: containing "' . $_POST['matcher'] . '" in ' . $_POST['filter']. '</span></div>';

            echo $info;

            //Displaying news in news boxes
            foreach ($fetch_article as $article_record){
              $article = new ArticleLister(); //creating an object of a class
              $article->setAttributes($article_record, $session_name, $get_login, $get_signup);
              echo $article->getSmallArticleBox($pdo, $query_comment_count); // Get the small article box i.e article with less content for initial display
            }
          }else{
            //else display no news mesage (below)
            $noArticle = true;
          }
        }
      //If a get variable cat is deifned then display the news having the category id passed in the variable
      }else if (isset($_GET['cat'])){
        $searcher = new SearchArticle();
        //Check if the id passed through the get variable is in the cateogries table
        if ($searcher->matchIds($pdo, $_GET['cat'], $query_fetch_category_id, 'category_id')){
          //Fetch news from database
          $fetch_article = $pdo->prepare($query_article_fetch_cat_id);
          $criteria = ['id' => $_GET['cat']];
          $fetch_article->execute($criteria);
          $count = $fetch_article->rowCount();

          if ($count > 0){
            $info = '<div class="resultCountDiv"> <i class="icon-search"></i> <span> Found ' . $count;
            if ($count == 1) $info .= ' article';
            else $info .= ' articles';
            $info .= ' | Newest first | Filter: category "';

            //Fetch news' category name from database
            $name = $pdo->prepare($query_fetch_category_name);
            $crit = [ 'id' => $_GET['cat']];
            $name->execute($crit);
            $name = $name->fetch();
            $info .= $name['category_title'];
            $info .= '"</span></div>';

            echo $info;

            foreach($fetch_article as $article_record){
              //Display news in news boxes
              $article = new ArticleLister();
              $article->setAttributes($article_record, $session_name, $get_login, $get_signup);
              echo $article->getSmallArticleBox($pdo, $query_comment_count);
            }
          }else{
            $noArticle = true;
          }
        }else{
          header('location: ' . SRC_PATH . 'error.php');
        }

      //If a get variable 'tag' is defined and if the tag is 'latest', then show all news (latest news first) on the screen
      }else if (isset($_GET['tag']) && $_GET['tag'] == 'latest'){
        $allArticle = true;
      //If a get variable 'news' is set, display the total news content of the news on the screen along with comment areas
      }else if (isset($_GET['news'])){
        $searcher = new SearchArticle();
        if ($searcher->matchIds($pdo, $_GET['news'], $query_fetch_news_id, 'news_id')){
          $fetch_article = $pdo->prepare($query_article_fetch_news_id);
          $criteria = ['id' => $_GET['news']];
          $fetch_article->execute($criteria);
          $count = $fetch_article->rowCount();
          if ($count == 1){
            $article_record = $fetch_article->fetch();
            $article = new ArticleLister();
            $article->setAttributes($article_record, $session_name, $get_login, $get_signup);
            echo $article->getLargeArticleBox($pdo, $query_fetch_comments, $query_insert_comments, $query_comment_count);
          }else{
            $noArticle = true;
          }
        }else{
          $noArticle = true;
        }

        //If a get variable 'show_up' is set, (happens when a user is clicked from comment section), then show all the comments the specific user has made in any news
        if (isset($_GET['show_up'])){
          echo '<div class="extraLayer"></div>';
          echo '<div class="insideBox" id="comment_user">';
          $fetchCommentByUser = $pdo->prepare($query_fetch_comments_show_up);
          $criteria = ['u_id' => $_GET['show_up']];
          $fetchCommentByUser->execute($criteria);

          //Remove duplicate values resulted from database query (cite)
          $commentSingle = $fetchCommentByUser->fetchAll(PDO::FETCH_ASSOC);
          // Generate a table to show the comments made by the user selected from comment section
          $table = new TableGenerate();
          $table->setHeadings(['Comment By', 'Comment', 'Post Date', 'Comment for the news']);
          foreach($commentSingle as $row){
            $table->addRow($row); //Adding rows (record of comments) to the table generator
          }
          echo $table->getHTML('add'); // displaying the table (output)
          echo'</div>';
        }
      }else{
        $allArticle = true;
      }

      //Display all the articles (by default) if no other options are set up
      if ($allArticle){
        $fetch_article = $pdo->prepare($query_article_fetch_all);
        $fetch_article->execute();
        $count = $fetch_article->rowCount();
        if ($count > 0){
          $info = '<div class="resultCountDiv"> <i class="icon-search"></i> <span> Found ' . $count;
          if ($count == 1) $info .= ' article';
          else $info .= ' articles';
          $info .= ' | Newest first | Filter: None ... (Home/Latest Article)</span></div>';

          echo $info;

          foreach ($fetch_article as $article_record){
            //Display the news in news boxes
            $article = new ArticleLister();
            $article->setAttributes($article_record, $session_name, $get_login, $get_signup);
            echo $article->getSmallArticleBox($pdo, $query_comment_count);
          }
        }else{
          $noArticle = true;
        }
      }

      if ($noArticle){
        //If no article flag is set, then display 'NO ARTICLE FOUND' error...
        $no_article = new ArticleLister();
        $no_article->handleNoArticleException();
      }
    ?>
  </div>
</main>

<?php
  }else{
    header('location: error.php');
  }
?>

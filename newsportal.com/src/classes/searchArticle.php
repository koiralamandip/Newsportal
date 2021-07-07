<?php
  /*
  * This class is mainly used for searching the news articles according to the search criteria
  */
  class SearchArticle{
    public $query;
    public $result;

    //Sets an array of queries used for searching news articles (written in conf-query.php file)
    function setQueries($queries){
      $this->query = $queries;
    }

    /*
    * It returns a matching query to be executed, according to the supplied filter while searching news articles
    */
    function getRequiredQuery($pdo, $filter){
      if ($filter == 'Content'){
        $this->result = $pdo->prepare($this->query['detail']);
      }else if ($filter == 'Title'){
        $this->result = $pdo->prepare($this->query['title']);
      }else if ($filter == 'Category'){
        $this->result = $pdo->prepare($this->query['category']);
      }else if ($filter == 'Author'){
        $this->result = $pdo->prepare($this->query['author']);
      }
      return $this->result;
    }

    /*
    * It is used for checking if any values passed to, for example, $_GET array through URL is valid is not from database;
    * Example : If a user manually enters index.php?news=555444333222, then this method is used to check that the supplied $_GET['news']
    * value is not a valid/ has no entry in database;
    */
    function matchIds($pdo, $id, $query, $idString){
      $result = $pdo->prepare($query);
      $result->execute();
      $answer = true;
      foreach ($result as $d_id){
        if ($d_id[$idString] == intval($id)){
          $answer = true;
          break;
        }else{
          $answer = false;
        }
      }
      return $answer;
    }

  }
?>

<?php

  class ArticleEditor{
    public $headings;
    public $articleLister;
    public $rows = [];

    public function setHeadings($heading){
      $this->headings = $heading;
    }

    public function addRow($articleLister, $row){
      $this->rows[] = $row;
      $this->articleLister = $articleLister;
    }

    public function getChangeBox($action, $pdo, $session_admin, $get_login, $get_signup, $query_comment_count){
      $result = '<div class="articleChange">';
      $result .= '<table class="admin_table"><thead><tr>';
      foreach($this->headings as $heading){
        $result .= '<th>' . $heading . '</th>';
      }
      if ($action != 'add') $result .= '<th>Action</th>';
      $result .= '</tr></thead><tbody>';
      foreach($this->rows as $row){
        $result .= '<tr>';
        if ($action != 'add') $result .= '<form method="POST" action="">';
        $result .= '<td class="newsEditTD">';

        $this->articleLister->setAttributes($row, $session_admin, $get_login, $get_signup);
        $result .= $this->articleLister->getSmallArticleBox($pdo, $query_comment_count);

        $result .= '</td>';
        if ($action == 'edit'){
          $result .= '<td><input type="submit" name="change" class="icon-pencil2" value="&#xe906;"/></td>';
        }else if ($action == 'delete'){
          $result .= '<td><input type="submit" name="change" class="icon-delete" value="&#xe9ac;"/></td>';
        }
        foreach($row as $key=> $value){
          $result .= '<input type="hidden" name="'. $key .'" value="'. $value .'"/>';
        }
        $result .= '</form></tr>';
      }
      $result .= '</tbody></table></div>';
      return $result;
    }
  }

?>

<?php
  class TableGenerate{
    public $headings;
    public $rows = [];

    public function setHeadings($heading){
      $this->headings = $heading;
    }

    public function addRow($row){
      $this->rows[] = $row;
    }

    public function getHTML($action){
      $result = '<div class="tab">';
      $result .= '<table class="admin_table"><thead><tr>';
      foreach($this->headings as $heading){
        $result .= '<th>' . $heading . '</th>';
      }
      if ($action != 'add') $result .= '<th>Action</th>';
      $result .= '</tr></thead><tbody>';
      foreach($this->rows as $row){
        $result .= '<tr>';
        if ($action != 'add') $result .= '<form method="POST" action="">';
        foreach($row as $key=> $value){
          $result .= '<td><input type="hidden" name="'. $key .'" value="'. $value .'"/>' . $value .'</td>';
        }
        if (isset($row['user_id']) && ($row['user_id'] == $_SESSION['loggedAdminID'] || $row['user_id'] == 1));
        else{
          if ($action == 'edit'){
            $result .= '<td><input type="submit" name="change" class="icon-pencil2" value="&#xe906;"/></form> </td>';
          }else if ($action == 'delete'){
            $result .= '<td><input type="submit" name="change" class="icon-delete" value="&#xe9ac;"/></form> </td>';
          }else if ($action == 'comment'){
            if ($row['publishable'] == 'NO') $result .= '<td><input type="submit" name="change" class="icon-eye-blocked" value="&#xe9d1;"/></form> </td>';
            else $result .= '<td><input type="submit" name="change" class="icon-eye" value="&#xe9ce;"/></form> </td>';
          }
        }
        $result .= '</tr>';
      }
      $result .= '</tbody></table></div>';
      return $result;
    }
  }
 ?>

<?php
  class ArticleWriter{
    public $heading = '';
    public $detail = '';
    public $category_id = '';
    public $news_id = '';

    function setAttributes($array){
      $this->heading = $array['heading'];
      $this->detail = $array['detail'];
      $this->category_id = $array['category_id'];
      $this->news_id = $array['news_id'];
    }

    function getWriteBox($pdo, $query_categories_fetch, $submitName, $cancel){
      $data = '';
      $data .= '<div class="article_add_div">';
      $data .= '<form method="POST" action="" class="writer_form">';
      $data .= '<div class="article_data_add">';
      $data .= '<input type="text" name="heading" value="' . $this->heading . '" placeHolder="Article Title"/>';
      $data .= '<select name="category_id">';
      $category_list = $pdo->prepare($query_categories_fetch);
      $category_list->execute();
      $selected = '';
      foreach($category_list as $row){
        if ($row['category_id'] == $this->category_id) $data .= '<option value="' . $row['category_id'] . '" selected>' . $row['category_title'] . '</option>';
        else $data .= '<option value="' . $row['category_id'] . '">' . $row['category_title'] . '</option>';
      }
      $data .= '</select>';
      $data .=  '<input type="file" name="photo" id="photo" accept="image/jpeg,image/png"/>';
      $data .= '<input type="submit" name="post" value="'. $submitName .'"/>';
      if ($cancel) $data .= '<input type="submit" name="cancel" value="Cancel"/>';
      $data .= '</div>';
      $data .= '<div class="article_detail_add">';
      $data .= '<textarea name="detail" rows="10">'. $this->detail .'</textarea>';
      $data .= '<input type="hidden" name="news_id" value="' . $this->news_id . '">';
      $data .= '</div>';
      $data .= '</form></div>';
      return $data;
    }

  }
?>

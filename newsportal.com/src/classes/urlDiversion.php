<?php

  class UrlDiversion{

    function getBackURL($unset_key){
      if (isset($_GET[$unset_key])) unset($_GET[$unset_key]);
      $query_string = '';
      if (count($_GET) > 0) {
        $query_string .= '?';
        foreach($_GET as $string => $value) { $query_string .= $string . '=' . $value . '&';}
      }
      return $query_string;
    }
  }
 ?>

<?php
  if ( $entry_point != null){
?>
<!-- Footer -->
<footer>
  &copy; Northampton News <?php echo "(" . date('Y') . ")";?>
</footer>

<?php
  }else{
    header('location: error.php');
  }
?>

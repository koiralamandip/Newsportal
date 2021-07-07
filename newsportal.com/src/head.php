<?php
  if ( $entry_point != null){
?>
<!DOCTYPE html>
<html>
  <head>
    <title>News Portal</title>

    <!-- setting the page width as device width and scale to fit the display-->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- character encoding of the page is set to UTF-8-->
    <meta charset="UTF-8">

    <!-- setting a favicon for the page-->
    <link rel="icon" href= "<?php echo RES_PATH; /* RES_PATH is defined in config-path.php*/?>images/iconfav.png" type="image/png">

    <!-- linking stylesheets for cascading styles and fonts in the page-->
    <link rel="stylesheet" href= "<?php echo RES_PATH; ?>styles/index.css">
    <link rel="stylesheet" href= "<?php echo RES_PATH; ?>styles/icofont.css">
  </head>
  <body>
<?php
  }else{
    //If this page is directly viewed via url, redirect to error.php page
    header('location: error.php');
  }

?>

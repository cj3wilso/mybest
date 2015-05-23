<?php
header("HTTP/1.0 404 Not Found");
$page = "404";
include("global.php");
include("header.php");
?>
  
  <!-- Jumbotron -->
  <div class="jumbotron">
    <h1>Page Not Found!</h1>
    <p class="lead">Great. Now what?</p>
    <p>Go to the <a href="<?php echo $home; ?>">homepage</a>, use the search in the topbar or do a monkey dance.</p>
    <br />
    <p class="lead">How come the page wasn't found?</p>
    <p>The listing could have been removed from our website. Also don't forget to check for typos!</p>
    </div>
    
<?php
include 'footer.php';
include 'footer_js.php';
?>
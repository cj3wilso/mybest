<?php
if ( !isset($_SESSION['LAST_ACTIVITY']) || (time() - $_SESSION['LAST_ACTIVITY'] > $_SESSION['LOG_TIME']) ) {
    // last request was more than variable log time
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
	header("Location: $adminLogin");
}

?>
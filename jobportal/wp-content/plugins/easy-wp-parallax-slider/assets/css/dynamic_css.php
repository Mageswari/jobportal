<?php
header("Content-type: text/css");
if(!session_id())
    session_start();
echo '@import "http://fonts.googleapis.com/css?family='.$_SESSION['enqueue_script'].'"';
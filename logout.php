<?php
include('./common/connection.php');
include('./common/session.php');

session_start();
$_SESSION = array();
session_destroy();
header("location: login.php?msg=Logout success");

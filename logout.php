<?php

/* 
 * File name: logout.php
 * Purpose: logout the user by wiping out the cookie and destroying the session
 * Authors: Team 112 - Luke Buthman, Paul Huff, Teri Kieffer
 * Date: Spring Semester 2019
 */

session_start();

// the cookie that holds the session id is destroyed
// set the cookie to a time in the past
if (isset($_COOKIE[session_name()]))
{
    setcookie(session_name(),"",time()-3600); //destroy the session cookie on the client
}

// wipe out session info
$_SESSION = array(); // unset or remove all data from the $_SESSION array
session_destroy(); //erase session data from the disk
session_write_close(); // make sure the changes are committed

// redirect user back to home page
header('Refresh: 2; URL=home.php');

echo '<h2>Thank you for logging out.  You will now be redirected to our home page.</h2>';

die();
?>
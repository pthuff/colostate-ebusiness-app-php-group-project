<?php

/* 
 * File name: loginCheck.php
 * Purpose: checks whether the user has been authenticated
 * Authors: Team 112 - Luke Buthman, Paul Huff, Teri Kieffer
 * Date: Spring Semester 2019
 */

session_start();

// if the session array element, "userInfo" is not set,
// the user is redirected to the login page (login.php)

if (!isset($_SESSION['userInfo']))
{
    /*
     * redirect sends them directly the page they were trying to get to after logging in
     */
    header('location: login.php?redirect=' . $_SERVER['PHP_SELF']);
    die();
}
?>

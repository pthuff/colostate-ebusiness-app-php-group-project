<?php

/* 
 * File name: loginValid.php
 * Purpose: authenticate user
 * Authors: Team 112 - Luke Buthman, Paul Huff, Teri Kieffer
 * Date: Spring Semester 2019
 */

   require_once ("siteCommon.php");
   require_once ("recLeagueSql.php");

    
// declare an array to hold the validation error messages
$error = array();

// the ternary operator (similar to an in-line IF statement) is used to set the variable, $username
$username = isset($_POST['username']) ? trim($_POST['username']) : '';

// if $username is empty, add an appropriate message to the $error array
if (empty($username))
{
    $error[] = urlencode('Please enter your username');
}

echo $username;

// the ternary operator (similar to an in-line IF statement) is used to set the variable, $loginPassword
$loginPassword = isset($_POST['password']) ? trim($_POST['password']) : '';

// if $loginPassword is empty, add an appropriate message to the $error array
if (empty($loginPassword))
{
    $error[] = urlencode('Please enter your password');
}

// if the $error array is NOT empty, redirect to the submitting page
// include the error messages (captured in the $error array) as a URL parameter (error)
// the join method is used to "glue" together each element in the $error array to form one string
// in the example below, each error message in the $error array is "glued" to the next with a <br /> tag

if (!empty($error))
{
    header('Location:login.php?error=' . join(urlencode('<br />'), $error));
    
    exit;
}
else
{
    require_once ("../siteCommon.php");

    // call the displayPageHeader method in siteCommon.php
    displayPageHeader("OK, You are In!");
}

// call the displayPageFooter method in siteCommon.php
displayPageFooter();
?>


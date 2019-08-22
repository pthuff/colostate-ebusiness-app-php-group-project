<?php

/* 
 * File name: login.php
 * Purpose: login page for officials to log in so they can add/edit/delete scores
 * Authors: Team 112 - Luke Buthman, Paul Huff, Teri Kieffer
 * Date: Spring Semester 2019
 */

session_start();

require_once ("siteCommon.php");
require_once ("recLeagueSql.php");

//check if user assigned in POST array. If it is, trim. If not, set blank
$username = (isset($_POST['username'])) ? trim($_POST['username']) : '';
$password = (isset($_POST['password'])) ? trim($_POST['password']) : '';

// if user came directly to login form, then refresh the page
$redirect = (isset($_REQUEST['redirect'])) ? $_REQUEST['redirect'] : 'login.php';

// if the form was submitted
if (isset($_POST['login']))
{
    //Call getUser method to check credentials
    $userList = getUser($username, $password);

    //array will either have 1 record (match) or 0 records (no match)
    if (count($userList)==1) //If credentials check out
    {
        extract($userList[0]);

        // assign user info to an array
        $userInfo = array('officialid'=>$officialid, 'fname'=>$fname, 'lname'=>$lname);

        // assign the array to a session array element
        $_SESSION['userInfo'] = $userInfo;
        session_write_close(); //typically not required; ensures that the session data is stored

        // redirect the user
        header('location:' . $redirect);
        die();
    }

    else // Otherwise, assign error message to $error
    {
        $error = 'Invalid login credentials. Please try again.';
    }
}

// call the displayPageHeader method in siteCommon.php
echo displayPageHeader('Login');

// check if session array has user info and if so, assign the first name, otherwise assign blank
$logFName = (isset($_SESSION['userInfo']))? $_SESSION['userInfo']['fname'] : "";   

// if the user is authenticated, customize greeting and display season dropdowns to add/edit/delete scores
if (!empty($logFName))
    {
        $loggedInText = <<<XYZ
            <section>
            <p>Welcome back the sports league, <strong>
XYZ;
        
        $loggedInText .= $logFName;
        
        $loggedInText .= <<<XYZ
            </strong>!</p>
            <p>Please select a season below to add, modify or delete games.</p>
            <p><a href="logout.php">Logout</a></p>
            </section>

            <section id="selectSeason">
        <hr />
        <h2>Submit a new score</h2>
        <form action="addScores.php" method = "post" name="SearchBySeason" id="SearchBySeason">
            <label for="season">Select Season:</label>
            <select name="seasonid" id="season">
                <option value=""></option>
XYZ;
        echo $loggedInText;
        
//Get current seasons list
$names = getCurrentSeasonList();

//Display current seasons in dropdown
foreach ($names as $aName) 
    {
    extract($aName); //extract the array elements
    echo '<option value="' . $seasonid . '">' . $name . '</option>';
    }

$seasonDropdownTeams = <<<XYZ
            </select>
            <p>
                <input name = "search" type="submit" value="Search Season" />
            </p>
        </form>
        <br>
        <hr />
        <h2>Edit or Delete an existing score</h2>
        <form action="editScores.php" method = "post" name="SearchGamesBySeason" id="SearchGamesBySeason">
            <label for="seasonGames">Select Season:</label>
            <select name="seasonid" id="seasonGames">
                <option value=""></option>
XYZ;
        echo $seasonDropdownTeams;
        
//Get current seasons list
$names = getCurrentSeasonList();

//display seasons in dropdown
foreach ($names as $aName) 
    {
    extract($aName); //extract the array elements
    echo '<option value="' . $seasonid . '">' . $name . '</option>';
    }

$seasonDropdownTeams2 = <<<XYZ
            </select>
            <p>
                <input name = "search" type="submit" value="Search Games" />
            </p>
        </form>
        <br>
    </section>    
XYZ;

echo $seasonDropdownTeams2;

        }
        
    else //if user is not logged in...
        
    {

// if error variable was set, display it
if (isset($error))
{
    echo '<div class="error">' . $error . '</div>';
}

//display login fields and callout message that officials need to log in to submit scores
$bodyText = <<<ABC
    <section id="main">
        <p>Officials, log in here first to submit game scores.</p>
        <form name="input" id="loginForm" action="login.php" method="post">
           <input type="hidden" name ="redirect" value ="
ABC;

$bodyText .= $redirect;

$bodyText .= <<<ABC
        " />
            <label for="username">Username:</label>
            <input type="email" id="username" name="username" value = "
ABC;

$bodyText .= $username;

$bodyText .= <<<ABC
   " autofocus="autofocus" maxlength=80 title="Please enter a valid Login ID" required/><br /><br />
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" maxlength=32 required/><br /><br />
            <input type="submit" value="Log in" name="login" />
        </form>
    </section>
    <section id="callout">
        <p><strong>Need help logging in?</strong></p>
        <p>Call the league office at:</p>
        <p>&#9742;&nbsp;<strong>303-555-1212</strong></p>
    </section>
ABC;
echo $bodyText;
    }

//display footer
echo displayPageFooter();   
?>

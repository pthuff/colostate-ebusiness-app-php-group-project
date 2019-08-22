<?php

/* 
 * File name: editScores.php
 * Purpose: display list of game scores to edit or delete
 * Authors: Team 112 - Luke Buthman, Paul Huff, Teri Kieffer
 * Date: Spring Semester 2019
 */

session_start();

// display any error messages for troubleshooting
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require_once ("loginCheck.php");
require_once ("siteCommon.php");
require_once ("recLeagueSql.php");

//checking if there is a key defined in the post
// if there is something there, grab the elements and set the cookie
if (isset($_POST['seasonid']))
{
    $seasonid =  $_POST['seasonid'];

    // cookies are set to expire 30 days from now (given in seconds)
    $expire = time() + (60 * 60 * 24 * 30);
    setcookie('seasonid', $seasonid, $expire);
}
// If a previous user is visting this page
//Check for cookie and set the values
elseif (isset($_COOKIE['seasonid']))
{
    $seasonid =  $_COOKIE['seasonid'];
}
// If a user is visiting this page for the first time
// Set values based on no cookie info
else
{
    $seasonid =  '';
}

// call the displayPageHeader method in siteCommon.php
echo displayPageHeader('Add Game Score');

//get the games in the season selected
$games = getGames($seasonid);

//display the table of games
$html = <<<HTM
<h1>Edit or Delete a Game Score</h1>        
<section> 
   <table id="allGames">
        <tr>
        <th>Game ID</th>
        <th>Season</th>
        <th>Date of Game</th>
        <th>Home Team</th>
        <th>Away Team</th>
        <th>Edit</th>
        <th>Delete</th>
HTM;
echo $html;

foreach ( $games as $game ) {
    extract($game);
    $output .= <<<ABC
    <tr>
        <td>
            $game[gameid]
        </td>
        <td>$game[name]</td>
        <td>
ABC;

    $output .= date('m-d-Y h:i A',(strtotime($game[datetime])));
    
    $output .= <<<ABC
    </td>
        <td>$game[hteamname]</td>
        <td>$game[ateamname]</td>
        <td>
            <a href="addScores.php?gamepk=$game[gameid]">[Edit]</a>
        </td>
        <td>
            <a onClick="javascript: return confirm('Please confirm deletion');" href="deleteScores.php?gamepk=$game[gameid]">[Delete]</a>
        </td>
    </tr>
ABC;
}
    echo $output; 
    echo "</table></section>";
// end table
    
    $buttons = <<<ABC
            <hr />
            <div class="buttonGroup">
            <input class="buttons" type="button" onclick="window.location.href='addScores.php'" value="Add a new score">
            <input class="buttons" type="button" onclick="window.location.href='login.php'" value="Return to officials page">
            </div>
ABC;
    echo $buttons;

//display footer
echo displayPageFooter();  
?>
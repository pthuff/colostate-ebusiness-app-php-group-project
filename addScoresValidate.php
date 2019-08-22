<?php

/* 
 * File name: addScoresValidate.php
 * Purpose: performs validation for the add/edit scores form
 * Authors: Team 112 - Luke Buthman, Paul Huff, Teri Kieffer
 * Date: Spring Semester 2019
 */

session_start();

// display any error messages for troubleshooting
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require_once ("recLeagueSql.php");

// declare an array to hold the validation error messages
$error = array();

// get the values from the form
$thisGameId = $_POST['gameid'];
$seasonId = $_POST['seasonid'];
$homeTeamId = $_POST['homeTeam'];   
$homeTeamScore = $_POST['homeScore'];
$awayTeamId = $_POST['awayTeam'];   
$awayTeamScore = $_POST['awayScore'];
$dateOfGame = date('Y-m-d\TH:i:s',(strtotime($_POST['selectDate']))); //format '2019-03-09T14:30:00'

// set OT flag based on checkbox selection
if(isset($_POST['ot'])){ 
    $otGame = 1; 
}else{ 
    $otGame = 0; 
}

// remove any potentially malicious characters - allow space, dash, apostrophe for names
$homeTeamScore = preg_replace("/[^0-9]/", '', $homeTeamScore);
$awayTeamScore = preg_replace("/[^0-9]/", '', $awayTeamScore);

//get game information for the season and check for two games at the same time
$gamesForSeason = getGames($seasonId);
foreach ($gamesForSeason as $game) {
    $dateDiff = abs(strtotime($dateOfGame) - strtotime($game['datetime']));
    if ($dateDiff < 3600 && !isset($_POST['gameid'])) {
        $error[] = urlencode('Cannot have two games at the same time');
        break;
    }
}
    
// check that the two teams selected are different
if ($homeTeamId == $awayTeamId) {
    $error[] = urlencode('A team cannot play against itself');
}

// get the sportid
$sports = getSportId($seasonId);
extract($sports[0]);
$sportId = $sportid;

//get official id stored in the session when the user logged in
$officialId = $_SESSION['userInfo']['officialid'];

// calculate the winner and set the variable
if ($homeTeamScore > $awayTeamScore)
{
    $winner = $homeTeamId;
}
else if ($homeTeamScore < $awayTeamScore)
{
    $winner = $awayTeamId;
}
else 
{
    $winner = NULL;    
}

//check if an error was set and display it
if (!empty($error))
{
    header('Location:addScores.php?error=' . join(urlencode('<br />'), $error));
    
    //exit;
}
else
{

// if $_POST has a gameid element, call the update method
if (isset($_POST['gameid']))
{
    editScore((int)$thisGameId,(int)$homeTeamId,(int)$awayTeamId,(int)$sportId,(int)$seasonId,
            (int)$officialId,(int)$winner, (int)$homeTeamScore,(int)$awayTeamScore,$otGame,$dateOfGame);
    $message = "<h1>You have successfully updated game $thisGameId.</h1>";
    header("Refresh: 2; editScores.php");
    echo $message;
    exit;
}
else //call the add method
{
    addScore((int)$homeTeamId,(int)$awayTeamId,(int)$sportId,(int)$seasonId,(int)$officialId,(int)$winner,
        (int)$homeTeamScore,(int)$awayTeamScore,$otGame,$dateOfGame);
    $message = "<h1>Game score added.</h1>";
    header("Refresh: 2; addScores.php");
    echo $message;
    exit;
}
}

?>


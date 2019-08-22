<?php

/* 
 * File name: deleteScores.php
 * Purpose: delete a game score from the database
 * Authors: Team 112 - Luke Buthman, Paul Huff, Teri Kieffer
 * Date: Spring Semester 2019
 */

session_start();

require_once ("loginCheck.php");
include_once ("recLeagueSql.php");

//check if there is a game key in the URL and that it's numeric, then delete the game
if ((isset($_GET['gamepk'])) && (is_numeric($_GET['gamepk'])))
{
    deleteScore((int)$_GET['gamepk']);
}

//display confirmation message and go back to edit scores page
$message = "Game " . $_GET['gamepk'] . " deleted";
header("Refresh: 2; editScores.php");
echo "<h1>$message</h1>";
exit;

<?php

/* 
 * File name: recLeagueSql.php
 * Purpose: this file holds all of the SQL queries for the website
 * Authors: Team 112 - Luke Buthman, Paul Huff, Teri Kieffer
 * Date: Spring Semester 2019
 */

// display any error messages for troubleshooting
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require_once 'dbConnect.php';

//get the list of sports
function getSportList()
{

    $query = "Select name
            From sport
            Order by name";
   
   return executeQuery($query);
   
}

//get a list of seasons
function getSeasonList()
{
    $query = <<<STR
Select seasonid, name
From season
Order by seasonid
STR;

    return executeQuery($query);
}

//get the list of seasons currently being played
function getCurrentSeasonList()
{
    $query = <<<STR
Select seasonid, name
From season
WHERE GETDATE() BETWEEN sdate AND edate
Order by seasonid
STR;

    return executeQuery($query);
}

//get a season's start and end dates
function getSeasonDates($seasonid)
{
    $query = <<<STR
            Select sdate, edate
            From season
            WHERE seasonid = $seasonid
STR;
    
    return executeQuery($query);
}

//get a season's standings
function getSeasonStandings($seasonID)
{
    $query = <<<STR
Select seasonid, hteamid, ateamid, winner, hscore, ascore, ot, datetime
From game
where seasonid = $seasonID
STR;
    return executeQuery($query);
}

//get season records by multicriteria
function getSeasonStandingsMC($seasonID,$teamName)
{
    $query = <<<STR
SELECT DISTINCT gameid, seasonid, hteamid, ateamid, winner, hscore, ascore, ot, datetime
FROM game
LEFT JOIN team ON team.teamid = game.hteamid OR team.teamid = game.ateamid
WHERE seasonid = $seasonID
STR;
    if ($teamName != '')
    {
    $query .= <<<STR
AND team.name like '%$teamName%'
STR;
    }
$query .= <<<STR
ORDER BY datetime
STR;

return executeQuery($query);

}

//get a list of teams
function getTeamList()
{
    $query = <<<STR
Select teamid, coachid, name
From team
Order by teamid
STR;
   return executeQuery($query);  
}

//get the teams list for a single season
function getTeamListSeason($seasonid)
{
    $query = <<<STR
Select team.teamid, team.name
From team
LEFT JOIN season_team ON season_team.teamid = team.teamid
Where season_team.seasonid = $seasonid
STR;
   return executeQuery($query);  
}

//get a list of coaches
function getCoachList()
{

    $query = "Select coachid, fname, lname
            From coach
            Order by lname";
   
   return executeQuery($query);
   
}

//find user for login
function getUser($username, $password)
{
    $query = <<<STR
Select officialid, fname, lname
From official
Where email = '$username' and password = '$password'
STR;

return executeQuery($query);

}

// add a new game score
function addScore($homeTeamId,$awayTeamId,$sportId,$seasonId,$officialId,$winner,
        $homeTeamScore,$awayTeamScore,$otGame,$dateOfGame)
{
    if ($winner > 0) { //query when there is a winner
    $query = <<<STR
    INSERT INTO game(hteamid,ateamid,sportid,seasonid,officialid,winner,hscore,ascore,ot,datetime)
            VALUES($homeTeamId,$awayTeamId,$sportId,$seasonId,$officialId,$winner,
        $homeTeamScore,$awayTeamScore,$otGame,'$dateOfGame')
STR;
    } else {  //query when there is a tie 
    $query = <<<STR
INSERT INTO game(hteamid,ateamid,sportid,seasonid,officialid,winner,hscore,ascore,ot,datetime)
            VALUES($homeTeamId,$awayTeamId,$sportId,$seasonId,$officialId,null,
        $homeTeamScore,$awayTeamScore,$otGame,'$dateOfGame')    
STR;

}
    executeQuery($query);
}

// edit a game score
function editScore($gameid, $homeTeamId,$awayTeamId,$sportId,$seasonId,$officialId,$winner,
        $homeTeamScore,$awayTeamScore,$otGame,$dateOfGame)
{
    if ($winner > 0) { //query when there is a winner
    $query = <<<STR
        UPDATE game 
        SET hteamid = $homeTeamId, ateamid = $awayTeamId, sportid = $sportId,
        seasonid = $seasonId, officialid = $officialId, winner = $winner,
        hscore = $homeTeamScore, ascore = $awayTeamScore, 
        ot = '$otGame', datetime = '$dateOfGame' 
        WHERE gameid = $gameid
            
STR;
    } else { //query when there is a tie 
    $query = <<<STR
        UPDATE game 
        SET hteamid = $homeTeamId, ateamid = $awayTeamId, sportid = $sportId,
        seasonid = $seasonId, officialid = $officialId, winner = null,
        hscore = $homeTeamScore, ascore = $awayTeamScore, 
        ot = '$otGame', datetime = '$dateOfGame' 
        WHERE gameid = $gameid
STR;
    }

    executeQuery($query);

}

// delete a game score
function deleteScore($gameid) {
    $query = <<<STR
        Delete
        From game
        Where gameid = $gameid
STR;

    executeQuery($query);
}

// get sport id for a single season
function getSportId($seasonId)
{
    $query = <<<STR
Select sportid
From season_sport
Where seasonid = $seasonId
STR;
   return executeQuery($query);  
}

// get game list for editing
function getGames($seasonid)
{
    $query = <<<STR
   Select game.gameid, 
       game.seasonid, 
       game.datetime,
       game.hteamid, 
       game.ateamid,
       season.name, 
       hteam.name as hteamname,
       ateam.name as ateamname
From game
LEFT JOIN season ON season.seasonid = game.seasonid
LEFT JOIN team as hteam ON game.hteamid = hteam.teamid
LEFT JOIN team as ateam ON game.ateamid = ateam.teamid
Where game.seasonid = $seasonid
STR;
   return executeQuery($query);  
}

// get game info for editing
function getGameDetails($gamepk)
{
    $query = <<<STR
Select *
From game
Where gameid = $gamepk
STR;
   return executeQuery($query);  
}

// get game dates
function getGameDates($dateOfGame)
{
    $query = <<<STR
Select gameid, seasonid, datetime
From game
Where datetime like ('$dateOfGame')
STR;

    return executeQuery($query);
}

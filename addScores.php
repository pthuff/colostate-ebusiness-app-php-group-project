<?php

/* 
 * File name: addScores.php
 * Purpose: page where officials can add a score; 
 *          also used for editing a score by prepopulating the form
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

// declare and initialize Add/Edit flag variable
$editmode = false;

//checking if there is a season defined in the post
// if there is something there, grab the elements and set the cookie
// this is capturing the season selection from the login page and storing it in a cookie
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

//check if an error was passed and set the error variable
if (isset($_GET['error']))
{
    $error = $_GET['error'];
}

// get the season's teams to populate the list box
$teamList = getTeamListSeason($seasonid);  

// get the season dates to restrict to current season's dates on form
$seasonDates = getSeasonDates($seasonid);
extract($seasonDates[0]);
$minDate = $sdate;
$maxDate = date('Y-m-d');
 
// if a numeric gamepk was passed through the URL
// this will be used for editing/deleting game scores - the gamepk will get passed through the URL
// when the game is selected from the editScores page
if ((isset($_GET['gamepk'])) && (is_numeric($_GET['gamepk'])))
{
    // get the details for the game to be edited
    $gameDetails = getGameDetails((int)$_GET['gamepk']);

    // if game details are returned for the gamepk, set $editmode to true
    $editmode = (count($gameDetails) == 1);
}

// if $editmode is true
if ($editmode)
{
    //get the game info and set the variables to prepopulate the form
    extract($gameDetails[0]);
    $selectDate = date('Y-m-d\TH:i:s',(strtotime($datetime)));
    $homeTeam = $team[$hteamid];
    $homeScore = $hscore;
    $awayTeam = $team[$ateamid];
    $awayScore = $ascore;
    
    if ($ot) {
        $otSelection = <<<ABC
            <input type="checkbox" name="ot" value="true" checked> Check if the game went overtime
ABC;
    }
    else {
        $otSelection = <<<ABC
            <input type="checkbox" name="ot" value="false"> Check if the game went overtime
ABC;
    }
    

    $heading = 'Update a Game Score';
    $buttontext = 'Update score';
    
 }
else  //otherwise, set the column variables to "" so form is blank for adding a new score
{
    $selectDate = date('Y-m-d\TH:i'); //set default date to today's date
    $homeTeam = $team[0];
    $homeScore = '';
    $awayTeam = $team[0];
    $awayScore = '';
    $otSelection = <<<ABC
        <input type="checkbox" name="ot" value="false"> Check if the game went overtime
ABC;

    $heading = 'Add a Game Score';
    $buttontext = 'Add score';
}

// call the displayPageHeader method in siteCommon.php
echo displayPageHeader('Add Game Score');
?>

<!-- display the appropriate header for add/edit/delete then display the form -->
<h1><?php echo $heading;?></h1>
<?php
// if error variable was set, display it
if (isset($error))
{
    echo '<div class="error">' . $error . '</div>';
}
?>
<!-- This is the form section -->
<section id="addScore">
    <form action="addScoresValidate.php" method="post" name="addScore" id="addScore" onsubmit="return checkForm(this)" class="addScore">
<?php
        //store the season id in a hidden field so it's available for the sql query
        echo '<input type="hidden" name="seasonid" value="' . $seasonid . '" />';
        
        //if editing, store the game id in a hidden field so it's available for the sql query
        if ($editmode) {
            echo '<input type="hidden" name="gameid" value="' . $gameid . '" />';            
        }
?>
        <p class="col1">
            <label for="selectDate">Date of game:</label><br />
            <input name="selectDate" type="datetime-local" value="<?php echo $selectDate; ?>" required title="Please select a valid date" min="<?php echo $minDate; ?>" max="<?php echo $maxDate; ?>"/><br /><br />
        </p>
        <p class="col2">
            <label for="homeTeam">Home team:</label><br />
            <select name="homeTeam" id="homeTeam" required title="Please select a team">
                <?php
                extract($gameDetails[0]);
                $outputTeams = "";
                if ($editmode) {
                    foreach ($teamList as $thisTeam) {
                        $selected = $thisTeam[teamid] == $hteamid ? 'selected' : '';
                        $outputTeams .= <<<HTML
                        <option value="$thisTeam[teamid]" 
                        $selected
                        >$thisTeam[name]</option>
HTML;
                    } 
                }
                else {
                    $outputTeams .= <<<ABC
                    <option value="0"></option>        
ABC;
                    foreach ($teamList as $team) {
                        $outputTeams .= <<<HTML
                    <option value="$team[teamid]">$team[name]</option>
HTML;
                    }
                }
                echo $outputTeams;
                ?>
                
        </select>
        </p>
        <p class="col3">
            <label for="addScore">Home score: </label><br />
            <input name="homeScore" type="text" pattern="[0-9]+" value="<?php echo $homeScore; ?>" required title="Please enter a numeric score"/>
        </p>
        <p class="col2">
            <label for="addScore">Away team: </label><br />
            <select name="awayTeam" id="awayTeam" value="<?php echo $awayTeam; ?>" required title="Please select a team">
            <?php
                extract($gameDetails[0]);
                $outputTeams = "";
                if ($editmode) {
                    foreach ($teamList as $thisTeam) {
                        $selected = $thisTeam[teamid] == $ateamid ? 'selected' : '';
                        $outputTeams .= <<<HTML
                        <option value="$thisTeam[teamid]" 
                        $selected
                        >$thisTeam[name]</option>
HTML;
                    } 
                }
                else {
                    $outputTeams .= <<<ABC
                    <option value="0"></option>        
ABC;
                    foreach ($teamList as $team) {
                        $outputTeams .= <<<HTML
                    <option value="$team[teamid]">$team[name]</option>
HTML;
                    }
                }
                echo $outputTeams;
                ?>
        </select>
        </p>
        <p class="col3">
            <label for="addScore">Away score: </label><br />
            <input name="awayScore" type="text" pattern="[0-9]+" value="<?php echo $awayScore; ?>" required title="Please enter a numeric score"/>
        </p>
        <p class="span2col">
            <?php
                echo $otSelection;
            ?>
        </p>
        <p class="span2col">
            <button class="subButton" type="submit"><?php echo $buttontext; ?></button>
        </p>
    </form>
    <hr />
<!-- End form section -->    
    <div class="buttonGroup">
        <input class="buttons" type="button" onclick="window.location.href='editScores.php'" value="Edit or delete scores">
        <input class="buttons" type="button" onclick="window.location.href='login.php'" value="Return to officials page">
    </div>

    </section>
        
<?php
    
// display footer
echo displayPageFooter();   
?>
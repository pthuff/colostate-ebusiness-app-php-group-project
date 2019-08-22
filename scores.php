<?php

/* 
 * File name: scores.php
 * Purpose: page to view team scores
 * Authors: Team 112 - Luke Buthman, Paul Huff, Teri Kieffer
 * Date: Spring Semester 2019
 */

require_once ("siteCommon.php");
require_once ("recLeagueSql.php");

//checking if there is a team name search term defined in the post
// if there is something there, grab the elements and set the cookie
// this is capturing the team search field and storing it in a cookie
if (isset($_POST['teamname']))
{
    $teamName =  $_POST['teamname'];

    // cookies are set to expire 1 hour from now (given in seconds)
    $expire = time() + (60 * 60);
    setcookie('teamname', $teamName, $expire);
}
// If a previous user is visting this page
//Check for cookie and set the values
elseif (isset($_COOKIE['teamname']))
{
    $teamName =  $_COOKIE['teamname'];
}
// If a user is visiting this page for the first time
// Set values based on no cookie info
else
{
    $teamName =  '';
}

//Get seasons list
$names = getSeasonList();
rsort($names);

// call the displayPageHeader method in siteCommon.php
echo displayPageHeader('Team Scores');
?>
<h1>Find Scores</h1>
<!-- display form to search by team name and select a season -->
<section>
    <form action="" method = "post" name="SearchBySeason" id="SearchBySeason">
        <label for="teamname">Enter a Team Name (Optional):</label>
        <input type="text" name="teamname" id ="teamname" maxlength="25" value="<?php echo $teamName; ?>" />
        <br><br>
        <label for="season">Select a Season:</label>
        <select name="seasonid" id="season" required>
            <option value=""></option>
    <?php
    foreach ($names as $aName)
        {
        extract($aName); //extract the array elements
        echo '<option value="' . $seasonid . '">' . $name . '</option>';
        }
    ?>
        </select>
        <p>
            <input name = "search" type="submit" value="Search" />
        </p>
    </form>
<!-- end of form -->

<?php
    $seasonID = $_POST['seasonid'];
    $teamName = $_POST['teamname'];
    
    //validate search fields entered then execute search
    if ($seasonID || $teamName != null)
    {
    $games = getSeasonStandingsMC($seasonID,$teamName);
    $teams = getTeamList();

    $seasonKey = array_search($seasonID, array_column($names, 'seasonid'));
    $season_name = $names[$seasonKey]['name'];
    
    If ($teamName != '')
    {
        $searchTerm = "Searched by: $teamName<br><br>";
    }
    //display search results in table
    $output = <<<ABC
    <table>
       <caption>$searchTerm$season_name</caption>
       <thead>
       <tr>
            <th align="center">Game Date</th>
            <th colspan="2" align="center">Home Team Score</th>
            <th colspan="2" align="center">Away Team Score</th>
            <th align="center">Overtime?</th>
            <th align="center">Winner</th>            
        </tr>
        </thead>
ABC;
    
    foreach ($games as $game) 
        {
        extract($game);
        $gameDate = date_format(new DateTime($datetime), "F j, Y");
        $homeKey = array_search($hteamid, array_column($teams, 'teamid'));
        $home = $teams[$homeKey]['name'];
        $awayKey = array_search($ateamid, array_column($teams, 'teamid'));
        $away = $teams[$awayKey]['name'];
        $winnerKey = array_search($winner, array_column($teams, 'teamid'));
        if ($hscore == $ascore)
        {
            $the_winner = 'Tie'; 
        }
        else
        {
            $the_winner = $teams[$winnerKey]['name'];
        }
        $overtime = $ot == 0 ? 'No' : 'Yes';
        
        $output .= <<<ABC
        
        <tr>
            <td>$gameDate</td>
            <td align="right">$home</td>
            <td>$hscore</td>
            <td align="right">$away</td>
            <td>$ascore</td>
            <td align="center">$overtime</td>
            <td align="center">$the_winner</td>
        </tr>
ABC;
        }
    $output .= "<tbody></table>";
    
    echo $output;
    }   
?>
</section>
    <br>
</section>
<!-- display callout for officials to log in to submit scores -->
    <section id="calloutLow">
        <p><strong>Are you an official looking to submit scores?</strong></p>
        <p>You must <a href=".\login.php">log in</a> to submit game scores.</p>
        <p>Call the league office if you need assistance.</p>
        <p>&#9742;&nbsp;<strong>303-555-1212</strong></p>
    </section>

 <?php
// call the displayPageFooter method in siteCommon.php
echo displayPageFooter();
?>



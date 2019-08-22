<?php

/* 
 * File name: standings.php
 * Purpose: page that displays league standings for a season
 * Authors: Team 112 - Luke Buthman, Paul Huff, Teri Kieffer
 * Date: Spring Semester 2019
 */

require_once ("siteCommon.php");
require_once ("recLeagueSql.php");

// call the displayPageHeader method in siteCommon.php
echo displayPageHeader('Team Standings');
?>
<!-- form to select a season -->
<section>
    <form action="" method = "post" name="SearchBySeason" id="SearchBySeason">
        <label for="season">Select Season:</label>
        <select name="seasonid" id="season">
            <option value=""></option>
    <?php
    $names = getSeasonList();
    rsort($names);
    foreach ($names as $aName) {
        extract($aName); //extract the array elements
        echo '<option value="' . $seasonid . '">' . $name . '</option>';
    }
    ?>
        </select>
        <p>
            <input name = "search" type="submit" value="Search" />
        </p>
    </form>
    <br>
</section>
<!-- end of form -->
<?php
    //get the season ID from the form
    $seasonID = $_POST['seasonid'];
    
    //validate a season is selected and get the information
    if ($seasonID != null)
    {
    $games = getSeasonStandings($seasonID);
    $teams = getTeamList();
    $coaches = getCoachList();
    
    $seasonKey = array_search($seasonID, array_column($names, 'seasonid'));
    $season_name = $names[$seasonKey]['name'];
    
    $output = <<<ABC
    <table>
       <caption>$season_name</caption>
       <thead>
       <tr>
            <th align="center">Rank</th>
            <th align="center">Team</th>
            <th align="center">Record</th>
            <th align="center">Coach</th>
            <th align="center">Win Percentage</th>            
        </tr>
        </thead>
ABC;
    
    // stores teams by name and win-loss-tie record
    // value stored as 100 for win, 10 for loss and 1 for tie
    // ex. TeamA has 321 -> 3 wins, 2 losses, and 1 tie
    $rankings = array();
    $coachByTeam = array();
    foreach ($games as $game) {
        extract($game);
        $homeKey = array_search($hteamid, array_column($teams, 'teamid'));
        $home = $teams[$homeKey]['name'];
        $awayKey = array_search($ateamid, array_column($teams, 'teamid'));
        $away = $teams[$awayKey]['name'];
        $winnerKey = array_search($winner, array_column($teams, 'teamid'));
        if ($coachByTeam[$home] == null) {
            $coachByTeam[$home] = $coaches[$teams[$homeKey]['coachid']];
        }
        if ($coachByTeam[$away] == null) {
            $coachByTeam[$away] = $coaches[$teams[$awayKey]['coachid']];
        }
        
        if ($hscore > $ascore) {
             $rankings[$home] += 100;
             $rankings[$away] += 10;
        }
        else if ($ascore > $hscore) {
            $rankings[$away] += 100;
            $rankings[$home] += 10;    
        }
        else {
            $rankings[$away] += 1;
            $rankings[$home] += 1;
        }
    }
    //format table
    array_multisort(array_values($rankings), SORT_DESC, array_keys($rankings), SORT_ASC, $rankings);
    $rank = 1;
    foreach ($rankings as $team => $record) {
        $record = (string)$record;
        $wins = substr($record ,0, 1);
        $losses = substr($record, 1, 1);
        $ties = substr($record, 2, 1);
        $gamesPlayed = $wins + $losses + $ties;
        $winPercentage = $wins / $gamesPlayed;
        $winPercentFormatted = number_format($winPercentage, 3);
        $firstname = $coachByTeam[$team]['fname'];
        $lastname = $coachByTeam[$team]['lname'];
        
        $output .= <<<ABC
        
        <tr>
            <td align="center">$rank</td>
            <td align="center">$team</td>
            <td align="center">$wins-$losses-$ties</td>
            <td align="center">$firstname $lastname</td>
            <td align="center">$winPercentFormatted</td>
        </tr>
ABC;
        $rank += 1;
    }
    $output .= "<tbody></table>";
    
    echo $output;
    }   
    //end of table
?>
</section>
<!-- callout reminding officials to log in if they need to submit scores -->
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
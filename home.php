<?php

/* 
 * File name: home.php
 * Purpose: display home page content
 * Authors: Team 112 - Luke Buthman, Paul Huff, Teri Kieffer
 * Date: Spring Semester 2019
 */

require_once ("siteCommon.php");
require_once ("recLeagueSql.php");

// call the displayPageHeader method in siteCommon.php
echo displayPageHeader('Local Sports Rec League');

// display body text
$homeText = <<<ABC
            <h1>Local Sports Recreation League</h1>
    <section id="main">
        <p>Welcome to our local sports rec league website. Here you can find team scores and league standings for our seasonal sports:</p>
        <ul>
            <li><a href="https://en.wikipedia.org/wiki/Basketball" target="_blank">Basketball</a></li>
            <li><a href="https://en.wikipedia.org/wiki/Flag_football" target="_blank">Flag football</a></li>
            <li><a href="https://www.usahockey.com/page/show/3684066-adult-classic-division-descriptions" target="_blank">Hockey</a></li>
            <li><a href="https://www.teamusa.org/usa-softball/play-usa-softball/adult-player-information" target="_blank">Softball</a></li>
        </ul>
        <p>All of our leagues are co-ed. Contact the league administrator for more information or to sign up: 303-555-1212 or <a href="mailto:Teri.Kieffer@colostate.edu">admin@localsportsrecleague.com</a>.</p>
    </section>
    <section id="callout">
        <p><strong>Are you an official looking to submit scores?</strong></p>
        <p>You must <a href=".\login.php">log in</a> to submit game scores.</p>
        <p>Call the league office if you need assistance.</p>
        <p>&#9742;&nbsp;<strong>303-555-1212</strong></p>
    </section>
    <section id="about">
        <h2>About our league</h2>
        <p>We offer co-ed adult recreation sports leagues for members of our community. Each team must have a designated coach to handle registration and communications with league officials. Coaches do not need to submit scores. Your game umpire or referee will submit the final score of each game. Scores and standings are updated on the website within 24 hours.</p>
        <h2>Upcoming seasons</h2>
        <p>Registration is open for the following seasons:</p>
        <ul>
            <li>Spring basketball 2019</li>
            <li>Summer softball 2019</li>
            <li>Fall flag football 2019</li>
            <li>Winter ice hockey 2019</li>
        </ul>
        <p>Call the league office at 303-555-1212 for more information or to sign up your team.</p>
    </section>
ABC;

echo $homeText;

//display footer
echo displayPageFooter();
?>

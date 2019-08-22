<?php

/* 
 * File name: siteCommon.php
 * Purpose: common header and footer display functions
 * Authors: Team 112 - Luke Buthman, Paul Huff, Teri Kieffer
 * Date: Spring Semester 2019
 */

//display hero image and navigation
function displayPageHeader($pageTitle)
    {
    
    session_start();

    //initiate login button values
    $buttonName = "Officials Login";
    $buttonUrl = "login.php";

    //change login button values to logout if user is already logged in
    if (isset($_SESSION['userInfo']))
    {
        $buttonName = "Logout";
        $buttonUrl = "logout.php";
    }

    //header HTML
    $output = <<<ABC
    <!DOCTYPE html>
    <html>
       <head>
            <meta charset="UTF-8">
            <title>$pageTitle</title>
            <link rel="stylesheet" href="./recLeague_styles.css" />
            <link rel="stylesheet" href="./recLeague_layout.css" />
       </head>
       <body>
            <header>
                <img alt="View of the Rocky Mountains" title="Sports league" src="./EstesPark.jpg" />
                <nav>
                    <ul>
                        <li><a href="./home.php">Home</a></li>
                        <li><a href="./scores.php">Team Scores</a></li>
                        <li><a href="./standings.php">League Standings</a></li>
                        <li><a href="
ABC;
       $output .= $buttonUrl;
       
       $output .= <<<ABC
               "><button class="logButton">
ABC;
       $output .= $buttonName;
       
       $output .= <<<ABC
            </button></a></li>
                    </ul>
                </nav>
            </header>
ABC;
       return $output;
    }
   
//display footer
function displayPageFooter()
    {
       $output = <<<ABC
        <footer>
            <p><em>Site developed by Team 112: Luke Buthman, Paul Huff, Teri Kieffer </em>&nbsp;&nbsp;&nbsp;&#9977;&nbsp;&nbsp;&nbsp;<em>Photo credit: Confluence Fort Collins</em></p>
        </footer>
     </body>
    </html>
ABC;
       return $output;
    }

?>

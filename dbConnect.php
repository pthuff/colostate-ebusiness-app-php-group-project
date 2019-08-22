<?php

/* 
 * File name: dbConnect.php
 * Purpose: connects to the database and returns the queries from recLeagueSql.php
 * Authors: Team 112 - Luke Buthman, Paul Huff, Teri Kieffer
 * Date: Spring Semester 2019
 */

//connect to the database with our team credentials
function dbConnect()
{
    $serverName = 'buscissql1601\cisweb';
    $uName = 'graphics';
    $pWord = 'stands';
    $db = 'Team112DB';
    
    try 
    {
        //Instantiate a PDO object and set connection properties
        $conn = new PDO("sqlsrv:Server=$serverName; Database=$db", $uName, $pWord, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));       
    }
    
    //Connection fails
    catch (Exception $ex) 
    {     
        die('Connection failed: ' . $e->getMessage());
    }
    
    //Return connection object
    return $conn;   
}

//execute sql query
function executeQuery($query)
{
    $conn = dbConnect();
    
    try
    {
        //Execute query and assign results to a PDOStatement object
        $stmt = $conn->query($query);

        if ($stmt->columnCount() > 0)  // if rows with columns are returned
            {
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);  //retreive the rows as an associative array
            }
            
        //Disconnect from DB and return the query    
        dbDisconnect($conn);
        return $results;       
    } 
    catch (Exception $ex) 
    {
        //Query fails
        dbDisconnect($conn);
        die ('Query failed: ' . $ex->getMessage());
    }
    
    return $results;
}

//disconnect from database
function dbDisconnect($conn)
{
    //Closes the specfied connection and releases associated resources
    $conn = null;
}
?>

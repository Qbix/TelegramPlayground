<?php
require_once 'HighScore.php';

if (isset($_GET['distance']) &&  isset($_GET['id'])) {

//    session_start();

//    if(!in_array('qbix_'.$_GET['id'], $_SESSION)){
//        return ;
//    }

    $highScoreObj = new HighScore();
    $highScoreObj->processHighScore($_GET['distance'], $_GET['id']);

}

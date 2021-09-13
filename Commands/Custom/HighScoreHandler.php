<?php

if (isset($_GET['distance']) &&  isset($_GET['id'])) {
    echo json_encode(array("result" => 'server result', "success" => true, "user_message" => "hello"));
    exit();
}


<?php

// Load all configuration options
/** @var array $config */
$config = require __DIR__ . '/../../config.php';

if (
    isset($_GET['distance']) &&
    isset($_GET['id']) &&
    isset($_GET['user_id']) &&
    isset($_GET['chat_id']) &&
    isset($_GET['message_id'])
) {
    $highScore = (int)$_GET['distance'];
    $user_id = (int)$_GET['user_id'];
    $chat_id = (int)$_GET['chat_id'];
    $message_id = (int)$_GET['message_id'];

    $url = 'https://api.telegram.org/bot' . $config['api_key'] . '/setGameScore?user_id=' . $user_id . '&score=' . $highScore . '&chat_id=' . $chat_id . '&message_id=' . $message_id . '&force=true';

    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, $url);
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    $buffer = curl_exec($curl_handle);
    curl_close($curl_handle);

    echo ($buffer) ? $buffer : "";
    return;
}


<?php

use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class HighScore
{
    public function processHighScore($score, $user_id)
    {
        session_start();
        $query = $_SESSION['qbix_' . $user_id];
        if (isset($query['message'])) {

            $chat_id = $query['message']['chat']['id'];
            $message_id = $query['message']['message_id'];

            $result = Request::setGameScore([
                'user_id' => $query['from']['id'],
                'score' => (int)$score,
                'chat_id' => (int)$chat_id,
                'message_id' => (int)$message_id

            ]);

        } else {

            $inline_message_id = $query['inline_message_id'];
            $result = Request::setGameScore([
                'user_id' => $query['from']['id'],
                'score' => (int)$score,
                'inline_message_id' => (int)$inline_message_id
            ]);
        }

        if ($result->isOk()) {
            echo json_encode($result);
            exit();
        } else {
            throw new TelegramException($result->getDescription());
        }
    }
}

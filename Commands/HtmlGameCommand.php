<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class HtmlGameCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'htmlgame';                      // Your command's name

    /**
     * @var string
     */
    protected $description = 'A command for html game'; // Your command description

    /**
     * @var string
     */
    protected $usage = '/htmlgame';                    // Usage of your command

    /**
     * @var string
     */
    protected $version = '1.0.0';                  // Version of your command


    /**
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();            // Get Message object
        $chat_id = $message->getChat()->getId();   // Get the current Chat ID
//        $data = [                                  // Set up the new message data
//            'chat_id' => $chat_id,                 // Set Chat ID to send the message to
//            'text' => 'This is just a html game...', // Set message to send
//        ];
//        return Request::sendMessage($data);        // Send message!

        $data = [
            'chat_id' => $chat_id,
            'game_short_name' => 'qbix_bot_game2' // change this game short name to as per your game short name
        ];

        return Request::sendGame($data);

    }


}
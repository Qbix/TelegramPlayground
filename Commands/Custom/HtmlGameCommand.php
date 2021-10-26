<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
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
        $config = require __DIR__ . '/../../config.php';
        $htmlgame_short_name = $config['game_short_name'];


        $message = $this->getMessage();            // Get Message object
        $chat_id = $message->getChat()->getId();   // Get the current Chat ID

        $result = Request::sendGame([
            'chat_id' => $chat_id,
            'game_short_name' => $htmlgame_short_name, // change this game short name to as per your game short name
            'reply_markup' => new InlineKeyboard([
                new InlineKeyboardButton([
                    'text'=>"Play",
                    'callback_game'=> 'Play qbix bot game'
                ])
            ]),
        ]);

        if ($result->isOk()) {
            return $result;
        }

        throw new TelegramException($result->getDescription());

    }


}
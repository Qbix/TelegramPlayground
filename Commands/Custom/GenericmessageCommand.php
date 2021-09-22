<?php

/**
 * This file is part of the PHP Telegram Bot example-bot package.
 * https://github.com/php-telegram-bot/example-bot/
 *
 * (c) PHP Telegram Bot Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Generic message command
 *
 * Gets executed when any type of message is sent.
 *
 * In this conversation-related context, we must ensure that active conversations get executed correctly.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class GenericmessageCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'genericmessage';

    /**
     * @var string
     */
    protected $description = 'Handle generic message';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * @var bool
     */
    protected $need_mysql = true;

    /**
     * Command execute method if MySQL is required but not available
     *
     * @return ServerResponse
     */
    public function executeNoDb(): ServerResponse
    {
        // Do nothing
        return Request::emptyResponse();
    }

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {

        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $text = $message->getText();

        $result = Request::emptyResponse();

        if($text === 'game'){
            $result = Request::sendGame([
                'chat_id' => $chat_id,
                'game_short_name' => 'qbix_bot_game2', // change this game short name to as per your game short name
                'reply_markup' => new InlineKeyboard([
                    new InlineKeyboardButton([
                        'text'=>"Play",
                        'callback_game'=> 'Play qbix bot game'
                    ])
                ]),
            ]);
        }

        if ($result->isOk()) {
            return $result;
        }

        throw new TelegramException($result->getDescription());

    }
}

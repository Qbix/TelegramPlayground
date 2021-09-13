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
 * Callback query command
 *
 * This command handles all callback queries sent via inline keyboard buttons.
 *
 * @see InlinekeyboardCommand.php
 */

namespace Longman\TelegramBot\Commands\SystemCommands;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;

class CallbackqueryCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'callbackquery';

    /**
     * @var string
     */
    protected $description = 'Handle the callback query';

    /**
     * @var string
     */
    protected $version = '1.2.0';

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws \Exception
     */
    public function execute(): ServerResponse
    {
        session_start();

        // Callback query data can be fetched and handled accordingly.
        $callback_query = $this->getCallbackQuery();
        $callback_data = $callback_query->getData();

        $queryId = $callback_query->getId();
        $_SESSION['qbix_'.$queryId] = $callback_query;

        $gameUrl = 'https://' . $_SERVER['SERVER_NAME'] . '/public/index.php?id='.$queryId;

        return $callback_query->answer([
            'text' => $callback_data,
            'show_alert' => (bool)random_int(0, 1), // Randomly show (or not) as an alert.
            'cache_time' => 5,
            'url' => $gameUrl
        ]);
    }
}

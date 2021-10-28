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
        // Callback query data can be fetched and handled accordingly.
        $callback_query = $this->getCallbackQuery();
        $callback_data = $callback_query->getData();
        $queryId = $callback_query->getId();

        $user_id = $callback_query->getFrom()->getId();

        $chat_id = null;
        $message_id = null;

        // https://github.com/php-telegram-bot/core/issues/1064#issuecomment-606851122 (How I can get chat_id from callback_query when callback_query is sent from InlineQueryResultArticle reply_markup? #1064)
        if ($callback_query->getMessage()) {
            $chat_id = $callback_query->getMessage()->getChat()->getId();
            $message_id = $callback_query->getMessage()->getMessageId();
        }

        $gameUrl = 'https://' . $_SERVER['SERVER_NAME'] . '/public/index.php?id='.$queryId.'&user_id='.$user_id.'&chat_id='.$chat_id.'&message_id='.$message_id;

        return $callback_query->answer([
            'text' => $callback_data,
            'show_alert' => (bool)random_int(0, 1), // Randomly show (or not) as an alert.
            'cache_time' => 5,
            'url' => $gameUrl
        ]);
    }
}

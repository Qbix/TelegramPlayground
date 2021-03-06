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

use App;
use Longman\TelegramBot\DB;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\ChatPermissions;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

require_once 'I18n.php';

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
        $i18n = new App\I18n();
        $i18n->handleMultiLanguage();

        $config = require __DIR__ . '/../../config.php';
        $bot_username = $config['bot_username'];
        $deep_link_code = $config['deep_link_code'];

        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();   // Get the current Chat ID
        $user_id = $message->getFrom()->getId();

        /*
        $newChatMemeber = $message->getNewChatMembers();

        if ($newChatMemeber) {
            Request::restrictChatMember(
                array(
                    'chat_id' => $chat_id,
                    'user_id' => $user_id,
                    'permissions' => json_encode(
                        new ChatPermissions(
                            array('can_send_messages' => false)
                        )
                    )
                )
            );

            $pdoObj = DB::getPdo();

            $sqlQuery = "SELECT chat_id, message_id FROM welcome_group_chat";
            $pdoSqlObj = $pdoObj->prepare($sqlQuery);
            $pdoSqlObj->execute();
            $resultWelcomeMessage = $pdoSqlObj->fetchAll();

            foreach ($resultWelcomeMessage as $value) {
                Request::deleteMessage(['chat_id' => $value['chat_id'], 'message_id' => $value['message_id']]);
            }

            $sqlQuery = "DELETE FROM welcome_group_chat";
            $pdoSqlObj = $pdoObj->prepare($sqlQuery);
            $pdoSqlObj->execute();

            $result = Request::sendMessage([
                'chat_id' => $chat_id,
                'text' => 'Please click below button to open the chat', // change this game short name to as per your game short name
                'reply_markup' => new InlineKeyboard([
                    new InlineKeyboardButton([
                        'text' => "open chat",
                        'url' => 'https://t.me/' . $bot_username . '?start=' . $deep_link_code
                    ])
                ]),
            ]);

            $message_id = $result->getResult()->getMessageId();
            $chat_id = $result->getResult()->getChat()->getId();

            $sqlQuery = "INSERT INTO welcome_group_chat (chat_id, message_id) VALUES (?,?)";
            $pdoSqlObj = $pdoObj->prepare($sqlQuery);
            $pdoSqlObj->execute([$chat_id, $message_id]);

            if ($result->isOk()) {
                return $result;
            }
        } else { */
            // If a conversation is busy, execute the conversation command after handling the message.
            $conversation = new Conversation($user_id, $chat_id);

            // Fetch conversation command if it exists and execute it.
            if ($conversation->exists() && $command = $conversation->getCommand()) {
                return $this->telegram->executeCommand($command);
            }
        /*} */

        return Request::emptyResponse();

    }
}

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
 * User "/survey" command
 *
 * Example of the Conversation functionality in form of a simple survey.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\ChatPermissions;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\KeyboardButton;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class SurveyCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'survey';

    /**
     * @var string
     */
    protected $description = 'Survey for bot users';

    /**
     * @var string
     */
    protected $usage = '/survey';

    /**
     * @var string
     */
    protected $version = '0.4.0';

    /**
     * @var bool
     */
    protected $need_mysql = true;

    /**
     * @var bool
     */
    protected $private_only = true;

    /**
     * Conversation Object
     *
     * @var Conversation
     */
    protected $conversation;

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {

        $config = require __DIR__ . '/../../config.php';
        $group_link = $config['group_link'];
        $group_chat_id = $config['group_chat_id'];

        $message = $this->getMessage();

        $chat = $message->getChat();
        $user = $message->getFrom();
        $text = trim($message->getText(true));
        $chat_id = $chat->getId();
        $user_id = $user->getId();

        // Preparing response
        $data = [
            'chat_id' => $chat_id,
            // Remove any keyboard by default
            'reply_markup' => Keyboard::remove(['selective' => true]),
        ];

        if ($chat->isGroupChat() || $chat->isSuperGroup()) {
            // Force reply is applied by default so it can work with privacy on
            $data['reply_markup'] = Keyboard::forceReply(['selective' => true]);
        }

        // Conversation start
        $this->conversation = new Conversation($user_id, $chat_id, $this->getName());

        // Load any existing notes from this conversation
        $notes = &$this->conversation->notes;
        !is_array($notes) && $notes = [];

        // Load the current state of the conversation
        $state = $notes['state'] ?? 0;

        $result = Request::emptyResponse();

        // State machine
        // Every time a step is achieved the state is updated
        switch ($state) {
            case 0:
                if ($message->getLocation() === null) {
                    $notes['state'] = 0;
                    $this->conversation->update();

                    $data['reply_markup'] = (new Keyboard(
                        (new KeyboardButton('Share Location'))->setRequestLocation(true)
                    ))
                        ->setOneTimeKeyboard(true)
                        ->setResizeKeyboard(true)
                        ->setSelective(true);

                    $data['text'] = 'Share your location:';

                    $result = Request::sendMessage($data);
                    break;
                }


                $notes['longitude'] = $message->getLocation()->getLongitude();
                $notes['latitude'] = $message->getLocation()->getLatitude();

            // No break!
            case 1:

                if ($message->getContact() === null) {
                    $notes['state'] = 1;
                    $this->conversation->update();

                    $data['reply_markup'] = (new Keyboard(
                        (new KeyboardButton('Share Contact'))->setRequestContact(true)
                    ))
                        ->setOneTimeKeyboard(true)
                        ->setResizeKeyboard(true)
                        ->setSelective(true);

                    $data['text'] = 'Share your contact information:';

                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['phone_number'] = $message->getContact()->getPhoneNumber();

            // No break!
            case 3:
                $notes['state'] = 3;
                $this->conversation->update();
                $this->telegram->executeCommand("userinfo");

            // No break!
            case 2:
                $this->conversation->update();
                $out_text = '/Survey result:' . PHP_EOL;
                $longitude = $notes['longitude'];
                unset($notes['state']);
                foreach ($notes as $k => $v) {
                    $out_text .= PHP_EOL . ucfirst($k) . ': ' . $v;
                }

                $data['text'] = $out_text;

                $this->conversation->stop();

                $result = Request::emptyResponse();

                if (!is_null($longitude)) {

                    Request::restrictChatMember(
                        array(
                            'chat_id' => $group_chat_id,
                            'user_id' => $user_id,
                            'permissions' => json_encode(
                                new ChatPermissions(
                                    array('can_send_messages' => true)
                                )
                            )
                        )
                    );

                    $result = Request::sendMessage([
                        'chat_id' => $chat_id,
                        'text' => 'Thanks for providing the info. You can participate in the group chat. Please click below button', // change this game short name to as per your game short name
                        'reply_markup' => new InlineKeyboard([
                            new InlineKeyboardButton([
                                'text' => "open group",
                                'url' => $group_link
                            ])
                        ]),
                    ]);
                }

                break;
        }

        return $result;
    }
}

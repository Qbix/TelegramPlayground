<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;


use Longman\TelegramBot\Conversation;


class VideoCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'video';                      // Your command's name

    /**
     * @var string
     */
    protected $description = 'A command for sending the video'; // Your command description

    /**
     * @var string
     */
    protected $usage = '/video';                    // Usage of your command

    /**
     * @var string
     */
    protected $version = '1.0.0';                  // Version of your command


    /**
     * Conversation Object
     *
     * @var Conversation
     */
    protected $conversation;

    /**
     * @var bool
     */
    protected $need_mysql = true;

    /**
     * @var bool
     */
    protected $private_only = false;

    /**
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        $config = require __DIR__ . '/../../config.php';
        $video_one_file_id = $config['video_one_file_id'];

        $message = $this->getMessage();
        $chat = $message->getChat();
        $chat_id = $chat->getId();

        $data = [
            'chat_id' => $chat_id,
            'video' => 	$video_one_file_id
        ];

        $result = Request::sendVideo($data);

        return $result;
    }
}
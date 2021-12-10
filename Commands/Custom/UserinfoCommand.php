<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use App;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;


use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;

require_once 'I18n.php';

class UserinfoCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'userinfo';                      // Your command's name

    /**
     * @var string
     */
    protected $description = 'get the user info (profile-photo, username, user-id, first-name, last-name) '; // Your command description

    /**
     * @var string
     */
    protected $usage = '/userinfo';                    // Usage of your command

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
    protected $private_only = true;

    /**
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        $i18n = new App\I18n();

        $i18n->handleMultiLanguage();

        $message = $this->getMessage();
        $from       = $message->getFrom();
        $chat = $message->getChat();
        $user = $message->getFrom();
        $chat_id = $chat->getId();
        $user_id = $user->getId();

        // Preparing response
        $data = [
            'chat_id' => $chat_id
        ];

        // Make sure the Download path has been defined and exists
        $download_path = $this->telegram->getDownloadPath();
        if (!is_dir($download_path)) {
            return $this->replyToChat(__('Download path has not been defined or does not exist.'));
        }
        $message_type = $message->getType();

        $caption = sprintf(
            __('Your Id:')." %d" . PHP_EOL .
            __('Name:')." %s %s" . PHP_EOL .
            __('Username:')." %s",
            $user_id,
            $from->getFirstName(),
            $from->getLastName(),
            $from->getUsername()
        );


        // Fetch the most recent user profile photo
        $limit  = 1;
        $offset = null;

        $user_profile_photos_response = Request::getUserProfilePhotos([
            'user_id' => $user_id,
            'limit'   => $limit,
            'offset'  => $offset,
        ]);

        if ($user_profile_photos_response->isOk()) {
            /** @var UserProfilePhotos $user_profile_photos */
            $user_profile_photos = $user_profile_photos_response->getResult();

            if ($user_profile_photos->getTotalCount() > 0) {
                $photos = $user_profile_photos->getPhotos();

                // Get the best quality of the profile photo
                $photo   = end($photos[0]);
                $file_id = $photo->getFileId();

                $data['photo']   = $file_id;
                $data['caption'] = $caption;

                $file    = Request::getFile(['file_id' => $file_id]);
                if ($file->isOk() && Request::downloadFile($file->getResult())) {
                    $data['text'] = $message_type . __(' file is located at: ') . $download_path . '/' . $file->getResult()->getFilePath();
                } else {
                    $data['text'] = __('Failed to download.');
                }

                return Request::sendPhoto($data);
            }
        }

        // No Photo just send text
        $data['text'] = $caption;
        $result = Request::sendMessage($data);

        return $result;
    }
}
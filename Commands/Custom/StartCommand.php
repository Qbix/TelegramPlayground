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
 * Start command
 *
 * Gets executed when a user first starts using the bot.
 *
 * When using deep-linking, the parameter can be accessed by getting the command text.
 *
 * @see https://core.telegram.org/bots#deep-linking
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class StartCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'start';

    /**
     * @var string
     */
    protected $description = 'Start command';

    /**
     * @var string
     */
    protected $usage = '/start';

    /**
     * @var string
     */
    protected $version = '1.2.0';

    /**
     * @var bool
     */
    protected $private_only = true;

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {

        $locale = "es_ES";

        putenv("LANG=" . $locale);
        setlocale(LC_ALL, $locale);
        $domain = "messages";
        bindtextdomain($domain, __DIR__."/locales");  // Also works like this
        bind_textdomain_codeset($domain, 'UTF-8');
        textdomain($domain);

        $config = require __DIR__ . '/../../config.php';
        $deep_link_code = $config['deep_link_code'];

        // If you use deep-linking, get the parameter like this:
        $deep_linking_parameter = $this->getMessage()->getText(true);

        $message = $this->getMessage();
        $group_chat_id = $config['group_chat_id'];
        $user_id = $message->getFrom()->getId();

        // check if user is restricted in the group
        $member = Request::getChatMember([
            'user_id' => $user_id,
            'chat_id' => $group_chat_id
        ]);

        if ($member->isOk()) {
            $member_data = $member->getResult();
            if ($member_data->getStatus()) {
                $status = $member_data->getStatus();
            } else {
                $status = '';
            }
        } else {
            $status = '';
        }

        $isRestricted = false;

        if ($status === 'restricted') {
            $isRestricted = true;
        }

        if ($deep_linking_parameter === $deep_link_code && $isRestricted) {
            return $this->telegram->executeCommand("survey");
        } else {
            return $this->replyToChat(
//                'Hi there!' . PHP_EOL .
                _('Hello and welcome!') . PHP_EOL .
                'Type /help to see all commands!'
            );
        }

    }
}

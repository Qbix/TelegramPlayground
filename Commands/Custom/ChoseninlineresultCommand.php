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
 * Chosen inline result command
 *
 * Gets executed when an item from an inline query is selected.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use App;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;

require_once 'I18n.php';

class ChoseninlineresultCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'choseninlineresult';

    /**
     * @var string
     */
    protected $description = 'Handle the chosen inline result';

    /**
     * @var string
     */
    protected $version = '1.2.0';

    /**
     * Main command execution
     *
     * @return ServerResponse
     */
    public function execute(): ServerResponse
    {
        $i18n = new App\I18n();
        $i18n->handleMultiLanguage();

        // Information about the chosen result is returned.
        $inline_query = $this->getChosenInlineResult();
        $query        = $inline_query->getQuery();

        return parent::execute();
    }
}

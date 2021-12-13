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
 * Inline query command
 *
 * Command that handles inline queries and returns a list of results.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use App;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\InlineQuery\InlineQueryResultArticle;
use Longman\TelegramBot\Entities\InlineQuery\InlineQueryResultContact;
use Longman\TelegramBot\Entities\InlineQuery\InlineQueryResultGame;
use Longman\TelegramBot\Entities\InlineQuery\InlineQueryResultGif;
use Longman\TelegramBot\Entities\InlineQuery\InlineQueryResultLocation;
use Longman\TelegramBot\Entities\InlineQuery\InlineQueryResultMpeg4Gif;
use Longman\TelegramBot\Entities\InlineQuery\InlineQueryResultPhoto;
use Longman\TelegramBot\Entities\InlineQuery\InlineQueryResultVenue;
use Longman\TelegramBot\Entities\InlineQuery\InlineQueryResultVideo;
use Longman\TelegramBot\Entities\InputMessageContent\InputTextMessageContent;
use Longman\TelegramBot\Entities\ServerResponse;

require_once 'I18n.php';

class InlinequeryCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'inlinequery';

    /**
     * @var string
     */
    protected $description = 'Handle inline query';

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

        $config = require __DIR__ . '/../../config.php';
        $htmlgame_short_name = $config['game_short_name'];

        $inline_query = $this->getInlineQuery();
        $query        = $inline_query->getQuery();

        $results = [];

        if ($query !== '') {
            // https://core.telegram.org/bots/api#inlinequeryresultarticle
            $results[] = new InlineQueryResultArticle([
                'id'                    => '001',
                'title'                 => __('Simple text using InputTextMessageContent'),
                'description'           => __('this will return Text'),

                // Here you can put any other Input...MessageContent you like.
                // It will keep the style of an article, but post the specific message type back to the user.
                'input_message_content' => new InputTextMessageContent([
                    'message_text' => __('The query that got you here:') . ' ' . $query,
                ]),
            ]);

            // https://core.telegram.org/bots/api#inlinequeryresultcontact
            $results[] = new InlineQueryResultContact([
                'id'           => '002',
                'phone_number' => '12345678',
                'first_name'   => __('Best'),
                'last_name'    => __('Friend'),
            ]);

            // https://core.telegram.org/bots/api#inlinequeryresultlocation
            $results[] = new InlineQueryResultLocation([
                'id'        => '003',
                'title'     => __('The center of the world!'),
                'latitude'  => 40.866667,
                'longitude' => 34.566667,
            ]);

            // https://core.telegram.org/bots/api#inlinequeryresultvenue
            $results[] = new InlineQueryResultVenue([
                'id'        => '004',
                'title'     => __('No-Mans-Land'),
                'address'   => __('In the middle of Nowhere'),
                'latitude'  => 33,
                'longitude' => -33,
            ]);

            // https://core.telegram.org/bots/api#inlinequeryresultgame
            $results[] = new InlineQueryResultGame([
                'id'                => '005',
                'game_short_name'   => $htmlgame_short_name
            ]);

            // https://core.telegram.org/bots/api#inlinequeryresultgif
            $results[] = new InlineQueryResultGif([
                'id'     => '006',
                'type'   => 'gif',
                'gif_url' => 'https://sample-videos.com/gif/3.gif',
                'thumb_url' => 'https://sample-videos.com/gif/3.gif',
                'title' => __('This is a gif'),
                'caption' => __('This is a gif caption'),
                'gif_height' => 10
            ]);

            // https://core.telegram.org/bots/api#inlinequeryresultmpeg4gif
            $results[] = new InlineQueryResultMpeg4Gif([
                'id'     => '007',
                'type'   => 'mpeg4_gif',
                'mpeg4_url' => 'https://www.easygifanimator.net/images/samples/video-to-gif-sample.gif',
                'thumb_url' => 'https://www.easygifanimator.net/images/samples/video-to-gif-sample.gif',
                'title' => __('This is a animated gif'),
                'caption' => __('This is a animated gif caption')
            ]);

            // https://core.telegram.org/bots/api#inlinequeryresultphoto
            $results[] = new InlineQueryResultPhoto([
                'id'     => '008',
                'type'   => 'photo',
                'photo_url' => 'https://file-examples-com.github.io/uploads/2017/10/file_example_JPG_100kB.jpg',
                'thumb_url' => 'https://file-examples-com.github.io/uploads/2017/10/file_example_JPG_100kB.jpg',
                'title' => __('This is a photo'),
                'caption' => __('This is a photo caption')
            ]);

            // https://core.telegram.org/bots/api#inlinequeryresultvideo
            $results[] = new InlineQueryResultVideo([
                'id'     => '009',
                'type'   => 'video',
                'video_url' => 'https://filesamples.com/samples/video/mp4/sample_640x360.mp4',
                'mime_type' => 'video/mp4',
                'thumb_url' => 'https://www.learningcontainer.com/wp-content/uploads/2020/07/Sample-JPEG-Image-File-Download-scaled.jpg',
                'title' => __('This is a video'),
                'caption' => __('This is a video caption')
            ]);


        }

        return $inline_query->answer($results);
    }
}

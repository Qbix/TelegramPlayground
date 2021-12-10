<?php

namespace App;

use PhpMyAdmin\MoTranslator\Loader;

class I18n
{

    public function handleMultiLanguage()
    {
        Loader::loadFunctions();

        $locale = "es_ES";

        _setlocale(LC_ALL, $locale);
        $domain = "messages";
        _bindtextdomain($domain, __DIR__ . "/locales");  // Also works like this
        _bind_textdomain_codeset($domain, 'UTF-8');
        _textdomain($domain);
    }
}


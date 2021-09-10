# PHP Telegram Bot Example

* requirements: php version 7.3, mysql version 5.7 [Probably it will work on other php7.\*/mysql5.\* versions also] 
* clone the repository
* copy config.example.php to config.php and provide necessary values
* create the database, provide that db name into config.php and import the sql schema structure.sql
* composer install 
* run the php server
* php -S localhost:8082
* expose localhost url via ngrok
* ngrok http 8082
* copy ngrok https url and fill in the config.php eg 
* https://304a-2405-205-1108-5f0a-c970-bf47-5fa0-5f2c.ngrok.io/manager.php
* set the webhook
* https://304a-2405-205-1108-5f0a-c970-bf47-5fa0-5f2c.ngrok.io/manager.php?s=super_secret&a=set
* create newbot and newgame as described in the article https://www.freecodecamp.org/news/how-to-code-chromes-t-rex-as-a-telegram-game-using-node-js-cbcf42f76f4b/ (step1 and step2)
* updae gamename in Commands/HtmlGameCommand.php file 
* open the chat with bot in telegram app
* Commands are 
* /help, /htmlgame, ...etc.




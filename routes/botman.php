<?php

use App\Conversations\QuizConversation;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Telegram\TelegramDriver;
use App\Http\Controllers\BotManController;




$driver = DriverManager::loadDriver(\BotMan\Drivers\Telegram\TelegramDriver::class);
$config = [
  'telegram' => [
    'token' => '6076846904:AAHtzW0YDIPGNLkhn8-vFQNwuL9PfSATFR0'
  ]
];



// Create an instance
$botman = BotManFactory::create($config);

// // Give the bot something to listen for.
// $botman->hears('hello', function ($bot) {
//     $bot->reply('Hello yourself.');
// });

// $botman->hears('hello', function (BotMan $bot) {
//     $bot->reply('whats your name ?');
// });

// $botman->hears('my name is {name}', function (BotMan $bot,$name) {
//     $bot->reply("hello $name");



// });

$botman->hears('Hi', function (BotMan $bot) {
    $bot->reply('Hello!');
});

$botman->hears('start', function (BotMan $bot) {
    $bot->startConversation(new QuizConversation());
});


// $botman->hears('Start conversation', BotManController::class.'@startConversation');



// $botman->fallback( function (BotMan $bot,$name) {
//     $message=$bot->getMessage()->getText();

//     $bot->reply("iam sorry '{$message}', ");
// });


// // Start listening
$botman->listen();

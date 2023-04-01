<?php

use App\Conversations\OnboardingConversation;
use App\Conversations\QuizConversation;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Telegram\TelegramDriver;
use App\Http\Controllers\BotManController;
use App\Models\UserScore;

use BotMan\BotMan\Cache\LaravelCache;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question as BotManQuestion;


$driver = DriverManager::loadDriver(\BotMan\Drivers\Telegram\TelegramDriver::class);
$config = [

    // "botman" => [
    //     'conversation_cache_time' => 720 ,
    //     'user_cache_time' => 720,
    // ],

    'telegram' => [
        'token' => '6076846904:AAHtzW0YDIPGNLkhn8-vFQNwuL9PfSATFR0'
    ]
];



// Create an instance
$botman = BotManFactory::create($config, new LaravelCache());






$botman->hears('Hello', function ($bot) {

    $user = $bot->getUser();
    $user_chat = UserScore::whereChatId($user->getId())->first();

    if ($user_chat) {
        $bot->reply("welcome back : {$user_chat->name} and write start for begin exam");
    } else {
        $bot->startConversation(new OnboardingConversation);
    }
})->stopsConversation();

$botman->hears('start', function (BotMan $bot) {

    $user = $bot->getUser();
    $user_chat = UserScore::whereChatId($user->getId())->first();

    if (!$user_chat) {
        $bot->reply("Write hello to new registration ");
    } else {
        $bot->startConversation(new QuizConversation());
    }
})->stopsConversation();

$botman->listen();

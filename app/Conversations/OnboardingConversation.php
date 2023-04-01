<?php

namespace App\Conversations;

use App\Models\Question;
use App\Models\UserScore;
use BotMan\BotMan\Interfaces\UserInterface;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question as BotManQuestion;
use BotMan\Drivers\Telegram\TelegramDriver;
use BotMan\BotMan\BotMan;


class OnboardingConversation extends Conversation
{
    protected $name;

    protected $phone;


    public function run()
    {

        $this->askFirstname();
    }


    public function askFirstname()
    {


        $this->ask('Hello! new registeration what is your name ?', function (Answer $answer) {
            // Save result
            $this->name = $answer->getText();

            $this->say('welcome ' . $answer->getText());
            $this->askPhone();
        });
    }

    public function askPhone()
    {
        $this->ask(' what is your phone?', function (Answer $answer) {
            // Save result

            $this->say('your phone, ' . $answer->getText());

            $user = $this->bot->getUser();

            $this->say('welcome ' . $this->name);

            UserScore::create([
                'chat_id' => $user->getId(),
                'name' => $this->name
            ]);

            $this->say('write start to begin exam');
        });
    }
}

<?php

namespace App\Conversations;

use App\Models\Question;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question as BotManQuestion;
use BotMan\Drivers\Telegram\TelegramDriver;

class QuizConversation extends Conversation
{
    /** @var Question */
    protected $quizQuestions;

/** @var integer */
    protected $userPoints = 0;

/** @var integer */
    protected $userCorrectAnswers = 0;

/** @var integer */

/** @var integer */
    protected $currentQuestion = 1;

    protected $questionCount = 0;



    public function run()
    {

        $this->quizQuestions = Question::all()->shuffle();
        $this->questionCount = $this->quizQuestions->count();
        $this->quizQuestions = $this->quizQuestions->keyBy('id');

        $this->showInfo();

    }
    private function showInfo()
    {
        $this->say('You will be shown ' . $this->questionCount . ' questions about Laravel. Every correct answer will reward you with a certain amount of points. Please keep it fair and don\'t use any help. All the best! 🍀');
        $this->checkForNextQuestion();
    }

    private function checkForNextQuestion()
    {

        if ($this->quizQuestions->count()) {
            return $this->askQuestion($this->quizQuestions->first());
        }

        $this->showResult();
    }
    private function askQuestion(Question $question)
    {
        $questionTemplate = BotManQuestion::create($question->question);

        $questionText = '➡️ Question: ' . $this->currentQuestion . ' / ' . $this->questionCount . ' : ' . $question->question;
        $questionTemplate = BotManQuestion::create($questionText);

        $this->ask($questionTemplate, function (Answer $answer) use ($question) {

            // $this->bot->driver(TelegramDriver::DRIVER_NAME)->endConversation();


            $quizAnswer = Question::where('answer', $answer->getText())->first();

            if (!$quizAnswer) {
                $answerResult = '❌';
            } else {
                $this->userPoints += 1;
                $answerResult = '✅';
            }
            $this->quizQuestions->forget($question->id);


            $this->currentQuestion++;

            $this->say("Your answer:{$this->currentQuestion} {$answer->getText()} {$answerResult}");

            return $this->checkForNextQuestion();

        });
    }

    private function showResult()
    {

        if ($this->userPoints >= ceil($this->questionCount /2) ) {
            $this->say('your win 🏁');
            
        } else {
            $this->say('your fail');

        }

        $this->bot->driver(TelegramDriver::DRIVER_NAME)->endConversation();

        // $this->stopsConversation('your win');



    }

}

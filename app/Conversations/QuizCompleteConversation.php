<?php

namespace App\Conversations;

use App\Models\Question;
use App\Models\UserScore;
use BotMan\BotMan\Interfaces\UserInterface;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question as BotManQuestion;
use BotMan\Drivers\Telegram\TelegramDriver;

class QuizCompleteConversation extends Conversation
{
    public $user_chat;
    public function __construct($user_chat)
    {

        $this->user_chat = $user_chat;
    }
    /** @var Question */
    protected $quizQuestions;
    protected $number_question = 0;
    protected $user;


    /** @var integer */
    protected $userPoints = 0;

    /** @var integer */
    protected $userCorrectAnswers = 0;

    /** @var integer */

    /** @var integer */
    protected $currentQuestion = 0;

    protected $questionCount = 0;



    public function run()
    {

        $this->quizQuestions = Question::all()->shuffle();
        $this->questionCount = $this->quizQuestions->count();
        $this->quizQuestions = $this->quizQuestions->keyBy('id');
        $this->currentQuestion= $this->user_chat->number_question;


        $this->showInfo();
    }
    private function showInfo()
    {
        if ($this->user_chat->status != 'not-completed') {

            $this->say('You will be show some questions. Every correct answer will reward you with a certain amount of points. Please keep it fair and don\'t use any help. All the best! 🍀');
        }
        $this->checkForNextQuestion();
    }

    private function checkForNextQuestion()
    {


        $user = $this->bot->getUser();

        $user_chat = UserScore::whereChatId($user->getId())->first();
        // $this->say($user_chat);


        if ((int)$this->user_chat->number_question == $this->questionCount || $user_chat->number_question ==9 ) {
            $this->showResult();
        }
        else{

            return $this->askQuestion($this->quizQuestions->first());
        }
    }
    private function askQuestion(Question $question)
    {
        $questionTemplate = BotManQuestion::create($question->question);

        $questionText = '➡️ Question: ' .  $this->currentQuestion . ' / ' . $this->questionCount . ' : ' . $question->question;
        $questionTemplate = BotManQuestion::create($questionText);

        $this->ask($questionTemplate, function (Answer $answer) use ($question) {



            $quizAnswer = Question::where('answer', $answer->getText())->first();

            if (!$quizAnswer) {
                $answerResult = '❌';
            } else {
                $this->userPoints += 1;
                $this->userCorrectAnswers++;

                $answerResult = '✅';
            }
            // $this->number_question += 1;

            $this->quizQuestions->forget($question->id);


            $this->currentQuestion++;

            $this->user = $this->saveUser($this->bot->getUser(), $this->userPoints, $this->userCorrectAnswers, $this->number_question);

            $user_name = UserScore::whereChatId($this->bot->getUser()->getId())->first();



            $this->say("{$user_name->name}-Your answer:{$answer->getText()} {$answerResult}");
           $this->user->increment('number_question', 1);
           $this->user->save();





            return $this->checkForNextQuestion();
        });
    }
    private function showResult()
    {

        if ($this->userPoints >= ceil($this->questionCount / 2)) {
            $this->say("yor score: {$this->userPoints} you win 🏁");
        } else {
            $this->say("your  score:  {$this->userPoints}  you lose");
        }
        $this->user->update(['status' => 'completed','number_question'=>1]);


        // $this->bot->driver(TelegramDriver::DRIVER_NAME)->endConversation();

        // $this->stopsConversation('your win');



    }

    public  function saveUser(UserInterface $botUser, int $userPoints, int $userCorrectAnswers, $number_question)
    {

        $user = UserScore::updateOrCreate(['chat_id' => $botUser->getId()], [
            'points' => $userPoints,
            'correct_answers' => $userCorrectAnswers,
            'status' => 'not-completed'
        ]);

        $user->increment('tries');
        //    $user->number_question= $this->user_chat->number_question+1;

        $user->save();

        return $user;
    }
}

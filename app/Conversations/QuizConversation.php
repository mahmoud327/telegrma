<?php

namespace App\Conversations;

use App\Models\Question;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

use BotMan\BotMan\Messages\Incoming\Answer as BotManAnswer;

use BotMan\BotMan\Messages\Outgoing\Question as BotManQuestion;


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
        $this->say('You will be shown '.$this->questionCount.' questions about Laravel. Every correct answer will reward you with a certain amount of points. Please keep it fair and don\'t use any help. All the best! 🍀');
        $this->checkForNextQuestion();
    }

    private function checkForNextQuestion()
    {

        if ($this->quizQuestions->count()) {
            return $this->askQuestion($this->quizQuestions->first());
        }

        // $this->showResult();
    }
    private function askQuestion(Question $question)
    {
        $questionTemplate = BotManQuestion::create($question->question);

        $questionText = '➡️ Question: '.$this->currentQuestion.' / '.$this->questionCount.' : '.$question->question;
        $questionTemplate = BotManQuestion::create($questionText);

        // foreach ($question->answers->shuffle() as $answer) {
        //     $questionTemplate->addButton(Button::create($answer->text)->value($answer->id));
        // }

 =
        $this->ask($this->createQuestionTemplate($question) , function (BotManAnswer $answer) use ($question) {
            $this->showResult();


            $this->say('Sorry, I did not get that. Please use the buttons.');

            // $quizAnswer = '';

            // if (! $quizAnswer) {
            //     // return $this->checkForNextQuestion();
            // }
            // // $this->replay('Sorry, I did not get that. Please use the buttons.');


            // $this->quizQuestions->forget($question->id);

            // if ($quizAnswer) {
            //     $this->userPoints += 1;
            //     $this->userCorrectAnswers++;
            //     $answerResult = '✅';
            // } else {
            //     $correctAnswer = Question::where('question',$question->question)->first()->answer;
            //     $answerResult = "❌ (Correct: {$correctAnswer})";
            // }
            // $this->currentQuestion++;

            // $this->say("Your answer: {$quizAnswer->answer} {$answerResult}");
            // $this->checkForNextQuestion();
        });
    }
    private function createQuestionTemplate(Question $question)
    {
        $questionText = '➡️ Question: '.$this->currentQuestion.' / '.$this->questionCount.' : '.$question->question;
        $questionTemplate = BotManQuestion::create($questionText);

        // $answer = $question->shuffle();

        // // foreach ($answers as $answer) {
        //     $questionTemplate->addButton(Button::create($answer->answer)->value($answer->id));
        // // }

    //    $questionTemplate->addButton(Button::create($question->answer)->value($question->id));


    //     // foreach ($answers as $answer) {
    //     //     $questionTemplate->addButton(Button::create($answer->text)->value($answer->id));
    //     // }

        return $questionTemplate;
    }


    private function showResult()
    {
        $this->say('Finished 🏁');
    }


}

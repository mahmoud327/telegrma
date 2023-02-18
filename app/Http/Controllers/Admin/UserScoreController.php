<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\UserScore;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class UserScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_scroes = UserScore::latest()->paginate(10);
        return view('admin.user_scores.index', compact('user_scroes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $question=Question::create($request->all());

       $telegram= Telegram::sendMessage([
            'chat_id'=>'-849059038',
            'text'=>
            "title:".$question->title."\n".
            "question:".$question->question
         ]);
        session()->flash('Add', 'تم اضافة سجل بنجاح ');
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $question = Question::findOrFail($id);
        $question->update($request->all());
        session()->flash('edit', 'تم اضافة سجل بنجاح ');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();
        session()->flash('delete', 'تم حذف سجل بنجاح ');
        return redirect()->back();
    }
}

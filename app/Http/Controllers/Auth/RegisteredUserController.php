<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Enums\UserRole;
use App\Models\WorkUnit;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\Question;
use App\Models\RespondentAnswer;
use App\Models\RespondentAnswerChildren;
use App\Models\RespondentScore;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        $registered_work_units = DB::table('users')
            ->select(DB::raw('DISTINCT(work_unit_id) as work_unit_id'))
            ->pluck('work_unit_id')
            ->toArray();

        $work_units = WorkUnit::whereNotIn('id',$registered_work_units)->get();
        return view('auth.register', compact('work_units'));
    }
    
    public function store(RegisterUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'work_unit_id'  => $validated["work_unit"],
            'username'      => $validated["username"],
            'email'         => $validated["email"],
            'role'          => UserRole::RESPONDENT,
            'password'      => Hash::make($validated["password"])
        ]);

        if ($user->role == UserRole::RESPONDENT)
        {
            $questions = Question::with('children')->get();
    
            foreach ($questions as $question) {
                RespondentAnswer::create([
                    'respondent_id' => $user->id,
                    'question_id'   => $question->id
                ]);
                foreach ($question->children as $question_child) {
                    RespondentAnswerChildren::create([
                        'respondent_id'         => $user->id,
                        'question_children_id'  => $question_child->id,
                        'question_id'           => $question->id
                    ]);
                }
            }
            RespondentScore::create([
                'respondent_id'         => $user->id
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Freshman;
use App\Models\Level;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (auth()->user()->isFreshman())
        {
            $user = auth()->user();
            $freshman = new Freshman($user);
            $tasks = $freshman->getTasks(false, true);
            $mentoring_tasks = $freshman->getMentoringTasks(false);
            $quests = $freshman->getQuests(false);
            $others = $freshman->getOtherTasks(false);
            $levels = Level::all();

            return view('dashboard', [
                'tasks' => $tasks,
                'freshman' => $freshman,
                'quests' => $quests,
                'levels' => $levels,
                'mentor_tasks' => $mentoring_tasks,
                'other_tasks' => $others,
            ]);
        }

        if (auth()->user()->isMember())
        {
            $user = auth()->user();
            $tasks = $user->getCreatedTasks();
            $freshmen = Freshman::getFreshmen();

            return view('dashboard', [
                'tasks' => $tasks,
                'freshmen' => $freshmen,
            ]);
        }

        return view('dashboard');
    }
}

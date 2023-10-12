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
            $tasks = $freshman->getTasks(false);
            $quests = $freshman->getQuests(false);
            $levels = Level::all();

            return view('dashboard', [
                'tasks' => $tasks,
                'freshman' => $freshman,
                'quests' => $quests,
                'levels' => $levels,
            ]);
        }

        return view('dashboard');
    }
}

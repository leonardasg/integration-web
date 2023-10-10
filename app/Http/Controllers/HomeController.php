<?php

namespace App\Http\Controllers;

use App\Models\Freshman;
use App\Models\User;

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
        if (auth()->user()->hasRole('freshman'))
        {
            $user = auth()->user();
            $freshman = new Freshman($user);
            $tasks = $freshman->getTasks(false);
            $quests = $freshman->getQuests(false);

            return view('dashboard', ['tasks' => $tasks, 'freshman' => $freshman, 'quests' => $quests]);
        }

        return view('dashboard');
    }
}

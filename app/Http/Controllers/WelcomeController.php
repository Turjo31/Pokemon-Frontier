<?php

namespace App\Http\Controllers;

use App\Models\Trainer;
use App\Models\Tournament;
use App\Models\MatchResult;

class WelcomeController extends Controller
{
    public function index()
    {
        $trainerCount    = Trainer::count();
        $tournamentCount = Tournament::count();
        $matchCount      = MatchResult::whereNotNull('winner_id')->count();

        return view('welcome', compact('trainerCount', 'tournamentCount', 'matchCount'));
    }
}
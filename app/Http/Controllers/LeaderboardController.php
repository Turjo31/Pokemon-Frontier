<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use App\Models\League;
use App\Models\MatchResult;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    public function index()
    {
        $trainer = Auth::guard('trainer')->user();

        $leagues = League::orderBy('league_id', 'desc')->get();

        $rankings = Ranking::with('trainer')
            ->orderBy('points', 'desc')
            ->get()
            ->map(fn($r) => [
                'trainer_id' => $r->trainer_id,
                'username'   => $r->trainer->username ?? 'Unknown',
                'points'     => $r->points,
                'wins'       => $r->wins,
                'losses'     => $r->losses,
            ])->toArray();

        $myRanking = Ranking::where('trainer_id', $trainer->trainer_id)
                            ->orderBy('points', 'desc')
                            ->first();

        $myRank    = Ranking::where('points', '>', $myRanking->points ?? 0)->count() + 1;
        $myPoints  = $myRanking->points  ?? 0;
        $myWins    = $myRanking->wins    ?? 0;
        $myLosses  = $myRanking->losses  ?? 0;

        $totalMatches = MatchResult::count();

        $recentForm = MatchResult::where('trainer1_id', $trainer->trainer_id)
            ->orWhere('trainer2_id', $trainer->trainer_id)
            ->orderBy('match_date', 'desc')
            ->take(8)
            ->get()
            ->map(fn($m) => $m->winner_id == $trainer->trainer_id ? 'W' : 'L')
            ->toArray();

        return view('leaderboard', compact(
            'rankings', 'leagues', 'myRank', 'myPoints',
            'myWins', 'myLosses', 'totalMatches', 'recentForm'
        ));
    }
}
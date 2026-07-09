<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use App\Models\MatchResult;
use App\Models\Trainer;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $trainer = Auth::guard('trainer')->user();

        // Get trainer's ranking in the most recent league
        $myRanking = Ranking::where('trainer_id', $trainer->trainer_id)
            ->orderBy('league_id', 'desc')
            ->first();

        $rankPoints = $myRanking->points ?? 0;
        $wins = $myRanking->wins ?? 0;
        $losses = $myRanking->losses ?? 0;

        // Global rank — how many trainers have more points
        $globalRank = Ranking::where('points', '>', $rankPoints)->count() + 1;

        // Total trainers
        $totalTrainers = Trainer::count();

        // Team count
        $teamCount = $trainer->teams()->count();

        // Recent matches (last 5)
        $recentMatches = MatchResult::where('trainer1_id', $trainer->trainer_id)
            ->orWhere('trainer2_id', $trainer->trainer_id)
            ->orderBy('match_date', 'desc')
            ->take(5)
            ->get()
            ->map(function ($match) use ($trainer) {
                $isTrainer1 = $match->trainer1_id == $trainer->trainer_id;
                $opponentId = $isTrainer1 ? $match->trainer2_id : $match->trainer1_id;
                $opponent = Trainer::find($opponentId);
                return [
                    'winner_id' => $match->winner_id,
                    'opponent_name' => $opponent->username ?? 'Unknown',
                    'tournament_name' => $match->tournament->name ?? '—',
                    'my_score' => $isTrainer1 ? $match->team1_score : $match->team2_score,
                    'opp_score' => $isTrainer1 ? $match->team2_score : $match->team1_score,
                ];
            });

        // Top 5 rankings
        $topRankings = Ranking::with('trainer')
            ->orderBy('points', 'desc')
            ->take(5)
            ->get()
            ->map(function ($r) {
                return [
                    'trainer_id' => $r->trainer_id,
                    'username' => $r->trainer->username ?? 'Unknown',
                    'points' => $r->points,
                    'wins' => $r->wins,
                    'losses' => $r->losses,
                ];
            });

        return view('dashboard', compact(
            'rankPoints',
            'wins',
            'losses',
            'globalRank',
            'totalTrainers',
            'teamCount',
            'recentMatches',
            'topRankings'
        ));
    }
}
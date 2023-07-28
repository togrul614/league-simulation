<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\RedirectResponse;

class LeagueController extends Controller
{

    public function simulateMatches()
    {
        // Get all teams
        $teams = Team::all();
        $teamCount = count($teams);

        // Initialize weekly fixtures array
        $fixtures = [];

        // Generate weekly match fixtures (Round-robin format)
        for ($week = 1; $week <= $teamCount - 1; $week++) {
            $fixtures[$week] = [];
            for ($i = 0; $i < $teamCount / 2; $i++) {
                $homeTeamIndex = $i;
                $awayTeamIndex = $teamCount - 1 - $i;

                $fixtures[$week][] = [$teams[$homeTeamIndex], $teams[$awayTeamIndex]];
            }

            // Rotate teams to create fixtures for the next week
            $lastTeam = $teams->pop();
            $teams->splice(1, 0, $lastTeam);
        }

        // Simulate matches and update standings week by week
        foreach ($fixtures as $week => $matchWeek) {
            foreach ($matchWeek as $match) {
                $homeTeam = $match[0];
                $awayTeam = $match[1];
                $this->playMatch($homeTeam, $awayTeam);
            }
        }

        // Sort teams based on points and goal difference
        $teams = $teams->sortByDesc(function ($team) {
            return [$team->points, $team->goals_scored - $team->goals_conceded];
        });

        return view('standings', compact('teams'));
    }

    private function playMatch($homeTeam, $awayTeam)
    {
        if (is_numeric($awayTeam)){
            dd($awayTeam);
        }
        // Simulate a match between two teams
        $homeScore = rand(0, min($homeTeam->strength, 6));
        $awayScore = rand(0, min($awayTeam->strength, 6));

        // Update team statistics
        $homeTeam->goals_scored += $homeScore;
        $homeTeam->goals_conceded += $awayScore;
        $awayTeam->goals_scored += $awayScore;
        $awayTeam->goals_conceded += $homeScore;

        // Update points based on match result (3 points for a win, 1 for a draw)
        if ($homeScore > $awayScore) {
            $homeTeam->points += 3;
        } elseif ($homeScore === $awayScore) {
            $homeTeam->points += 1;
            $awayTeam->points += 1;
        } else {
            $awayTeam->points += 3;
        }

        $homeTeam->save();
        $awayTeam->save();
    }

    public function displayStandings()
    {
        // Retrieve all teams from the database and sort based on points and goal difference
        $teams = Team::orderByDesc('points')->orderByDesc('goals_difference')->get();

        return view('standings', compact('teams'));
    }
}

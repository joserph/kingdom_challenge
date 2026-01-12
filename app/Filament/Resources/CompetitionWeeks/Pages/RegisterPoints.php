<?php

namespace App\Filament\Resources\CompetitionWeeks\Pages;

use App\Filament\Resources\CompetitionWeeks\CompetitionWeekResource;
use App\Models\CompetitionWeek;
use App\Models\Score;
use App\Models\Team;
use App\Models\Youth;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;

class RegisterPoints extends Page implements HasForms
{
    use InteractsWithForms;
    protected static string $resource = CompetitionWeekResource::class;

    protected string $view = 'filament.resources.competition-weeks.pages.register-points';
    
    // Public variables
    public CompetitionWeek $record;
    public ?array $data = [];
    public $teams = [];
    public $gameWinnerTeamId = null;
    public $gamePointsWinner = 0;

    // public $youths = Youth::where('active', true)->all();
    
    
    public function mount(): void
    {
        // foreach ($this->youths as $youth) {
        //     $key = 'youth_' . $youth->id;
        //     $this->data[$key] = [
        //         'attendance' => $youth->attendance ?? 0,
        //         'punctuality' => $youth->punctuality ?? 0,
        //         'bible' => $youth->bible ?? 0,
        //         'guest' => $youth->guest ?? 0,
        //     ];
        // }

        // Load active teams with their youths
        $this->teams = Team::where('active', true)
            ->with(['youths' => function ($query) {
                $query->where('active', true)->orderBy('name');
            }])
            ->get();
        // dd($this->teams);
        // Load existing scores
        $scores = Score::where('competition_week_id', $this->record->id)->get();
        
        foreach ($scores as $score) {
            $this->data['youth_' . $score->youth_id] = [
                'attendance' => $score->attendance,
                'punctuality' => $score->punctuality,
                'bible' => $score->bible,
                'guest' => $score->guest,
                'observations' => $score->observations,
            ];
        }
        
        // Load game data
        $this->gameWinnerTeamId = $this->record->game_winner_team_id;
        $this->gamePointsWinner = $this->record->game_points_winner ?? 0;
    }

    // When game winner changes, update points
    public function updatedGameWinnerTeamId($value)
    {
        $this->gamePointsWinner = $value ? 50 : 0;
    }

    // Save all points
    public function save()
    {
        try {
            DB::transaction(function () {
                // 1. Save individual points
                foreach ($this->data as $key => $points) {
                    if (str_starts_with($key, 'youth_')) {
                        $youthId = str_replace('youth_', '', $key);
                        
                        Score::updateOrCreate(
                            [
                                'youth_id' => $youthId,
                                'competition_week_id' => $this->record->id,
                            ],
                            [
                                'attendance' => $points['attendance'] ?? false,
                                'punctuality' => $points['punctuality'] ?? false,
                                'bible' => $points['bible'] ?? false,
                                'guest' => $points['guest'] ?? false,
                                'observations' => $points['observations'] ?? '',
                            ]
                        );
                    }
                }
                
                // 2. Save game data
                $this->record->update([
                    'game_winner_team_id' => $this->gameWinnerTeamId,
                    'game_points_winner' => $this->gamePointsWinner,
                    'status' => 'completed',
                ]);
            });
            
            // $this->notify('success', '✅ Points saved successfully');
            Notification::make()
                ->title('Points saved successfully')
                ->success()
                ->send();
            return redirect()->to(CompetitionWeekResource::getUrl('index'));
        } catch (\Exception $e) {
            // $this->notify('error', '❌ Error: ' . $e->getMessage());
            Notification::make()
                ->title('❌ Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    // Calculate individual points
    public function calculateIndividualPoints($points)
    {
        $total = 0;
        $total += $points['attendance'] ?? false ? 10 : 0;
        $total += $points['punctuality'] ?? false ? 10 : 0;
        $total += $points['bible'] ?? false ? 5 : 0;
        $total += $points['guest'] ?? false ? 20 : 0;
        return $total;
    }

    // Get points per youth
    public function getPointsPerYouth($youthId)
    {
        $key = 'youth_' . $youthId;
        return isset($this->data[$key]) 
            ? $this->calculateIndividualPoints($this->data[$key]) 
            : 0;
    }

    // Calculate points per team
    public function getTeamPoints($teamId)
    {
        $team = $this->teams->find($teamId);
        if (!$team) return 0;
        
        $total = 0;
        foreach ($team->youths as $youth) {
            $total += $this->getPointsPerYouth($youth->id);
        }
        // dd($total);
        // Add game points
        if ($this->gameWinnerTeamId == $teamId) {
            $total += $this->gamePointsWinner;
        }
        
        return $total;
    }
}

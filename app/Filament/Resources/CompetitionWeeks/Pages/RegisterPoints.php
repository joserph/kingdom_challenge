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
    // public $gameWinnerTeamId = null;
    public $gamePointsWinner = 0;
    // Variables para múltiples juegos
    public $games = [];
    public $gameCount = 2; // Por defecto 2 juegos
    public $maxGames = 5; // Máximo de juegos posibles

    // public $youths = Youth::where('active', true)->all();
    
    
    public function mount(): void
    {
        // Inicializar juegos
        $this->initializeGames();

        // Cargar datos existentes de juegos
        $this->loadExistingGameData();
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
        // $this->gameWinnerTeamId = $this->record->game_winner_team_id;
        $this->gamePointsWinner = $this->record->game_points_winner ?? 0;
    }

    // When game winner changes, update points
    // public function updatedGameWinnerTeamId($value)
    // {
    //     $this->gamePointsWinner = $value ? 50 : 0;
    // }

    // Save all points
    // public function save()
    // {
    //     try {
    //         DB::transaction(function () {
    //             // 1. Save individual points
    //             foreach ($this->data as $key => $points) {
    //                 if (str_starts_with($key, 'youth_')) {
    //                     $youthId = str_replace('youth_', '', $key);
                        
    //                     Score::updateOrCreate(
    //                         [
    //                             'youth_id' => $youthId,
    //                             'competition_week_id' => $this->record->id,
    //                         ],
    //                         [
    //                             'attendance' => $points['attendance'] ?? false,
    //                             'punctuality' => $points['punctuality'] ?? false,
    //                             'bible' => $points['bible'] ?? false,
    //                             'guest' => $points['guest'] ?? false,
    //                             'observations' => $points['observations'] ?? '',
    //                         ]
    //                     );
    //                 }
    //             }
                
    //             // 2. Save game data
    //             $this->record->update([
    //                 'game_winner_team_id' => $this->gameWinnerTeamId,
    //                 'game_points_winner' => $this->gamePointsWinner,
    //                 'status' => 'completed',
    //             ]);
    //         });
            
    //         // $this->notify('success', '✅ Points saved successfully');
    //         Notification::make()
    //             ->title('Points saved successfully')
    //             ->success()
    //             ->send();
    //         return redirect()->to(CompetitionWeekResource::getUrl('index'));
    //     } catch (\Exception $e) {
    //         // $this->notify('error', '❌ Error: ' . $e->getMessage());
    //         Notification::make()
    //             ->title('❌ Error')
    //             ->body($e->getMessage())
    //             ->danger()
    //             ->send();
    //     }
    // }
    public function save()
    {
        try {
            DB::transaction(function () {
                // 1. Guardar puntos individuales (tu código existente)
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
                
                // 2. Guardar datos de juegos (NUEVO)
                // Preparar solo juegos habilitados
                $gameData = [];
                
                foreach ($this->games as $index => $game) {
                    if ($game['enabled']) {
                        $gameData[] = [
                            'winner_team_id' => $game['winner_team_id'],
                            'points' => $game['points'],
                        ];
                    }
                }
                
                // Para compatibilidad con versiones anteriores
                $firstGame = $gameData[0] ?? null;
                
                $this->record->update([
                    'game_winner_team_id' => $firstGame['winner_team_id'] ?? null,
                    'game_points_winner' => $firstGame['points'] ?? 0,
                    'game_data' => json_encode($gameData),
                    'game_count' => $this->gameCount,
                    'status' => 'completed',
                ]);
            });
            
            Notification::make()
                ->title('Puntos guardados exitosamente')
                ->success()
                ->send();
                
            return redirect()->to(CompetitionWeekResource::getUrl('index'));
        } catch (\Exception $e) {
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
        // $team = $this->teams->find($teamId);
        // if (!$team) return 0;
        
        // $total = 0;
        // foreach ($team->youths as $youth) {
        //     $total += $this->getPointsPerYouth($youth->id);
        // }
        // // dd($total);
        // // Add game points
        // if ($this->gameWinnerTeamId == $teamId) {
        //     $total += $this->gamePointsWinner;
        // }
        
        // return $total;
        $team = $this->teams->find($teamId);
        if (!$team) return 0;
        
        $total = 0;
        
        // Sumar puntos individuales de los jóvenes (tu código existente)
        foreach ($team->youths as $youth) {
            $total += $this->getPointsPerYouth($youth->id);
        }
        
        // Sumar puntos de juegos ganados por el equipo
        $total += $this->getTeamGamePoints($teamId);
        
        return $total;
    }

    // Inicializar estructura de juegos
    protected function initializeGames()
    {
        for ($i = 0; $i < $this->maxGames; $i++) {
            $this->games[$i] = [
                'winner_team_id' => null,
                'points' => 0,
                'enabled' => $i < $this->gameCount,
            ];
        }
    }

    // Cargar datos existentes de juegos desde la base de datos
    protected function loadExistingGameData()
    {
        // Si ya hay datos guardados, cargarlos
        if ($this->record->game_data) {
            $savedGames = json_decode($this->record->game_data, true);
            
            if (is_array($savedGames)) {
                // Restaurar número de juegos
                $this->gameCount = count($savedGames);
                
                // Cargar datos de cada juego
                foreach ($savedGames as $index => $game) {
                    if (isset($this->games[$index])) {
                        $this->games[$index] = array_merge($this->games[$index], $game);
                        $this->games[$index]['enabled'] = true;
                    }
                }
            }
        }
        // Para compatibilidad con versiones anteriores
        elseif ($this->record->game_winner_team_id) {
            $this->games[0]['winner_team_id'] = $this->record->game_winner_team_id;
            $this->games[0]['points'] = $this->record->game_points_winner ?? 0;
            $this->games[0]['enabled'] = true;
        }
    }

    // Cuando cambia el número de juegos
    public function updatedGameCount($value)
    {
        $this->gameCount = max(2, min($this->maxGames, $value));
        
        // Habilitar/deshabilitar juegos según el conteo
        for ($i = 0; $i < $this->maxGames; $i++) {
            $this->games[$i]['enabled'] = $i < $this->gameCount;
            if (!$this->games[$i]['enabled']) {
                $this->games[$i]['winner_team_id'] = null;
                $this->games[$i]['points'] = 0;
            }
        }
    }

    // Cuando cambia el ganador de un juego específico
    public function updatedGames($value, $index)
    {
        $parts = explode('.', $index);
        
        if (count($parts) >= 3 && $parts[1] === 'winner_team_id') {
            $gameIndex = $parts[0];
            $this->games[$gameIndex]['points'] = $value ? 50 : 0;
        }
    }

    /// O también puedes hacerlo así (más explícito):
    public function getTeamGamePoints($teamId)
    {
        $total = 0;
        foreach ($this->games as $game) {
            if ($game['enabled'] && $game['winner_team_id'] == $teamId) {
                $total += 50; // 50 puntos fijos por cada juego ganado
            }
        }
        return $total;
    }

    // Obtener número de juegos ganados por equipo
    public function getTeamWins($teamId)
    {
        $wins = 0;
        foreach ($this->games as $game) {
            if ($game['enabled'] && $game['winner_team_id'] == $teamId) {
                $wins++;
            }
        }
        return $wins;
    }

    // Obtener todos los juegos ganados por el equipo
    public function getTeamWinningGames($teamId)
    {
        $winningGames = [];
        foreach ($this->games as $index => $game) {
            if ($game['enabled'] && $game['winner_team_id'] == $teamId) {
                $winningGames[] = $index + 1;
            }
        }
        return $winningGames;
    }
}

<x-filament-panels::page>
    <form wire:submit="save">
        <div class="fi-sc-component">
            <div class="fi-wi-stats-overview-stat">
                <div class="fi-wi-stats-overview-stat-content">
                    <div class="fi-wi-stats-overview-stat-label-ctn">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                            Registrar Puntos - {{ $record->name }}
                        </h1>
                    </div>
                    <div class="fi-wi-stats-overview-stat-value">
                        Semana {{ $record->week_number }} • {{ $record->date }}
                    </div>
                </div>
            </div>
        </div>
        <x-filament::section class="mt-8">
            <div class="fi-card overflow-hidden">
                <div class="fi-ta-container overflow-x-auto">
                    <table class="fi-ta-table w-full text-sm">
                        <thead class="fi-ta-header">
                            <tr class="fi-ta-header-row">
                                <th class="fi-ta-header-cell text-left">
                                    Criterio
                                </th>
                                <th class="fi-ta-header-cell text-center">
                                    Puntos
                                </th>
                                <th class="fi-ta-header-cell text-center">
                                    Estado
                                </th>
                            </tr>
                        </thead>
                        <tbody class="fi-ta-body">
                            @foreach ([
                                ['label' => 'Asistencia', 'points' => 10, 'active' => true],
                                ['label' => 'Puntualidad', 'points' => 10, 'active' => true],
                                ['label' => 'Biblia', 'points' => 5, 'active' => true],
                                ['label' => 'Invitados', 'points' => 15, 'active' => true],
                            ] as $item)
                                <tr class="fi-ta-row">
                                    <td class="fi-ta-cell">
                                        {{ $item['label'] }}
                                    </td>
                                    <td class="fi-ta-cell text-center font-medium">
                                        {{ $item['points'] }}
                                    </td>
                                    <td class="fi-ta-cell text-center">
                                        @if ($item['active'])
                                            <span class="fi-badge fi-color-success">Activo</span>
                                        @else
                                            <span class="fi-badge fi-color-danger">Inactivo</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </x-filament::section>  
        <x-filament::section>
        @foreach ($teams as $team)
            <div class="fi-card overflow-hidden">
                <div class="fi-ta-container overflow-x-auto" style="background-color: {{ $team->light_color }}">
                    <table class="fi-ta-table w-full text-sm">
                        <thead class="fi-ta-header">
                            <tr>
                                <th class="fi-ta-header-cell text-left">
                                    <h2 class="text-xl font-bold flex items-center gap-2" style="color: {{ $team->color }}">
                                        {{ $team->name }}

                                        @php
                                            $teamWins = $this->getTeamWins($team->id);
                                            $winningGames = $this->getTeamWinningGames($team->id);
                                        @endphp
                                
                                {{-- @if($teamWins > 0)
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium
                                            bg-warning-100 text-warning-800
                                            dark:bg-warning-900 dark:text-warning-200">
                                        @if($teamWins == 1)
                                            🏆 Juego {{ $winningGames[0] }} +50 pts
                                        @else
                                            🏆 {{ $teamWins }} juegos +{{ $teamWins * 50 }} pts
                                        @endif
                                    </span>
                                @endif --}}
                                    </h2>
                                </th>
                                <th class="fi-ta-header-cell text-center">
                                    {{ $this->getTeamPoints($team->id) }} Puntos
                                </th>
                            </tr>
                            
                            <tr class="fi-ta-header-row">
                                <th class="fi-ta-header-cell text-left">
                                    Nombre
                                </th>
                                <th class="fi-ta-header-cell text-center">
                                    Puntos
                                </th>
                                <th class="fi-ta-header-cell text-center">
                                    Asistencia
                                </th>
                                <th class="fi-ta-header-cell text-center">
                                    Puntualidad
                                </th>
                                <th class="fi-ta-header-cell text-center">
                                    Biblia
                                </th>
                                <th class="fi-ta-header-cell text-center">
                                    Invitado
                                </th>
                            </tr>
                        </thead>
                        
                        @foreach ($team->youths as $youth)
                            @php
                                $key = 'youth_' . $youth->id;
                                $individualPoints = $this->getPointsPerYouth($youth->id);
                            @endphp
                            
                            <tbody class="fi-ta-body">
                                <tr class="fi-ta-row">
                                    <td class="fi-ta-cell">
                                        {{ $youth->name }}
                                    </td>
                                    <td class="fi-ta-cell">
                                        {{ $individualPoints }}
                                    </td>
                                    
                                    @foreach ([
                                        ['key' => 'attendance', 'label' => 'Asistencia', 'points' => 10],
                                        ['key' => 'punctuality', 'label' => 'Puntualidad', 'points' => 10],
                                        ['key' => 'bible', 'label' => 'Biblia', 'points' => 5],
                                        ['key' => 'guest', 'label' => 'Invitado', 'points' => 20],
                                    ] as $item)
                                        <td class="fi-ta-cell">
                                            @php
                                                // Verificar si el checkbox debe estar activo
                                                // Esto depende de cómo estés manejando los datos
                                                $isChecked = false;
                                                
                                                // Opción 1: Si los datos vienen del modelo $youth
                                                if (isset($youth->{$item['key']}) && $youth->{$item['key']} == 1) {
                                                    $isChecked = true;
                                                }
                                                
                                                // Opción 2: Si los datos vienen del array $data
                                                if (isset($data[$key][$item['key']]) && $data[$key][$item['key']] == 1) {
                                                    $isChecked = true;
                                                }
                                                
                                                // Opción 3: Si estás usando una propiedad computada
                                                $checkboxValue = $isChecked ? 1 : 0;
                                            @endphp
                                            
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input
                                                    type="checkbox"
                                                    wire:model.live="data.{{ $key }}.{{ $item['key'] }}"
                                                    value="1"
                                                    @if($isChecked) checked @endif
                                                    class="sr-only peer"
                                                >
                                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 
                                                        peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full 
                                                        peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white 
                                                        after:content-[''] after:absolute after:top-[2px] after:left-[2px] 
                                                        after:bg-white after:border-gray-300 after:border after:rounded-full 
                                                        after:h-5 after:w-5 after:transition-all dark:border-gray-600 
                                                        peer-checked:bg-blue-600">
                                                </div>
                                            </label>
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        @endforeach
                    </table>
                </div>
            </div>
        @endforeach
        </x-filament::section>  
        
        {{-- <x-filament::section>
            <x-slot name="heading">
                🏆 Ganador de los Juegos (+50 pts)
            </x-slot>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ($teams as $team)
                    <div
                        wire:click="$set('gameWinnerTeamId', {{ $team->id }})"
                        class="cursor-pointer"
                    >
                        <div
                            class="
                                p-4
                                rounded-xl
                                border
                                text-center
                                transition
                                duration-200
                                {{ $gameWinnerTeamId === $team->id
                                    ? 'ring-2 ring-offset-2'
                                    : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600'
                                }}
                            "
                            @if ($gameWinnerTeamId === $team->id)
                                style="
                                    border-color: {{ $team->color }};
                                    background-color: {{ $team->light_color }};
                                    --tw-ring-color: {{ $team->color }};
                                "
                            @endif
                        >
                            <div
                                class="text-lg font-bold"
                                style="color: {{ $team->color }}"
                            >
                                {{ $team->name }}
                            </div>

                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                +50 pts al equipo
                            </div>
                        </div>
                    </div>
                @endforeach

                    
                <div
                    wire:click="$set('gameWinnerTeamId', null)"
                    class="cursor-pointer"
                >
                    <div
                        class="
                            p-4
                            rounded-xl
                            border
                            text-center
                            transition
                            duration-200
                            {{ is_null($gameWinnerTeamId)
                                ? 'border-gray-400 bg-gray-100 dark:bg-gray-800'
                                : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600'
                            }}
                        "
                    >
                        <div class="text-lg font-bold">
                            Sin ganador
                        </div>

                        <div class="text-sm text-gray-600 dark:text-gray-300">
                            Empate o no aplica
                        </div>
                    </div>
                </div>
            </div>

            @if ($gameWinnerTeamId)
                @php
                    $winningTeam = $teams->firstWhere('id', $gameWinnerTeamId);
                @endphp

                <x-filament::card class="mt-4 bg-warning-50 dark:bg-warning-900/20">
                    <p class="text-sm text-warning-800 dark:text-warning-200">
                        ⚡ El equipo
                        <strong style="color: {{ $winningTeam->color }}">
                            {{ $winningTeam->name }}
                        </strong>
                        recibirá <strong>50 puntos adicionales</strong> por ganar los juegos.
                    </p>
                </x-filament::card>
            @endif
        </x-filament::section> --}}
        <x-filament::section>
            <x-slot name="heading">
                🏆 Juegos de Equipo ({{ $gameCount }} juegos × 50 pts)
            </x-slot>
            
            <!-- Selector de número de juegos -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Número de juegos de esta semana:
                </label>
                <div class="flex flex-wrap gap-2">
                    @for($i = 2; $i <= 5; $i++)
                        <button
                            type="button"
                            wire:click="$set('gameCount', {{ $i }})"
                            class="px-4 py-2 rounded-lg transition-all duration-200
                                {{ $gameCount == $i 
                                    ? 'bg-primary-600 text-white shadow-md' 
                                    : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700'
                                }}"
                        >
                            {{ $i }} juego{{ $i > 1 ? 's' : '' }}
                        </button>
                    @endfor
                </div>
            </div>

            <!-- Juegos individuales -->
            <div class="space-y-6">
                @for($i = 0; $i < $gameCount; $i++)
                    @php
                        $game = $games[$i];
                        $winningTeam = $game['winner_team_id'] ? $teams->firstWhere('id', $game['winner_team_id']) : null;
                    @endphp
                    
                    <div class="border rounded-xl p-4 
                        {{ $game['winner_team_id'] 
                            ? 'border-green-300 bg-green-50 dark:bg-green-900/20' 
                            : 'border-gray-200 dark:border-gray-700'
                        }}">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold">
                                Juego {{ $i + 1 }}
                                @if($game['winner_team_id'])
                                    <span class="inline-flex items-center gap-1 text-sm font-medium px-3 py-1 rounded-full
                                        bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        ✔ Completado
                                    </span>
                                @endif
                            </h3>
                            
                            <div class="text-xl font-bold 
                                {{ $game['points'] ? 'text-green-600 dark:text-green-400' : 'text-gray-400' }}">
                                {{ $game['points'] }} pts
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-{{ count($teams) + 1 }} gap-4">
                            @foreach ($teams as $team)
                                <div
                                    wire:click="$set('games.{{ $i }}.winner_team_id', {{ $team->id }})"
                                    class="cursor-pointer"
                                >
                                    <div
                                        class="
                                            p-4
                                            rounded-xl
                                            border
                                            text-center
                                            transition
                                            duration-200
                                            {{ $game['winner_team_id'] === $team->id
                                                ? 'ring-2 ring-offset-2'
                                                : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600'
                                            }}
                                        "
                                        @if ($game['winner_team_id'] === $team->id)
                                            style="
                                                border-color: {{ $team->color }};
                                                background-color: {{ $team->light_color }};
                                                --tw-ring-color: {{ $team->color }};
                                            "
                                        @endif
                                    >
                                        <div
                                            class="text-lg font-bold"
                                            style="color: {{ $team->color }}"
                                        >
                                            {{ $team->name }}
                                        </div>

                                        <div class="text-sm text-gray-600 dark:text-gray-300">
                                            +50 pts
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Opción sin ganador -->
                            <div
                                wire:click="$set('games.{{ $i }}.winner_team_id', null)"
                                class="cursor-pointer"
                            >
                                <div
                                    class="
                                        p-4
                                        rounded-xl
                                        border
                                        text-center
                                        transition
                                        duration-200
                                        {{ is_null($game['winner_team_id'])
                                            ? 'border-gray-400 bg-gray-100 dark:bg-gray-800'
                                            : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600'
                                        }}
                                    "
                                >
                                    <div class="text-lg font-bold">
                                        Sin ganador
                                    </div>

                                    <div class="text-sm text-gray-600 dark:text-gray-300">
                                        Empate o no aplica
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </x-filament::section>

        
        <x-filament::section class="mt-8">
            <x-slot name="heading">
                📊 Resumen de la Semana
            </x-slot>

            <div class="grid grid-cols-1 md:grid-cols-{{ count($teams) }} gap-6">
                @foreach ($teams as $team)
                    <x-filament::card
                        style="
                            border-color: {{ $team->color }}30;
                            background-color: {{ $team->light_color }}10;
                        "
                        class="border"
                    >
                        <h4
                            class="font-bold mb-2"
                            style="color: {{ $team->color }}"
                        >
                            {{ $team->name }}
                        </h4>

                        <div
                            class="text-3xl font-bold mb-1"
                            style="color: {{ $team->color }}"
                        >
                            {{ $this->getTeamPoints($team->id) }} pts
                        </div>

                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $team->youths->count() }} jóvenes activos
                        </p>

                        {{-- @if ($gameWinnerTeamId === $team->id)
                            <p class="mt-2 text-sm font-medium text-warning-600 dark:text-warning-400">
                                +50 pts por juegos
                            </p>
                        @endif --}}
                    </x-filament::card>
                @endforeach
            </div>

            {{-- Ganador de la semana --}}
            @php
                $teamPoints = [];

                foreach ($teams as $team) {
                    $teamPoints[$team->id] = $this->getTeamPoints($team->id);
                }

                arsort($teamPoints);

                $winningTeamId = array_key_first($teamPoints);
                $maxPoints = reset($teamPoints);
                $secondPoints = count($teamPoints) > 1 ? array_values($teamPoints)[1] : 0;
            @endphp

            @if (count($teamPoints) > 1 && $maxPoints > 0)
                <x-filament::card
                    class="mt-6
                        {{ $maxPoints === $secondPoints
                            ? 'bg-gray-100 dark:bg-gray-800'
                            : 'bg-success-50 dark:bg-success-900/20'
                        }}"
                >
                    <div class="flex items-start gap-3">
                        <div class="text-2xl">
                            {{ $maxPoints === $secondPoints ? '⚖️' : '🏆' }}
                        </div>

                        <div>
                            <p
                                class="font-bold
                                    {{ $maxPoints === $secondPoints
                                        ? 'text-gray-700 dark:text-gray-300'
                                        : 'text-success-700 dark:text-success-300'
                                    }}"
                            >
                                @if ($maxPoints === $secondPoints)
                                    ¡Empate! Ambos equipos tienen {{ $maxPoints }} puntos
                                @else
                                    @php
                                        $winningTeam = $teams->firstWhere('id', $winningTeamId);
                                    @endphp
                                    ¡Equipo
                                    <span style="color: {{ $winningTeam->color }}">
                                        {{ $winningTeam->name }}
                                    </span>
                                    lidera!
                                @endif
                            </p>

                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Diferencia: {{ $maxPoints - $secondPoints }} puntos
                            </p>
                        </div>
                    </div>
                </x-filament::card>
            @endif
        </x-filament::section>


       

        {{-- <div class="mt-8 flex items-center justify-between border-t border-gray-200 pt-6 dark:border-gray-700">
            <x-filament::button
                tag="a"
                href="{{ route('filament.admin.resources.competition-weeks.index') }}"
                color="gray"
                outlined
            >
                Cancelar
            </x-filament::button>

            <div class="flex items-center gap-4">
                <div class="text-sm font-bold">
                    @foreach ($teams as $team)
                        <span class="mr-4" style="color: {{ $team->color }}">
                            {{ $team->name }}: {{ $this->getTeamPoints($team->id) }}
                        </span>
                    @endforeach
                </div>

                <x-filament::button
                    type="submit"
                    color="primary"
                    size="lg"
                    icon="heroicon-o-check"
                >
                    Finalizar y Guardar
                </x-filament::button>
            </div>
        </div> --}}
        <div class="mt-8 flex items-center justify-between border-t border-gray-200 pt-6 dark:border-gray-700">
    <x-filament::button
        tag="a"
        href="{{ route('filament.admin.resources.competition-weeks.index') }}"
        color="gray"
        outlined
    >
        Cancelar
    </x-filament::button>

    <div class="flex items-center gap-4">
        <div class="text-sm font-bold space-y-1">
            @foreach ($teams as $team)
                @php
                    $teamPoints = $this->getTeamPoints($team->id);
                    $teamGamePoints = $this->getTeamGamePoints($team->id); // Esto ya es 50 por cada juego ganado
                    $teamWins = $this->getTeamWins($team->id);
                    $totalGamePoints = $teamWins * 50; // 50 puntos por cada juego ganado
                @endphp
                
                <div class="flex items-center gap-2">
                    <span style="color: {{ $team->color }}" class="font-bold">
                        {{ $team->name }}: {{ $teamPoints }}
                    </span>
                    
                    @if($teamWins > 0)
                        <span class="text-xs bg-warning-100 text-warning-800 dark:bg-warning-900 dark:text-warning-200 px-2 py-0.5 rounded-full">
                            +{{ $totalGamePoints }} pts ({{ $teamWins }}×50)
                        </span>
                    @endif
                </div>
            @endforeach
            
            <!-- Resumen total de juegos -->
            @php
                $totalGamesPlayed = $gameCount;
                $totalGamePointsAwarded = 0;
                foreach ($teams as $team) {
                    $totalGamePointsAwarded += $this->getTeamGamePoints($team->id);
                }
            @endphp
            
            <div class="text-xs text-gray-500 dark:text-gray-400 pt-1 border-t border-gray-100 dark:border-gray-700 mt-1">
                Total juegos: {{ $totalGamesPlayed }} • Puntos en juegos: {{ $totalGamePointsAwarded }} pts
            </div>
        </div>

        <x-filament::button
            type="submit"
            color="primary"
            size="lg"
            icon="heroicon-o-check"
        >
            Finalizar y Guardar
        </x-filament::button>
    </div>
</div>
        <hr>
        
    </form>
</x-filament-panels::page>
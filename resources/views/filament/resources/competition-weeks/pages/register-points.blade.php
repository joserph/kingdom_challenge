<x-filament-panels::page>
    <form wire:submit="save">
        <!-- HEADER -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Registrar Puntos - {{ $record->name }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Semana {{ $record->week_number }} • {{ $record->date->format('d/m/Y') }}
            </p>
        </div>

        <!-- GAME WINNER SELECTOR -->
        <div class="mb-8 p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">🏆 Ganador de los Juegos (+50 pts)</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                @foreach($teams as $team)
                <div class="cursor-pointer" wire:click="$set('gameWinnerTeamId', {{ $team->id }})">
                    {{-- <div class="p-4 border-2 rounded-lg text-center transition-all
                        {{ $gameWinnerTeamId == $team->id 
                            ? 'border-[{{ $team->color }}] bg-[{{ $team->light_color }}]'
                            : 'border-gray-200 dark:border-gray-700 hover:border-gray-300' }}"> --}}
                        <div class="p-4 border-2 rounded-lg text-center transition-all
                                {{ $gameWinnerTeamId == $team->id
                                    ? 'border-4'
                                    : 'border-gray-200 dark:border-gray-700 hover:border-gray-300' }}"
                            @if($gameWinnerTeamId == $team->id)
                                style="border-color: {{ $team->color }}; background-color: {{ $team->light_color }};"
                            @endif>
                        <div class="text-lg font-bold" style="color: {{ $team->color }}">
                            {{ $team->name }}
                        </div>
                        <div class="text-sm">+50 pts al equipo</div>
                    </div>
                </div>
                @endforeach
                
                <!-- Opción sin ganador -->
                <div class="cursor-pointer" wire:click="$set('gameWinnerTeamId', null)">
                    <div class="p-4 border-2 rounded-lg text-center transition-all
                        {{ !$gameWinnerTeamId 
                            ? 'border-gray-500 bg-gray-100 dark:bg-gray-800'
                            : 'border-gray-200 dark:border-gray-700 hover:border-gray-300' }}">
                        <div class="text-lg font-bold">Sin ganador</div>
                        <div class="text-sm">Empate o no aplica</div>
                    </div>
                </div>
            </div>
            
            @if($gameWinnerTeamId)
                @php $winningTeam = $teams->firstWhere('id', $gameWinnerTeamId); @endphp
                <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                    <p class="text-sm text-yellow-800 dark:text-yellow-200">
                        ⚡ Equipo <strong style="color: {{ $winningTeam->color ?? '#000' }}">{{ $winningTeam->name ?? '' }}</strong> 
                        recibirá <strong>50 puntos adicionales</strong> por ganar los juegos.
                    </p>
                </div>
            @endif
        </div>

        <!-- REGISTRATION BY TEAM -->
        @foreach($teams as $team)
        <div class="mb-8 p-4 rounded-lg shadow" style="background-color: {{ $team->light_color }}20">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold" style="color: {{ $team->color }}">
                    {{ $team->name }}
                    @if($gameWinnerTeamId == $team->id)
                    <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                        🏆 GANADOR +50 pts
                    </span>
                    @endif
                </h2>
                <div class="text-lg font-bold" style="color: {{ $team->color }}">
                    {{ $this->getTeamPoints($team->id) }} pts
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($team->youths as $youth)
                @php 
                    $key = 'youth_' . $youth->id;
                    $individualPoints = $this->getPointsPerYouth($youth->id);
                @endphp
                
                <div class="bg-white dark:bg-gray-800 p-4 rounded shadow">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white">
                                {{ $youth->name }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $youth->age }} años
                            </p>
                        </div>
                        <div class="text-sm font-bold text-gray-700 dark:text-gray-300">
                            {{ $individualPoints }} pts
                        </div>
                    </div>

                    <!-- POINTS TOGGLES -->
                    <div class="space-y-3">
                        @foreach([
                            ['key' => 'attendance', 'label' => 'Asistencia', 'points' => 10],
                            ['key' => 'punctuality', 'label' => 'Puntualidad', 'points' => 10],
                            ['key' => 'bible', 'label' => 'Biblia', 'points' => 5],
                            ['key' => 'guest', 'label' => 'Invitado', 'points' => 20],
                        ] as $item)
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ $item['label'] }}
                                </label>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $item['points'] }} pts
                                </div>
                            </div>
                            
                            <!-- En Filament v4, los toggles se usan así -->
                            <div class="relative inline-block w-12 align-middle select-none">
                                <input 
                                    type="checkbox"
                                    wire:model.live="data.{{ $key }}.{{ $item['key'] }}"
                                    class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"
                                    style="border-color: {{ $team->color }};"
                                />
                                <label 
                                    class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"
                                    style="background-color: {{ $team->light_color }};"
                                ></label>
                            </div>
                        </div>
                        @endforeach
                        
                        <!-- Observations -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Observaciones
                            </label>
                            <input 
                                type="text"
                                wire:model="data.{{ $key }}.observations"
                                placeholder="Observaciones..."
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            />
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <!-- FINAL SUMMARY -->
        <div class="mt-8 p-6 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 rounded-lg">
            <h3 class="text-lg font-bold mb-4">📊 Resumen de la Semana</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-{{ count($teams) }} gap-6">
                @foreach($teams as $team)
                <div class="p-4 border rounded-lg" style="border-color: {{ $team->color }}30; background-color: {{ $team->light_color }}10">
                    <h4 class="font-bold mb-2" style="color: {{ $team->color }}">
                        {{ $team->name }}
                    </h4>
                    <div class="text-3xl font-bold mb-2" style="color: {{ $team->color }}">
                        {{ $this->getTeamPoints($team->id) }} pts
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $team->youths->count() }} jóvenes activos
                    </div>
                    @if($gameWinnerTeamId == $team->id)
                    <div class="mt-2 text-sm font-medium text-yellow-600 dark:text-yellow-400">
                        +50 pts por juegos
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            
            <!-- Ganador de la semana -->
            @php
                $teamPoints = [];
                foreach($teams as $team) {
                    $teamPoints[$team->id] = $this->getTeamPoints($team->id);
                }
                arsort($teamPoints);
                $winningTeamId = key($teamPoints);
                $maxPoints = current($teamPoints);
                $secondPoints = next($teamPoints);
            @endphp
            
            @if(count($teamPoints) > 1 && $maxPoints > 0)
                <div class="mt-6 p-4 rounded-lg 
                    @if($maxPoints == $secondPoints) bg-gray-100 dark:bg-gray-800 
                    @else bg-green-50 dark:bg-green-900/20 @endif">
                    <div class="flex items-center">
                        <div class="text-2xl mr-3">
                            @if($maxPoints == $secondPoints) ⚖️
                            @else 🏆 @endif
                        </div>
                        <div>
                            <div class="font-bold 
                                @if($maxPoints == $secondPoints) text-gray-700 dark:text-gray-300
                                @else text-green-700 dark:text-green-300 @endif">
                                @if($maxPoints == $secondPoints)
                                    ¡Empate! Ambos equipos tienen {{ $maxPoints }} puntos
                                @else
                                    @php $winningTeam = $teams->firstWhere('id', $winningTeamId); @endphp
                                    ¡Equipo <span style="color: {{ $winningTeam->color }}">{{ $winningTeam->name }}</span> lidera!
                                @endif
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                Diferencia: {{ $maxPoints - $secondPoints }} puntos
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- SAVE BUTTON -->
        <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
            <div>
                <a 
                    href="{{ route('filament.admin.resources.competition-weeks.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700"
                >
                    Cancelar
                </a>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="text-lg font-bold">
                    @foreach($teams as $team)
                    <span class="mr-4" style="color: {{ $team->color }}">
                        {{ $team->name }}: {{ $this->getTeamPoints($team->id) }}
                    </span>
                    @endforeach
                </div>
                
                <button 
                    type="submit"
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Finalizar y Guardar
                </button>
            </div>
        </div>
    </form>
</x-filament-panels::page>
<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}
        
        <!-- HEADER -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Register Points - {{ $record->name }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Week {{ $record->week_number }} • {{ $record->date->format('m/d/Y') }}
            </p>
        </div>

        <!-- GAME WINNER SELECTOR -->
        <div class="mb-8 p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">🏆 Game Winner (+50 pts)</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                @foreach($teams as $team)
                <div class="cursor-pointer" wire:click="$set('gameWinnerTeamId', {{ $team->id }})">
                    <div class="p-4 border-2 rounded-lg text-center transition-all
                        {{ $gameWinnerTeamId == $team->id 
                            ? 'border-' . $team->color . ' bg-' . $team->light_color
                            : 'border-gray-200 dark:border-gray-700 hover:border-gray-300' }}">
                        <div class="text-lg font-bold" style="color: {{ $team->color }}">
                            {{ $team->name }}
                        </div>
                        <div class="text-sm">+50 pts to team</div>
                    </div>
                </div>
                @endforeach
                
                <!-- No winner option -->
                <div class="cursor-pointer" wire:click="$set('gameWinnerTeamId', null)">
                    <div class="p-4 border-2 rounded-lg text-center transition-all
                        {{ !$gameWinnerTeamId 
                            ? 'border-gray-500 bg-gray-100 dark:bg-gray-800'
                            : 'border-gray-200 dark:border-gray-700 hover:border-gray-300' }}">
                        <div class="text-lg font-bold">No winner</div>
                        <div class="text-sm">Tie or not applicable</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- REGISTRATION BY TEAM -->
        @foreach($teams as $team)
        <div class="mb-8 p-4 rounded-lg shadow" style="background-color: {{ $team->light_color }}20">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold" style="color: {{ $team->color }}">
                    {{ $team->name }}
                    @if($gameWinnerTeamId == $team->id)
                    <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        🏆 WINNER +50 pts
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
                        <h3 class="font-bold text-gray-900 dark:text-white">
                            {{ $youth->name }}
                        </h3>
                        <div class="text-sm font-bold text-gray-700 dark:text-gray-300">
                            {{ $individualPoints }} pts
                        </div>
                    </div>

                    <!-- POINTS TOGGLES -->
                    <div class="space-y-2">
                        @foreach([
                            ['key' => 'attendance', 'label' => 'Attendance', 'points' => 10],
                            ['key' => 'punctuality', 'label' => 'Punctuality', 'points' => 10],
                            ['key' => 'bible', 'label' => 'Bible', 'points' => 5],
                            ['key' => 'guest', 'label' => 'Guest', 'points' => 20],
                        ] as $item)
                        <div class="flex justify-between items-center">
                            <label class="text-sm font-medium">
                                {{ $item['label'] }}
                                <span class="text-xs text-gray-500">({{ $item['points'] }} pts)</span>
                            </label>
                            <x-filament::toggle
                                wire:model.live="data.{{ $key }}.{{ $item['key'] }}"
                                size="sm"
                            />
                        </div>
                        @endforeach
                        
                        <!-- Observations -->
                        <div class="mt-3">
                            <x-filament::input
                                wire:model="data.{{ $key }}.observations"
                                placeholder="Observations..."
                                size="sm"
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
            <h3 class="text-lg font-bold mb-4">📊 Week Summary</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-{{ count($teams) }} gap-6">
                @foreach($teams as $team)
                <div class="p-4 border rounded-lg" style="border-color: {{ $team->color }}30">
                    <h4 class="font-bold mb-2" style="color: {{ $team->color }}">
                        {{ $team->name }}
                    </h4>
                    <div class="text-3xl font-bold" style="color: {{ $team->color }}">
                        {{ $this->getTeamPoints($team->id) }} pts
                    </div>
                    <div class="text-sm text-gray-500 mt-1">
                        {{ $team->youths->count() }} active youths
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- SAVE BUTTON -->
        <div class="mt-8 flex justify-end">
            <x-filament::button type="submit" icon="heroicon-o-check-circle" size="lg">
                Complete & Save Week
            </x-filament::button>
        </div>
    </x-filament-panels::form>
</x-filament-panels::page>
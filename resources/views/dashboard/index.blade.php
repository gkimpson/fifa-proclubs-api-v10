<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Start Results -->
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div>
                        <div class="flex flex-row">
                            <div class="basis-1/3 text-center">HOME</div>
                            <div class="basis-1/3 text-center"></div>
                            <div class="basis-1/3 text-center">AWAY</div>
                        </div>
                        @foreach ($results as $result)
                        <div class="flex flex-row py-2 mt-2">
                            <div class="basis-1/3 text-center">{{ $result->properties['clubs'][0]['name'] ?? 'Team Disbanded' }}</div>
                            <div class="basis-1/3 text-center">{{ $result->home_team_goals }} - {{ $result->away_team_goals }}</div>
                            <div class="basis-1/3 text-center">{{ $result->properties['clubs'][1]['name'] ?? 'Team Disbanded' }}</div>
                        </div>
                            @isset($result->properties['aggregate'])
                                <div x-data="{ open: false }" class="text-center">
                                    <div class="flex flex-row">
                                        <div class="basis-1/3 items-center">
                                            <img src="{{ $result->teamEmblem['home'] }}" width="100px" class="mx-auto" alt="">
                                        </div>
                                        <div class="basis-1/3 items-center">
                                            <button x-on:click="open = ! open">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="basis-1/3 items-center">
                                            <img src="{{ $result->teamEmblem['away'] }}" width="100px" class="mx-auto" alt="">
                                        </div>
                                    </div>
                                    <div x-show="open">
                                        <div class="grid grid-cols-3 mx-auto border-b py-2">
                                            <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->home_team_id]['shots'] }}</div>
                                            <div class="text-center text-xs md:text-sm">Shots on Target</div>
                                            <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->away_team_id]['shots'] }}</div>
                                        </div>
                                        <div class="grid grid-cols-3 mx-auto border-b py-2">
                                            <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->home_team_id]['saves'] }}</div>
                                            <div class="text-center text-xs md:text-sm">Saves (Human GK)</div>
                                            <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->away_team_id]['saves'] }}</div>
                                        </div>
                                        <div class="grid grid-cols-3 mx-auto border-b py-2">
                                            <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->home_team_id]['redcards'] }}</div>
                                            <div class="text-center text-xs md:text-sm">Red Cards</div>
                                            <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->away_team_id]['redcards'] }}</div>
                                        </div>
                                        <div class="grid grid-cols-3 mx-auto border-b py-2">
                                            <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->home_team_id]['tacklesmade'] }}</div>
                                            <div class="text-center text-xs md:text-sm">Tackles Made</div>
                                            <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->away_team_id]['tacklesmade'] }}</div>
                                        </div>
                                        <div class="grid grid-cols-3 mx-auto border-b py-2">
                                            <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->home_team_id]['tackleattempts'] }}</div>
                                            <div class="text-center text-xs md:text-sm">Tackles Attempts</div>
                                            <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->away_team_id]['tackleattempts'] }}</div>
                                        </div>
                                        <div class="grid grid-cols-3 mx-auto border-b py-2">
                                            <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->home_team_id]['assists'] }}</div>
                                            <div class="text-center text-xs md:text-sm">Assists</div>
                                            <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->away_team_id]['assists'] }}</div>
                                        </div>
                                        <div class="grid grid-cols-3 mx-auto border-b py-2">
                                            <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->home_team_id]['passesmade'] }}</div>
                                            <div class="text-center text-xs md:text-sm">Passes Made</div>
                                            <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->away_team_id]['passesmade'] }}</div>
                                        </div>
                                        <div class="grid grid-cols-3 mx-auto border-b py-2">
                                            <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->home_team_id]['passattempts'] }}</div>
                                            <div class="text-center text-xs md:text-sm">Pass Attempts</div>
                                            <div class="text-center text-xs md:text-sm">{{ $result->properties['aggregate'][$result->away_team_id]['passattempts'] }}</div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div>No stats recorded for this match</div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="py-2">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                {{ $results->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Results -->

</x-app-layout>

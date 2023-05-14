<x-guest-layout>
    <form method="GET" action="{{ route('search.submit') }}">
        @csrf

        <div>
            <x-input-label for="clubName" :value="__('Club Name')" />
            <x-text-input id="clubName" class="block mt-1 w-full" type="text" name="clubName" :value="app('request')->input('clubName')" required autofocus autocomplete="clubName" />
            <x-input-error :messages="$errors->get('clubName')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="platform" :value="__('Platform')" />
            <select class="form-control m-bot15" name="platform">
                @if (count($platforms) > 0)
                    @foreach ($platforms as $key => $platform)
                        <option value="{{ $key }}" @selected(app('request')->input('platform') == $key)>
                            {{ $platform }}
                        </option>
                    @endForeach
                @else
                    No Platforms Found
                @endif
            </select>
            <x-input-error :messages="$errors->get('platform')" class="mt-2" />
        </div>

        <!-- Club ID -->
        <div class="mt-4">
            <x-input-label for="club_id" :value="__('Club ID')" />
            <x-text-input id="club_id" class="block mt-1 w-full" type="text" name="club_id" :value="old('club_id')" maxlength="6" max="999999" required disabled="true" />
            <x-input-error :messages="$errors->get('club_id')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4 mb-4">
            <x-primary-button class="ml-4">
                {{ __('Search') }}
            </x-primary-button>
        </div>


        <!-- Search results -->
        @if (isset($clubs))
            @foreach ($clubs as $club)
                <div class="bg-gray-100 py-1 flex flex-col justify-center sm:py-1">
                    <div class="relative py-2 sm:max-w-xl sm:mx-auto">
                        <!-- widget -->
                        <div
                            class="rounded-2xl flex bg-white p-6 flex-col space-y-5"
                            id="widget"
                        >
                            <div class="flex justify-between w-64">
                                @php
                                    $clubLogo = 'https://fifa21.content.easports.com/fifa/fltOnlineAssets/05772199-716f-417d-9fe0-988fa9899c4d/2021/fifaweb/crests/256x256/l' . $club->clubInfo->teamId . '.png';
                                @endphp
                                <img
                                    src="{{ $clubLogo }}"
                                    class="rounded ring-4 ring-white h-20 w-20 shadow-2xl"
                                />
                                <button
                                    class="hover:border-gray-200 border border-white flex-grow-0 rounded-full h-12 w-12 flex items-center justify-center transform duration-500"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="#000000"
                                        fill="none"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <path
                                            stroke="none"
                                            d="M0 0h24v24H0z"
                                            fill="none"
                                        ></path>
                                        <circle cx="5" cy="12" r="1"></circle>
                                        <circle cx="12" cy="12" r="1"></circle>
                                        <circle cx="19" cy="12" r="1"></circle>
                                    </svg>
                                </button>
                            </div>
                            <div>
                                <p class="text-lg font-semibold">{{ $club->name }}</p>
                                @if (isset($club->currentDivision))
                                    <p class="text-gray-400 text-xs">Division {{ $club->currentDivision }}</p>
                                @endif

                            </div>
                            <div class="flex space-x-10 text-gray-400">
                                @if (isset($club->wins))
                                <div>
                                    <p class="text-green-500 font-bold">{{ $club->wins }}</p>
                                    <p class="text-xs">Wins</p>
                                </div>
                                @endif
                                @if (isset($club->losses))
                                <div>
                                    <p class="text-red-400 font-bold">{{ $club->losses }}</p>
                                    <p class="text-xs">Losses</p>
                                </div>
                                @endif
                                @if (isset($club->overallRankingPoints))
                                <div>
                                    <p class="text-blue-400 font-bold">{{ $club->overallRankingPoints }}</p>
                                    <p class="text-xs">Ranking Points</p>
                                </div>
                                @endif
                            </div>
                            <div class="flex space-x-5 items-center">
                                <button
                                    class="rounded-lg bg-red-400 text-red-50 text-sm p-2 px-6 transform hover:scale-105 duration-300"
                                >
                                    <a href="/club/{{ $club->clubInfo->clubId }}/platform/{{ app('request')->input('platform') }}">Follow</a>
                                </button>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="transform hover:scale-110 cursor-pointer duration-300"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="#000000"
                                    fill="none"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path
                                        stroke="none"
                                        d="M0 0h24v24H0z"
                                        fill="none"
                                    ></path>
                                    <path
                                        d="M3 20l1.3 -3.9a9 8 0 1 1 3.4 2.9l-4.7 1"
                                    ></path>
                                    <line x1="12" y1="12" x2="12" y2="12.01"></line>
                                    <line x1="8" y1="12" x2="8" y2="12.01"></line>
                                    <line x1="16" y1="12" x2="16" y2="12.01"></line>
                                </svg>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="transform hover:scale-110 cursor-pointer duration-300"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="#000000"
                                    fill="none"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path
                                        stroke="none"
                                        d="M0 0h24v24H0z"
                                        fill="none"
                                    ></path>
                                    <path
                                        d="M19.5 13.572l-7.5 7.428l-7.5 -7.428m0 0a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572"
                                    ></path>
                                </svg>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="transform hover:scale-110 cursor-pointer duration-300"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="#000000"
                                    fill="none"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path
                                        stroke="none"
                                        d="M0 0h24v24H0z"
                                        fill="none"
                                    ></path>
                                    <path
                                        d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"
                                    ></path>
                                    <path d="M9 17v1a3 3 0 0 0 6 0v-1"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </form>
</x-guest-layout>

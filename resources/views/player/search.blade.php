<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Player Search') }}
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div>

                        <form method="GET" action="/player/search">
                            @csrf
                            <div class="flex flex-wrap -mx-2 mb-4">
                                @foreach ($attributes as $attribute)
                                    @if ($attribute == 'unsure_attribute')
                                        @continue
                                    @endif
                                    <div class="w-full sm:w-1/2 md:w-1/3 px-2 mb-4">
                                        <div class="flex items-center">
                                            <button type="button" class="mr-2" data-target="{{ $attribute }}"></button>
                                            <label for="{{ $attribute }}" class="block font-medium text-gray-700">
                                            {{ ucfirst(str_replace('_', ' ', $attribute)) }}
                                            </label>
                                        </div>
                                        <select name="{{ $attribute }}" id="{{ $attribute }}" class="text-black leading-6 border-gray-300 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5">
                                            <option value="">Any</option>
                                            @for ($i = 99; $i >= 65; $i--)
                                                <option value="{{ $i }}" {{ request($attribute) == $i ? 'selected' : '' }} class="text-gray-900">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                @endforeach
                            </div>

                            <div class="flex justify-between items-center">
                                <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Search</button>
                                <button type="reset" class="px-4 py-2 text-white bg-gray-500 rounded hover:bg-gray-600 focus:outline-none focus:bg-gray-600">Reset</button>
                            </div>
                        </form>

                        <!-- Player Search Results -->
                        <div class="overflow-x-auto">
                            <table class="w-full whitespace-no-wrap">
                                <thead>
                                <tr class="bg-gray-100 text-xs">
                                    <th>Player</th>
                                    @foreach ($attributes as $attribute)
                                        @if ($attribute == 'unsure_attribute')
                                            @continue
                                        @endif
                                        <th class="px-6 py-3 text-left font-medium text-gray-700 uppercase tracking-wider">
                                            {{ ucfirst(str_replace('_', ' ', $attribute)) }}
                                        </th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($players as $index => $playerAttribute)
                                    @if ($attribute == 'unsure_attribute')
                                        @continue
                                    @endif
                                    <tr class="{{ $index % 2 == 0 ? 'bg-gray-50' : '' }}">
                                        <td>{{ $playerAttribute->player->player_name }}</td>
                                        @foreach ($attributes as $attribute)
                                            <td class="text-center">{{ $playerAttribute->$attribute }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>

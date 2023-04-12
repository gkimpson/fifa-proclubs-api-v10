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

                            <div>
                                @foreach ($attributes as $attribute)
                                    <label for="{{ $attribute }}" class="block font-medium text-gray-700">{{ $attribute }}</label>
                                    <select name="{{ $attribute }}" id="{{ $attribute }}" class="text-black leading-6 border-gray-300 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5">
                                        @for ($i = 99; $i >= 65; $i--)
                                            <option value="{{ $i }}" {{ request($attribute, 65) == $i ? 'selected' : '' }} class="text-gray-900">{{ $i }}</option>
                                        @endfor
                                    </select>
                                @endforeach
                            </div>

                            <button type="submit">Submit</button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>

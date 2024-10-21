<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('careat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                  {{-- create a website store form  --}}
                    <form action="{{ route('websites.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                        <label for="domain" class="block text-gray-700 text-sm font-bold mb-2">Domain</label>
                        <input type="text" name="domain" id="domain" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                        <label for="visitor_count" class="block text-gray-700 text-sm font-bold mb-2">Visitor Count</label>
                        <input type="number" name="visitor_count" id="visitor_count" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        {{-- add category field --}}
                        <div class="mb-4">
                        <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Category</label>
                        <input type="text" name="category" id="category" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Website</button>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('message'))
                <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                    <p class="font-bold">Informational message</p>
                    <p class="text-sm">{{ session('message') }}</p>
                </div>
            @endif
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            {{ __('Website endpoint') }}
                        </div>
                        
                        <a href="{{ route('edit-website') }}">
                            <x-button class="ml-3">
                                {{ __('Edit') }}
                            </x-button>
                        </a>
                    </div>
                </div>

                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            {{ __('General settings') }}
                        </div>

                        <a href="{{ route('edit-settings') }}">
                            <x-button class="ml-3">
                                {{ __('Edit') }}
                            </x-button>
                        </a>  
                    </div>
                </div>

                <div class="pl-3 pr-3 pt-6 pb-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('run-example') }}">
                            <x-button class="ml-3">
                                {{ __('Run example') }}
                            </x-button>
                        </a>

                        @if(session('example'))
                            <div>
                                <span class="font-semibold mr-2 text-left flex-auto">{{ Str::limit(session('example'), 120, $end = '...') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

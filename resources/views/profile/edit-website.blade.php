<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit website') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('edit-website') }}">
                        @csrf

                        <div class="flex items-center justify-between">
                            <x-input id="website" class="w-full block" type="text" name="website" value="{{ $website ?? '' }}" placeholder="{{ __('Specify the new website...') }}" required autofocus />
                            
                            <x-button class="ml-3 py-3">
                                {{ __('Save') }}
                            </x-button>
                        </div>
                    </form>

                    <div class="pt-5">
                        {{ __('Registration URL:') }} https://base_url/api/{{ $token ?? 'not_set'}}/subscribe
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
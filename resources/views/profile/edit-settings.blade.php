<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('edit-settings') }}">
                        @csrf

                        <div class="mt-2">
                            <x-label for="scheduler" :value="__('Scheduler')" />
                            <x-input id="scheduler" class="block mt-1 w-full" type="number" name="scheduler" placeholder="{{ __('Specify the number of days') }}" value="{{ $settings->scheduler ?? '' }}" required autofocus />
                        </div>

                        <div class="mt-2">
                            <x-label for="posts_number" :value="__('Posts number')" />
                            <x-input id="posts_number" class="block mt-1 w-full" type="number" name="posts_number" placeholder="{{ __('Specify the number of posts_number') }}" value="{{ $settings->posts_number ?? '' }}" required autofocus />
                        </div>

                        <div class="mt-2">
                            <x-label for="title_tag" :value="__('Title tag')" />
                            <x-input id="title_tag" class="block mt-1 w-full" type="text" name="title_tag" placeholder="{{ __('Specify your title_tag') }}" value="{{ $settings->title_tag ?? '' }}" required autofocus />
                        </div>

                        <div class="mt-2">
                            <x-label for="email_object" :value="__('Email object')" />
                            <x-input id="email_object" class="block mt-1 w-full" type="text" name="email_object" placeholder="{{ __('Specify your email_object') }}" value="{{ $settings->email_object ?? '' }}" required autofocus />
                        </div>

                        <div class="mt-2">
                            <x-label for="headline" :value="__('Headline')" />                        
                            <x-textarea id="headline" class="block mt-1 w-full" rows="5" name="headline" placeholder="{{ __('Specify your headline...') }}" required autofocus>{{ $settings->headline ?? '' }}</x-textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-button class="ml-3">
                                {{ __('Save') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
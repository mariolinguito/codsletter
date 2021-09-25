<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit website') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/code.css') }}"> 

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

    <pre class="html"><code>
        &lt;form&nbsp;action=&quot;https://base_url/api/<mark class="code-mark">8JFMg6tMz7IQJ</mark>/subscribe&quot;&nbsp;method=&quot;POST&quot;&gt;
            &nbsp;&nbsp;&lt;div&gt;
                &nbsp;&nbsp;&nbsp;&nbsp;&lt;label&nbsp;for=&quot;name&quot;&gt;Name&lt;/label&gt;
                &nbsp;&nbsp;&nbsp;&nbsp;&lt;input&nbsp;name=&quot;name&quot;&nbsp;id=&quot;name&quot;&nbsp;value=&quot;Mario&quot;&gt;
            &nbsp;&nbsp;&lt;/div&gt;
            &nbsp;&nbsp;&lt;div&gt;
                &nbsp;&nbsp;&nbsp;&nbsp;&lt;label&nbsp;for=&quot;surname&quot;&gt;Surname&lt;/label&gt;
                &nbsp;&nbsp;&nbsp;&nbsp;&lt;input&nbsp;name=&quot;surname&quot;&nbsp;id=&quot;surname&quot;&nbsp;value=&quot;Linguito&quot;&gt;
            &nbsp;&nbsp;&lt;/div&gt;
            &nbsp;&nbsp;&lt;div&gt;
                &nbsp;&nbsp;&nbsp;&nbsp;&lt;label&nbsp;for=&quot;email&quot;&gt;E-mail&lt;/label&gt;
                &nbsp;&nbsp;&nbsp;&nbsp;&lt;input&nbsp;name=&quot;email&quot;&nbsp;id=&quot;email&quot;&nbsp;value=&quot;m@m1.com&quot;&gt;
            &nbsp;&nbsp;&lt;/div&gt;
            
            &nbsp;&nbsp;&lt;input&nbsp;type=&quot;hidden&quot;&nbsp;name=&quot;redirect_to&quot;&nbsp;value=&quot;https://my-redirect-page.com&quot;&gt;
            &nbsp;&nbsp;&lt;button&gt;Subscribe&nbsp;me&lt;/button&gt;
        &lt;/form&gt;
    </code></pre>

</x-app-layout>
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Website;
use Illuminate\Support\Str;
use Auth;

class WebsiteController extends Controller
{

    private $view;
    
    public function store(Request $request)
    {
        $website = Website::updateOrCreate([
            'url' => $request->website,
            'token' => Str::random(13),
            'user_id' => Auth::id(),
        ]);

        return redirect()
            ->route('dashboard')
            ->with('message', 'Operation executed successfully!');
    }

    public function get() 
    {
        try {
            $website = Website::where(['user_id' => Auth::id()])->first();
            
            $this->view = view('profile/edit-website', 
                ['website' => $website->url, 'token' => $website->token]
            );
        } catch(\ErrorException $exception) {
            $this->view = view('profile/edit-website');
        }

        return $this->view;
    }

}

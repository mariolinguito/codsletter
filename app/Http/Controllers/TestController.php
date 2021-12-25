<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;
use Goutte;
use Auth;

class TestController extends Controller
{
    
    public function runScraptingTest() {

        $website = Website::where(
            ['user_id' => Auth::id()]
        )->first();
        
        $settings = $website->settings;
        
        try {
            $crawler = Goutte::request('GET', $website->url);
            $example = $crawler->filter($settings->title_tag)->first()->text();
        } catch(\InvalidArgumentException $exception) {
            $example = "Something gone wrong with website...";
        } catch(\ErrorException $exception) {
            $example = "Something gone wrong, please make sure that your website is set!";
        }

        return redirect()->route('dashboard')->with('example', $example);
    }

}

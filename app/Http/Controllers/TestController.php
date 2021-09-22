<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte;
use Auth;
use DB;

class TestController extends Controller
{
    
    public function runScraptingTest() {
        
        try {
            $joined_data = 
                DB::table('websites')
                    ->join('settings', 'websites.id', '=', 'settings.website_id')
                    ->where('websites.user_id', Auth::id())
                    ->first();

            $crawler = Goutte::request('GET', $joined_data->url);
            $example = $crawler->filter($joined_data->title_tag)->first()->text();
        } catch(\InvalidArgumentException $exception) {
            $example = "Something gone wrong with website...";
        } catch(\ErrorException $exception) {
            $example = "Something gone wrong, please make sure that your website is set!";
        }

        return redirect()->route('dashboard')->with('example', $example);
    }

}

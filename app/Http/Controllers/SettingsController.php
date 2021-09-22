<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\Setting;

class SettingsController extends Controller
{

    private $view;
    
    public function store(Request $request) {

        try {
            $website_id = DB::table('websites')->where('user_id', Auth::id())->value('id');
            $settings = Setting::updateOrCreate(['website_id' => $website_id], [
                'scheduler' => $request->scheduler,
                'posts_number' => $request->posts_number,
                'title_tag' => $request->title_tag,
                'email_object' => $request->email_object,
                'headline' => $request->headline,
                'website_id' => $website_id,
            ]);

            $message = 'Operation executed successfully!';
        } catch(\Illuminate\Database\QueryException $exception) { 
            $message = 'Maybe you have not set the website, try to make it.';
        } catch(\ErrorException $exception) {
            $message = 'Ops, an error occurs, try again...';
        }

        return redirect()->route('dashboard')->with('message', $message);
    }

    public function get() {
        try {
            $website = DB::table('websites')->where('user_id', Auth::id())->first();
            $settings = DB::table('settings')->where('website_id', $website->id)->first();

            $this->view = view('profile/edit-settings', ['settings' => $settings]);
        } catch(\ErrorException $exception) {
            $this->view = view('profile/edit-settings');
        }

        return $this->view;
    }

}

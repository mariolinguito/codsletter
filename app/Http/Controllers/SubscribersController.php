<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscriber;
use App\Models\Website;
use DB;
use Auth;
use Redirect;

class SubscribersController extends Controller
{

    public function confirmUnsubscribe(Request $request, string $email, string $token) {

        if (!$request->hasValidSignature()) {
            abort(401);
        }

        $website = Website::where('token', $token)->first();
        $subscriber = Subscriber::where('website_id', $website->id)->where('email', $email);

        $subscriber->delete();

        return view('sorry-to-see-you-go');
    }

    public function subscribe(Request $request) {
        
        // get website id from token

        try {
            $website = DB::table('websites')->where('websites.token', $request->token)->first();
            $website_id = $website->id;

            $subscriber = Subscriber::updateOrCreate(['email' => $request->email, 'website_id' => $website_id], [
                'name' => $request->name,
                'surname' => $request->surname,
                'email' => $request->email,
                'website_id' => $website_id,
            ]);

            return Redirect::to($request->redirect_to);

        } catch(\ErrorException $exception) {
            # error handler
        }
    }
    
    public function list() {

        try {
            $website = DB::table('websites')->where('user_id', Auth::id())->first();

            $returned_view_list = view('subscribers.subscribers', [
                'subscribers' => DB::table('subscribers')->where('website_id', $website->id)->paginate(15)
            ]);
        } catch(\ErrorException $exception) {
            $returned_view_list = view('subscribers.subscribers')->with('message', 'You should set a website first!');        
        }

        return $returned_view_list;
    }

    public function delete($subscriber_id) {

        $subscriber = Subscriber::where('id', $subscriber_id)->first();
        $subscriber->delete();

        return redirect()->route('subscribers')->with('message', 'Operation executed successfully!');
    }

}

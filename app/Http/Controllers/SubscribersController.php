<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscriber;
use App\Models\Website;
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

        try {
            $website_id = Website::where(['websites.token' => $request->token])->first()->id;

            $subscriber = Subscriber::updateOrCreate(['email' => $request->email, 'website_id' => $website_id], [
                'name' => $request->name,
                'surname' => $request->surname,
                'email' => $request->email,
                'website_id' => $website_id,
            ]);

            if(isset($request->redirect_to) && !empty($request->redirect_to)) {
                return Redirect::to($request->redirect_to);
            } else {
                return response()->json([
                    'status' => 'Wou! You are in.',
                ]);
            }

        } catch(\ErrorException $exception) {
            # error handler
        }
    }
    
    public function list() {

        try {
            $website = Website::where(['user_id' => Auth::id()])->first();

            $returned_view_list = view('subscribers.subscribers', [
                'subscribers' => $website->subscribers()->paginate(15)
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

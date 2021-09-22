<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GoutteController;
use App\Models\Subscriber;
use App\Models\User;
use App\Notifications\NewsletterNotification;
use Notification;
use DB;

class SendMailController extends Controller
{
    
    private function sendNewsletter($user) {

        $goutte_controller = new GoutteController($user->id);

        if($goutte_controller->matchDaysNumber()) {

            $sliced_nodes = $goutte_controller->checkPostsNumber();

            if(!empty($sliced_nodes)) {
                
                $email_object = $sliced_nodes['email_object'];
                $headline = $sliced_nodes['headline'];

                unset($sliced_nodes['email_object']);
                unset($sliced_nodes['headline']);

                $details = [
                    'body' => $sliced_nodes,
                    'email_object' => $email_object,
                    'headline' => $headline,
                ];
        
                $website_id = DB::table('websites')->where('user_id', $user->id)->first()->id;

                foreach(Subscriber::where('website_id', $website_id)->get() as $subscriber) {
                    Notification::send($subscriber, new NewsletterNotification($details));
                }
                
            }
        }
    }
    
    public function startMailing() {
        foreach(User::all() as $user) {
            $this->sendNewsletter($user);
            echo "Start mailing...";
        }
    }

}

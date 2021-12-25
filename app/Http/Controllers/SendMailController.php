<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GoutteController;
use App\Models\User;
use App\Models\Website;
use App\Notifications\NewsletterNotification;
use Notification;

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
        
                $website = Website::where('user_id', $user->id)->first();
                $subscribers = $website->subscribers;

                Notification::send($subscribers, new NewsletterNotification($details));
            }
        }
    }
    
    public function startMailing() {
        foreach(User::all() as $user) {
            $this->sendNewsletter($user);
        }
    }

}

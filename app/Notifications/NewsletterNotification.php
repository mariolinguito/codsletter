<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class NewsletterNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $notification_details;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($notification_details)
    {
        $this->notification_details = $notification_details;
        $this->delay(now()->addMinute(1));
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = new MailMessage;

        $message
            ->subject($this->notification_details['email_object'])
            ->greeting($this->notification_details['email_object'])
            ->line($this->notification_details['headline'])
            ->line(new HtmlString('<h1>Last updates:</h1>'));

        $this
            ->getTitleLines($this->notification_details['body'], $message);

        return $message->markdown('mail.newsletter');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    /**
     * 
     */
    private function getTitleLines($titles, &$message) {
        foreach ($titles as $title) {
            $message->line(new HtmlString('<a href="'.$title['url'].'">'.$title['title'].'</a>'));
        }
    }
}

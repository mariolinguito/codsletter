<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use YlsIdeas\SubscribableNotifications\Contracts\CanUnsubscribe;
use URL;
use DB;

class Subscriber extends Model implements CanUnsubscribe
{
    use HasFactory, Notifiable;

    public function unsubscribeLink(?string $mailingList = ''): string
    {

        $website = DB::table('websites')->where('id', $this->website_id)->first();
        $website_token = $website->token;

        return URL::signedRoute(
            'subscriber.confirm-unsubscribe',
            ['email' => $this->email, 'token' => $website_token],
            now()->addDays(1)
        );
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'website_id',
    ];
}

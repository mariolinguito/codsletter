<?php

namespace App\Providers;

use YlsIdeas\SubscribableNotifications\SubscribableApplicationServiceProvider;
use YlsIdeas\SubscribableNotifications\Facades\Subscriber;

class SubscribableServiceProvider extends SubscribableApplicationServiceProvider
{

    /**
     * @var bool
     */
    protected $loadRoutes = true;

    public function boot() {
        Subscriber::userModel('App\Models\Subscriber');
    }

    /**
     * @return callable|string
     */
    public function onUnsubscribeFromMailingList()
    {
        return function ($user, $mailingList) {
        };
    }

    /**
     * @return callable|string
     */
    public function onUnsubscribeFromAllMailingLists()
    {
        return function ($user) {
        };
    }

    /**
     * @return callable|string
     */
    public function onCompletion()
    {
        return function ($user, $mailingList) {
            return response()
                ->redirectTo('/');
        };
    }

    /**
     * @return callable|string
     */
    public function onCheckSubscriptionStatusOfMailingList()
    {
        return function ($user, $mailingList) {
            return true;
        };
    }

    /**
     * @return callable|string
     */
    public function onCheckSubscriptionStatusOfAllMailingLists()
    {
        return function ($user) {
            return true;
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'url',
        'token',
        'user_id',
    ];

    /**
     * Get the settings associated with the website.
     */
    public function settings()
    {
        return $this->hasOne(Setting::class);
    }

    /**
     * Get the flash data associated with the website.
     */
    public function flash_data()
    {
        return $this->hasOne(WebsiteFlashData::class);
    }

    /**
     * Get the subscribers associated with the website.
     */
    public function subscribers()
    {
        return $this->hasMany(Subscriber::class);
    }
}

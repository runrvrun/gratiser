<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Schedula\Laravel\PassportSocialite\User\UserSocialAccount;

class User extends \TCG\Voyager\Models\User implements UserSocialAccount 
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'fullname', 'gender', 'phone', 'city_id','avatar','otp'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function likes(){
        return $this->belongsToMany('App\StoreProductStock', 'user_store_product_stocks_likes', 'user_id', 'store_product_stock_id');
    }

    public function notification_allows(){
        return $this->hasMany('App\NotificationAllow');
    }

    public static function findForPassportSocialite($provider,$id) {
        $account = SocialAccount::where('provider', $provider)->where('provider_user_id', $id)->first();
        if($account) {
            if($account->user){
                return $account->user;
            }
        }
        return;
    }
}

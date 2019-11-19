<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\User;
use App\Notification;
use App\Product;
use \Carbon\Carbon;
use DB; 
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class PushnotificationController extends Controller
{
    public function index()
    {
        return view('/vendor/voyager/pushnotification');
    }
    
    public function notify(Request $request)
    {
        $image = Product::where('id',$request->product_id)->pluck('image');
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder('Gratiser');
        $notificationBuilder->setTitle($request->title)
                            ->setBody($request->body)
                            ->setSound('default')
                            ->setBadge('https://gratiser.oriadesoft.id/storage/'.$image[0])
                            ->setIcon('https://gratiser.oriadesoft.id/storage/'.$image[0]);
                            // setClickAction

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['product_id' => $request->product_id]);
        $dataBuilder->addData(['store_id' => $request->store_id]);
        $dataBuilder->addData(['notification_name' => $request->notification_name]);
        $dataBuilder->addData(['image' => 'https://gratiser.oriadesoft.id/storage/products/'.$image[0]]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();
        
        // You must change it to get your tokens
        $tokens = Notification::join('notification_allows','notification_allows.user_id','notifications.user_id')->where('notification_name',$request->notification_name)
        // ->where('notifications.user_id',28)
        ->where('allow',1)->pluck('token')->toArray();

        if(count($tokens) == 0){// no one to send notif to
            return view('/vendor/voyager/pushnotification')->with(['message' => "No one to send notification to", 'alert-type' => 'danger']);
        }

        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);

        $downstreamResponse->numberSuccess();
        $ret=$downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();

        // return Array - you must remove all this tokens in your database
        $downstreamResponse->tokensToDelete();

        // return Array (key : oldToken, value : new token - you must change the token in your database)
        $downstreamResponse->tokensToModify();

        // return Array - you should try to resend the message to the tokens in the array
        $downstreamResponse->tokensToRetry();

        // return Array (key:token, value:error) - in production you should remove from your database the tokens present in this array
        $downstreamResponse->tokensWithError();

        return redirect('/vendor/voyager/pushnotification')->with(['message' => "Notification Processing to Send", 'alert-type' => 'success']);
        // return view('/vendor/voyager/pushnotification');
    }
}

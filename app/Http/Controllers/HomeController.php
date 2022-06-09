<?php

namespace App\Http\Controllers;

use App\Classes\Telegram;
use App\Models\SyncUserTelegramChannels;
use App\Models\TelegramChannels;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(auth()->user()->isAdmin()){
            $telegramChannels = TelegramChannels::get();
            return view('admin-channels',compact(['telegramChannels']));
        }
        $responseData = [];

        $headChannels  = TelegramChannels::get();
        $channelsArray = auth()->user()->userChannels->pluck('title', 'id')->toArray();
        $channelsUser = auth()->user()->userChannels;

        foreach($channelsArray as $id => $title){
            $syncChannels = auth()->user()->syncTelegramChannels->where('user_telegram_channel_id',$id)->pluck('telegram_channel_id')->toArray();
            $responseData[$id] = $syncChannels;
        }


        return view('home',compact(['channelsUser','headChannels','responseData']));
    }

    public function updateUser()
    {
        $user = auth()->user();
        $user->telegram_token = request()->telegram_token;
        $user->save();

        $telegram = new Telegram($user->telegram_token);
        $telegram->setMethod('setWebhook');
        $telegram->setParams(['url'=>env('APP_URL').'/user/'.$user->id]);

        $telegram->query();

        return redirect(route('user.home'));
    }

    public function updateSyncChannels(){

        $data = request()->get('data',null);

        SyncUserTelegramChannels::whereUserId(auth()->user()->id)->delete();

        if($data){
            foreach($data as $item){
                if(isset($item['from_channels'])) {
                    foreach ($item['from_channels'] as $telegramId) {
                        SyncUserTelegramChannels::create([
                            'user_id' => auth()->user()->id,
                            'telegram_channel_id' => $telegramId,
                            'user_telegram_channel_id' => $item['to_channels']
                        ]);
                    }
                }
            }
        }
        return redirect(route('user.home'));

    }
}

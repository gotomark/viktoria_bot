<?php

namespace App\Http\Controllers;

use App\Classes\Telegram;
use App\Models\SyncUserTelegramChannels;
use App\Models\SyncUserVkontakteAlbumChannels;
use App\Models\TelegramChannels;
use App\Models\UserVkontakteAlbum;
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
    public function telegramSync()
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function vkontakteSync()
    {
        if(auth()->user()->isAdmin()){
            $telegramChannels = TelegramChannels::get();
            return view('admin-channels',compact(['telegramChannels']));
        }
        $responseData = [];

        $headChannels  = TelegramChannels::get();
        $channelsArray = auth()->user()->userAlbums->pluck('title', 'id')->toArray();

        $channelsUser = auth()->user()->userAlbums;

        foreach($channelsArray as $id => $title){
            $syncChannels = auth()->user()->syncAlbums->where('user_vkontakte_album_id',$id)->pluck('telegram_channel_id')->toArray();

            $responseData[$id] = $syncChannels;
        }

;

        return view('vkontakte-sync',compact(['channelsUser','headChannels','responseData']));
    }

    public function updateUser()
    {

        $user = auth()->user();
        if(request()->get('telegram_token',null) != null){
            $user->telegram_token = request()->telegram_token;
            $telegram = new Telegram($user->telegram_token);
            $telegram->setMethod('setWebhook');
            $telegram->setParams(['url'=>env('APP_URL').'/user/'.$user->id]);

            $telegram->query();
        }

        if(request()->get('vk_token',null) != null){
            $user->vk_token = request()->vk_token;
        }

        $user->save();



        return back();
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

        return redirect(route('user.telegram-sync'));

    }

    public function updateSyncVkontakte(){

        $data = request()->get('data',null);

        SyncUserVkontakteAlbumChannels::whereUserId(auth()->user()->id)->delete();

        if($data){
            foreach($data as $item){
                if(isset($item['from_channels'])) {
                    foreach ($item['from_channels'] as $telegramId) {
                        SyncUserVkontakteAlbumChannels::create([
                            'user_id' => auth()->user()->id,
                            'telegram_channel_id' => $telegramId,
                            'user_vkontakte_album_id' => $item['to_channels']
                        ]);
                    }
                }
            }
        }
        return redirect(route('user.vkontakte-sync'));

    }

    public function storeAlbum(){
        UserVkontakteAlbum::create([
            'user_id' => auth()->user()->id,
            'album_id' => request()->album_id,
            'title' => request()->title,
        ]);
       return back();
    }

    public function deleteAlbum(UserVkontakteAlbum $userVkontakteAlbum){
        SyncUserVkontakteAlbumChannels::where('user_vkontakte_album_id',$userVkontakteAlbum->id)->delete();
        $userVkontakteAlbum->delete();
        return back();
    }
}

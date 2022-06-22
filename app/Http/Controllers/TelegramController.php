<?php

namespace App\Http\Controllers;

use App\Jobs\Photo;
use App\Jobs\PhotoGroup;
use App\Jobs\PhotoVK;
use App\Jobs\Video;
use App\Models\SyncUserTelegramChannels;
use App\Models\TmpPhotoGroup;
use App\Models\User;
use Illuminate\Http\Request;
use App\Classes\Telegram;
use App\Classes\Vkontakte;
Use App\Models\WebHookHistory;
use App\Models\WebHookUserHistory;
use App\Models\UserTelegramChannels;
use App\Models\TelegramChannels;
use Log;
use Carbon\Carbon;
class TelegramController extends Controller
{
   public function vkPhotos(){

      // Vkontakte()

   }
   public function index(){
       $user = User::find(1);
       $mediaGroup = TmpPhotoGroup::find(51);
       $channel = TelegramChannels::where('channel_id','-1001612955556')->first();
       $name = '7107.jpg';

       $caption = '';
       if(isset($req['channel_post']['caption'])){
           $caption = $req['channel_post']['caption'];
       }
       foreach($channel->userSyncChannels as $channelItam) {

           foreach ($channelItam->userChannel as $userChannel) {

               $item = TmpPhotoGroup::whereUserId($userChannel->user->id)
                   ->whereMediaGroupId($name)
                   ->whereChannelId($userChannel->channel_id)
                   ->get();

               if($item->count() == 0){
                   $tmpGroup = TmpPhotoGroup::create([
                       'user_id'=>$userChannel->user->id,
                       'media_group_id'=>$name,
                       'channel_id'=>$userChannel->channel_id,
                       'caption'=>$caption
                   ]);

                   dispatch(new Photo($tmpGroup));
               }
           }
       }

       foreach($channel->userSyncAlbums as $channelItam) {

           foreach ($channelItam->userAlbums as $userChannel) {

               $item = TmpPhotoGroup::whereUserId($userChannel->user->id)
                   ->whereMediaGroupId($name)
                   ->whereChannelId($userChannel->album_id)
                   ->get();

               if($item->count() == 0){
                   $tmpGroup = TmpPhotoGroup::create([
                       'user_id'=>$userChannel->user->id,
                       'media_group_id'=>$name,
                       'channel_id'=>$userChannel->album_id,
                       'caption'=>''
                   ]);

                   dispatch(new PhotoVK($tmpGroup));
               }
           }
       }


   }

   public function hook(){
       $req = request()->all();
       //Log::info('----MAIN BOT----');
       //Log::info($req);
       WebHookHistory::create(
       [
           'json_data'=>json_encode(request()->all())
       ]);

      if(isset($req['channel_post']['text'])){
          /*
          Log::info('TYPE TEXT: '.$req['channel_post']['text'].' - CHAT ID: '.$req['channel_post']['chat']['id']);

          $channel = TelegramChannels::where('channel_id',$req['channel_post']['chat']['id'])->first();

          foreach($channel->userSyncChannels as $channel){

              foreach($channel->userChannel as $userChannel){
                  Log::info('REPLY TEXT: '.$req['channel_post']['text'].' - CHAT ID: '.$userChannel->channel_id);
                  $telegram = new Telegram($userChannel->user->telegram_token);
                  $telegram->setMethod('sendMessage');
                  $telegram->setParams(['chat_id'=>$userChannel->channel_id,'text'=>$req['channel_post']['text']]);
                  $telegram->sendMessage();
              }
          }
*/
           return response(200);
        }

       if(isset($req['channel_post']['video'])){

           $telegram = new Telegram(config('services.telegram-bot-api.token'));
           $telegram->setMethod('getFile');
           $telegram->setParams(['file_id'=>$req['channel_post']['video']['file_id']]);

           $filePath = json_decode($telegram->getPathPhoto());

           $telegram = new Telegram(config('services.telegram-bot-api.token'));
           if(isset($filePath->result->file_path)){
                $videoUrl = $telegram->getUrlPhoto($filePath->result->file_path);
           }else{
               //Log::info($filePath);
               return response(200);
           }


           $name = rand(0,100000).'.'.pathinfo($filePath->result->file_path, PATHINFO_EXTENSION);
           $localPath = storage_path().'/app/public/'.$name;
           file_put_contents($localPath, file_get_contents($videoUrl));

           $channel = TelegramChannels::where('channel_id',$req['channel_post']['chat']['id'])->first();
           $caption = '';
           if(isset($req['channel_post']['caption'])){
               $caption = $req['channel_post']['caption'];
           }
           foreach($channel->userSyncChannels as $channel) {

               foreach ($channel->userChannel as $userChannel) {

                   $item = TmpPhotoGroup::whereUserId($userChannel->user->id)
                       ->whereMediaGroupId($name)
                       ->whereChannelId($userChannel->channel_id)
                       ->get();

                   if($item->count() == 0){
                       $tmpGroup = TmpPhotoGroup::create([
                           'user_id'=>$userChannel->user->id,
                           'media_group_id'=>$name,
                           'channel_id'=>$userChannel->channel_id,
                           'caption'=>$caption
                       ]);

                       dispatch(new Video($tmpGroup));
                   }
               }
           }




           return response(200);
       }

       if(isset($req['channel_post']['photo']) && !isset($req['channel_post']['media_group_id'])){

           $telegram = new Telegram(config('services.telegram-bot-api.token'));
           $telegram->setMethod('getFile');
           $telegram->setParams(['file_id'=>isset($req['channel_post']['photo'][3]['file_id']) ? $req['channel_post']['photo'][3]['file_id']:$req['channel_post']['photo'][2]['file_id']]);

           $filePath = json_decode($telegram->getPathPhoto());

           $telegram = new Telegram(config('services.telegram-bot-api.token'));

           $photoUrl = $telegram->getUrlPhoto($filePath->result->file_path);
           $name = rand(0,100000).'.'.pathinfo($filePath->result->file_path, PATHINFO_EXTENSION);
           $localPath = storage_path().'/app/public/'.$name;
           file_put_contents($localPath, file_get_contents($photoUrl));

           $channel = TelegramChannels::where('channel_id',$req['channel_post']['chat']['id'])->first();

           $caption = '';
           if(isset($req['channel_post']['caption'])){
               $caption = $req['channel_post']['caption'];
           }
           foreach($channel->userSyncChannels as $channelItem) {

               foreach ($channelItem->userChannel as $userChannel) {

                   $item = TmpPhotoGroup::whereUserId($userChannel->user->id)
                       ->whereMediaGroupId($name)
                       ->whereChannelId($userChannel->channel_id)
                       ->get();

                   if($item->count() == 0){
                       $tmpGroup = TmpPhotoGroup::create([
                           'user_id'=>$userChannel->user->id,
                           'media_group_id'=>$name,
                           'channel_id'=>$userChannel->channel_id,
                           'caption'=>$caption
                       ]);

                       dispatch(new Photo($tmpGroup));
                   }
               }
           }

           foreach($channel->userSyncAlbums as $channelItem) {

               foreach ($channelItem->userAlbums as $userChannel) {

                   $item = TmpPhotoGroup::whereUserId($userChannel->user->id)
                       ->whereMediaGroupId($name)
                       ->whereChannelId($userChannel->album_id)
                       ->get();

                   if($item->count() == 0){
                       $tmpGroup = TmpPhotoGroup::create([
                           'user_id'=>$userChannel->user->id,
                           'media_group_id'=>$name,
                           'channel_id'=>$userChannel->album_id,
                           'caption'=>''
                       ]);

                       dispatch(new PhotoVK($tmpGroup));
                   }
               }
           }




           return response(200);
       }

       if(isset($req['channel_post']['photo']) && isset($req['channel_post']['media_group_id'])){

           $telegram = new Telegram(config('services.telegram-bot-api.token'));
           $telegram->setMethod('getFile');
           $telegram->setParams(['file_id'=>isset($req['channel_post']['photo'][3]['file_id']) ? $req['channel_post']['photo'][3]['file_id']:$req['channel_post']['photo'][2]['file_id']]);
           $filePath = json_decode($telegram->getPathPhoto());

           $telegram = new Telegram(config('services.telegram-bot-api.token'));

           $photoUrl = $telegram->getUrlPhoto($filePath->result->file_path);
           $name = rand(0,100000).'.'.pathinfo($filePath->result->file_path, PATHINFO_EXTENSION);
           $dir = storage_path() . '/app/public/' . $req['channel_post']['media_group_id'];

           if(!is_dir($dir)) {
               mkdir($dir);
           }

           $localPath = $dir.'/'.$name;

           file_put_contents($localPath, file_get_contents($photoUrl));

           $channel = TelegramChannels::where('channel_id',$req['channel_post']['chat']['id'])->first();

           foreach($channel->userSyncChannels as $channelItem) {

               foreach ($channelItem->userChannel as $userChannel) {

                   $item = TmpPhotoGroup::whereUserId($userChannel->user->id)
                       ->whereMediaGroupId($req['channel_post']['media_group_id'])
                       ->whereChannelId($userChannel->channel_id)
                       ->get();

                   if($item->count() == 0){
                       $tmpGroup = TmpPhotoGroup::create([
                           'user_id'=>$userChannel->user->id,
                           'media_group_id'=>$req['channel_post']['media_group_id'],
                           'channel_id'=>$userChannel->channel_id,
                       ]);

                       dispatch(new PhotoGroup($tmpGroup))->delay(Carbon::now()->addSeconds(10));
                   }
               }
           }



           foreach($channel->userSyncAlbums as $channelItem) {

               foreach ($channelItem->userAlbums as $userChannel) {

                   $item = TmpPhotoGroup::whereUserId($userChannel->user->id)
                       ->whereMediaGroupId($req['channel_post']['media_group_id'])
                       ->whereChannelId($userChannel->album_id)
                       ->whereFilePath($name)
                       ->get();

                   if($item->count() == 0){
                       $tmpGroup = TmpPhotoGroup::create([
                           'user_id'=>$userChannel->user->id,
                           'media_group_id'=>$req['channel_post']['media_group_id'],
                           'file_path'=>$name,
                           'channel_id'=>$userChannel->album_id,
                           'caption'=>''
                       ]);

                       dispatch(new PhotoVK($tmpGroup))->delay(Carbon::now()->addSeconds(10));
                   }
               }
           }

           return response(200);
       }


       if(isset($req['my_chat_member']['new_chat_member']['status'])){
           if($req['my_chat_member']['new_chat_member']['status'] == 'administrator'){
               TelegramChannels::create(
                   [
                       'channel_id'=>$req['my_chat_member']['chat']['id'],
                       'title'=>$req['my_chat_member']['chat']['title'],
                   ]
               );
           }else{
               $channel = TelegramChannels::where('channel_id',$req['my_chat_member']['chat']['id'])->first();
               if($channel) {
                   SyncUserTelegramChannels::where('telegram_channel_id', $channel->id)->delete();
                   $channel->delete();
               }
           }
           return response(200);
       }

   }

   public function hookUser(User $user){
        $req = request()->all();
        WebHookUserHistory::create(
            [
                'json_data'=>json_encode(request()->all()),
                'user_id'=>$user->id
            ]);
       if(isset($req['my_chat_member']['new_chat_member']['status'])) {
           if ($req['my_chat_member']['new_chat_member']['status'] == 'administrator') {
               //Log::info("STATUS ISSET^ ".$req['my_chat_member']['new_chat_member']['status']);
               UserTelegramChannels::create(
                   [
                       'user_id' => $user->id,
                       'channel_id' => $req['my_chat_member']['chat']['id'],
                       'title' => $req['my_chat_member']['chat']['title'],
                   ]
               );
           } else {
               $userChannel = UserTelegramChannels::where('channel_id',$req['my_chat_member']['chat']['id'])->first();
               if($userChannel) {
                   SyncUserTelegramChannels::where('user_telegram_channel_id', $userChannel->id)->delete();
               $userChannel->delete();
               }
           }
       }
       return response(200);
   }

}

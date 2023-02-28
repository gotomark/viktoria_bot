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



       $vk = new Vkontakte(['access_token' => 'vk1.a.-A81xkAo7bPpm6vnMQaNkk8c8fTKlfk8vBr8SYHcyiQFHNV6-8YjKj4CqOEcUT1Jl2Bp1Aa8RC1tmCRH-69rcFf4qyYV58dP4ZP_96KkEnyy41s8koUB6O-WSTJU1evBaiIwgP6itL0GNjGJbUpVvml_ch4l4FXaeayAsBn7XknvVg7hBORry_xRIygRi6Ib']);
       $albumParse = explode('_','187183257_283614336');

       $vk->postToPublicAlbum($albumParse[0], $albumParse[1], storage_path() . '/app/public/54738.jpg');




        //$srt='{"server":857512,"photos_list":"[{\"markers_restarted\":true,\"photo\":\"c98a28868b:w\",\"sizes\":[],\"latitude\":0,\"longitude\":0,\"kid\":\"cea5d4d0561375ad6ee48faa66dab668\",\"sizes2\":[[\"s\",\"074f32bf2c0bb67a9f24a47c1e6014d1be2b0dbba05fd9ad998f9bbe\",\"2058566074981196356\",35,75],[\"m\",\"4b4dcb905a0030acd98d88ee311de52dc4ba8e3ea09c7bd00d3885db\",\"213505911923876043\",60,130],[\"x\",\"8dfcc546134cab5bd586c2ede616c7af87bc614b5c5dc91cbe49ff1e\",\"-2414057877472488208\",279,604],[\"y\",\"3fa2283073ff7c44a3fc68c9cbb9dbfcef2c0da20461a77147b03dcc\",\"5054199995153250066\",373,807],[\"z\",\"afcbc3ab8f412c13abfeeb4065d8fb148f33e984279d99c1d31c4499\",\"-4662236945848550187\",499,1080],[\"w\",\"f2a8d67740b576350fd2223a5c94153998d0f4323ef722f3a3e32f11\",\"-5855993852341323085\",591,1280],[\"o\",\"21fe98027fc2180ee9cfb3778fb8e9ef7ec9641c66a126288b0cb5fc\",\"8723749148850120394\",130,281],[\"p\",\"77e34d5433043967e789de7879dfc97245051ef44d0800db6579dedb\",\"1918832663129794851\",200,433],[\"q\",\"c985f271b738fe2fa19ae9a033ff8bcc9adc5aa5d4e8d9d3152067fa\",\"-4208388072127171446\",320,693],[\"r\",\"3c38ee7521e7744a12d4d4871dcbd735ea1961474e286f540eb267c7\",\"-7892542530741436785\",510,900]],\"urls\":[],\"urls2\":[\"B08yvywLtnqfJKR8HmAU0b4rDbugX9mtmY-bvg/RKLsPfB-kRw.jpg\",\"S03LkFoAMKzZjYjuMR3lLcS6jj6gnHvQDTiF2w/yyCr5X6G9gI.jpg\",\"jfzFRhNMq1vVhsLt5hbHr4e8YUtcXckcvkn_Hg/8NQEkiaLf94.jpg\",\"P6IoMHP_fESj_GjJy7nb_O8sDaIEYadxR7A9zA/EvfjBh4gJEY.jpg\",\"r8vDq49BLBOr_utAZdj7FI8z6YQnnZnB0xxEmQ/1bz-BzNoTL8.jpg\",\"8qjWd0C1djUP0iI6XJQVOZjQ9DI-9yLzo-MvEQ/s_pSIJ5Uu64.jpg\",\"If6YAn_CGA7pz7N3j7jp737JZBxmoSYoiwy1_A/yubTZqH7EHk.jpg\",\"d-NNVDMEOWfnid54ed_JckUFHvRNCADbZXne2w/I6lEBSQQoRo.jpg\",\"yYXycbc4_i-hmumgM_-LzJrcWqXU6NnTFSBn-g/inTea1jNmMU.jpg\",\"PDjudSHndEoS1NSHHcvXNeoZYUdOKG9UDrJnxw/j35-gGIOeJI.jpg\"]}]","aid":279926553,"hash":"3f1a5d4f0b151f4a44af98047f3666a9"}';
     // var_dump(json_decode($srt,true,2));

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
                           'caption'=>$userChannel->user->flag_caption == 1 ? $caption : ''
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

                                  //dispatch(new PhotoVK($tmpGroup));
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
               mkdir($dir, 0755, true);
           }

           $localPath = $dir.'/'.$name;

           file_put_contents($localPath, file_get_contents($photoUrl));

           $channel = TelegramChannels::where('channel_id',$req['channel_post']['chat']['id'])->first();

           foreach($channel->userSyncChannels as $channelItem) {

               foreach ($channelItem->userChannel as $userChannel) {
                   $caption = '';
                   if(isset($req['channel_post']['caption'])){
                       $caption = $req['channel_post']['caption'];
                   }
                   $item = TmpPhotoGroup::whereUserId($userChannel->user->id)
                       ->whereMediaGroupId($req['channel_post']['media_group_id'])
                       ->whereChannelId($userChannel->channel_id)
                       ->get();

                   if($item->count() == 0){
                       $tmpGroup = TmpPhotoGroup::create([
                           'user_id'=>$userChannel->user->id,
                           'media_group_id'=>$req['channel_post']['media_group_id'],
                           'channel_id'=>$userChannel->channel_id,
                           'caption'=>$userChannel->user->flag_caption == 1 ? $caption : '',
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

                      // dispatch(new PhotoVK($tmpGroup))->delay(Carbon::now()->addSeconds(10));
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
               WebHookUserHistory::create(
                   [
                       'json_data'=>json_encode(request()->all()),
                       'user_id'=>$user->id
                   ]);
           } else {
               $userChannel = UserTelegramChannels::where('channel_id',$req['my_chat_member']['chat']['id'])->first();
               if($userChannel) {
                   SyncUserTelegramChannels::where('user_telegram_channel_id', $userChannel->id)->delete();
               $userChannel->delete();
               }
               WebHookUserHistory::create(
                   [
                       'json_data'=>json_encode(request()->all()),
                       'user_id'=>$user->id
                   ]);
           }
       }
       return response(200);
   }

}

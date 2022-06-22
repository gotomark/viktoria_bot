<?php

namespace App\Jobs;

use App\Classes\Telegram;
use App\Models\TmpPhotoGroup;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Photo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $mediaGroup;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mediaGroup)
    {
        $this->mediaGroup = $mediaGroup;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::find($this->mediaGroup->user_id);

        $telegram = new Telegram($user->telegram_token);
        $telegram->setMethod('sendPhoto');
        $telegram->setCaption($this->mediaGroup->caption);
        $telegram->setChatId($this->mediaGroup->channel_id);
        $telegram->setParams(['chat_id'=>$this->mediaGroup->channel_id]);
        $telegram->setPhoto(env('APP_URL').'/storage/'.$this->mediaGroup->media_group_id);
        $telegram->sendImage();


        if(TmpPhotoGroup::where('media_group_id',$this->mediaGroup->media_group_id)->count() <= 1 ){
            //unlink(storage_path() . '/app/public/' .$this->mediaGroup->media_group_id);
        }

        $this->mediaGroup->delete();
    }


}

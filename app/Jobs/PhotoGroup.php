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
use Storage;

class PhotoGroup implements ShouldQueue
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
        $files = scandir(storage_path().'/app/public/'.$this->mediaGroup->media_group_id);

        $user = User::find($this->mediaGroup->user_id);

        $telegram = new Telegram($user->telegram_token);
        $telegram->setMethod('sendMediaGroup');
        $telegram->setCaption($this->mediaGroup->caption);
        $telegram->setChatId($this->mediaGroup->channel_id);
        $telegram->setParams(['chat_id'=> $this->mediaGroup->channel_id]);

        foreach($files as $image){
            if($image != '.' && $image != '..') {
                $dataSendFiles[] = [
                    'type'=>"photo",
                    'media'=> env('APP_URL').'/storage/'.$this->mediaGroup->media_group_id.'/'.$image,
                ];
            }
        }
        $telegram->setPhotoGroup($dataSendFiles);

        $telegram->sendImageGroup();

        $deleteMediaGroupId = $this->mediaGroup->media_group_id;


        if(TmpPhotoGroup::where('media_group_id',$deleteMediaGroupId)->count() <= 1 ){
            //$this->removeFolder(storage_path() . '/app/public/' .$deleteMediaGroupId);
        }

        $this->mediaGroup->delete();

    }

    public function retryUntil()
    {
        return now()->addSeconds(60);
    }

    function removeFolder($folderName) {
        if (is_dir($folderName))
            $folderHandle = opendir($folderName);

        if (!$folderHandle)
            return false;

        while($file = readdir($folderHandle)) {
            if ($file != "." && $file != "..") {
                if (!is_dir($folderName."/".$file))
                    unlink($folderName."/".$file);
                else
                    removeFolder($folderName.'/'.$file);
            }
        }

        closedir($folderHandle);
        rmdir($folderName);
        return true;
    }
}

<?php

namespace App\Jobs;

use App\Classes\Vkontakte;
use App\Models\TmpPhotoGroup;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
//use Log;
class PhotoVK implements ShouldQueue
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

        $vk = new Vkontakte(['access_token' => $user->vk_token]);
        $albumParse = explode('_',$this->mediaGroup->channel_id);

        if($this->mediaGroup->media_group_id != '' && $this->mediaGroup->file_path == '') {
            $vk->postToPublicAlbum($albumParse[0], $albumParse[1], storage_path() . '/app/public/' . $this->mediaGroup->media_group_id);
            //Log::info('POSTED ONE PHOTO');
        }else{
            $vk->postToPublicAlbum($albumParse[0], $albumParse[1], storage_path() . '/app/public/' . $this->mediaGroup->media_group_id.'/'.$this->mediaGroup->file_path);
            //Log::info('POSTED ONE PHOTO FROM GALLERY');
        }

        $deleteMediaGroupId = $this->mediaGroup->media_group_id;


        if($this->mediaGroup->media_group_id != '' && $this->mediaGroup->file_path == '') {
            if (TmpPhotoGroup::where('media_group_id', $deleteMediaGroupId)->count() <= 1) {
                //unlink(storage_path() . '/app/public/' . $deleteMediaGroupId);
            }
        }else{
            if (TmpPhotoGroup::where('media_group_id', $deleteMediaGroupId)->count() <= 1 ) {
                //$this->removeFolder(storage_path() . '/app/public/' . $deleteMediaGroupId);
            }
        }

        $this->mediaGroup->delete();

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

<?php

namespace App\Console\Commands;

use App\Models\TmpPhotoGroup;
use Illuminate\Console\Command;

class ClearAllData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resetData:tmp_and_files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tmp = TmpPhotoGroup::all();

        foreach ($tmp as $item) {
            if($item->file_path != null || $item->file_path != '') {
               // $this->line('Folder: '. storage_path() . '/app/public/' . $item->media_group_id);
                $this->removeFolder(storage_path() . '/app/public/' . $item->media_group_id);
            }else{
                //$this->line('File: '.storage_path() . '/app/public/' . $item->media_group_id);
                if(is_file(storage_path() . '/app/public/' .$item->media_group_id)){
                    unlink(storage_path() . '/app/public/' .$item->media_group_id);
                }
            }
        }
        TmpPhotoGroup::truncate();

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

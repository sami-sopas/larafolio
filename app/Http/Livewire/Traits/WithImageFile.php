<?php

namespace App\Http\Livewire\Traits;

use Illuminate\Support\Facades\Storage;

trait WithImageFile
{
    private function deleteFile($disk, $filename)
    {
        //Verifica que el archivo exista en el disco dado
        if($filename && Storage::disk($disk)->exists($filename)){
            Storage::disk($disk)->delete($filename);
        }
    }

}

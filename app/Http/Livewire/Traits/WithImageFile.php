<?php

namespace App\Http\Livewire\Traits;

use RuntimeException;
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

    private function verifyTemporaryUrl()
    {
        /*
        El try-catch es para evitar que el sistema lance un error si se
        sube un archivo que no sea una imagen (un pdf por ejemplo)
        */
        try{

            $this->imageFile->temporaryUrl();

        } catch(RuntimeException $e)
        {
            //Si ocurre el error, reiniciamos la propiedad
            $this->reset('imageFile');
        }
    }

}

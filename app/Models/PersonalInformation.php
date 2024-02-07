<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonalInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'cv',
        'image',
        'email',
    ];

    //Accessors

    //Esta funcion busca el archivo en el disco, en la carpeta cv y devuelve la url,
    // en caso que no exista, devolvemos un archivo por defecto
    protected function cvUrl() : Attribute
    {
        return Attribute::make(
            get: fn() => Storage::disk('cv')->url($this->cv ?? 'my-cv.pdf')
        );
    }

    //Esta funcion busca el archivo en el disco, en la carpeta hero y devuelve la url,
    // La carpeta esta en storage->img->hero pero en filesystems declaramos su public path para no escribir toda la liga
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => Storage::disk('hero')->url($this->image ?? 'default-hero.jpg')
        );
    }
}

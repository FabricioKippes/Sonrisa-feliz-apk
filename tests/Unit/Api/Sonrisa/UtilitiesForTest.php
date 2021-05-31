<?php

namespace Tests\Unit\Api\Sonrisa;

use Illuminate\Support\Facades\Storage;
use stdClass;

class UtilitiesForTest
{
    public static function getFileTest(){
        $imagen = new stdClass;
        $imagen->filename = "test.png";
        $imagen->image = base64_encode(Storage::disk('public')->get('/test/test.png'));

        return $imagen;
    }
}

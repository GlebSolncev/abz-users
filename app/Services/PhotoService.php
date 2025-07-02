<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tinify\Source;
use Tinify\Tinify;

class PhotoService
{
    /**
     * @var array
     */
    protected array $modify;

    public function __construct()
    {
        $config = config('tinify');
        $this->modify = $config['modify'];

        Tinify::setKey($config['api_key']);
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function processAndStore(UploadedFile $file): string
    {
        $resized = Source::fromFile($file->getRealPath())->resize($this->modify);
        $ext = $file->getClientOriginalExtension();
        $filename = 'users/' . uniqid('user_') . '.' . $ext;
        Storage::disk('public')->put($filename, $resized->toBuffer());

        return $filename;
    }
}
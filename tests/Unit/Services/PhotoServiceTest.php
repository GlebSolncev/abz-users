<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Services\PhotoService;
use Mockery;
use Tinify\Source;

class PhotoServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $tempDir = sys_get_temp_dir();
        config()->set('filesystems.disks.public.root', $tempDir);
        Storage::clearResolvedInstances();

        config()->set('tinify.modify', [
            'method' => 'cover',
            'width'  => 70,
            'height' => 70,
        ]);
        config()->set('tinify.api_key', 'fake-api-key');

        $sourceMock = Mockery::mock('alias:' . Source::class);
        $sourceMock
            ->shouldReceive('fromFile')
            ->andReturnSelf();
        $sourceMock
            ->shouldReceive('resize')
            ->with(config('tinify.modify'))
            ->andReturnSelf();
        $sourceMock
            ->shouldReceive('toBuffer')
            ->andReturn('binary-image-data');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_process_and_store_saves_resized_image(): void
    {
        $file = UploadedFile::fake()
            ->image('avatar.jpg', 100, 100)
            ->size(5000);

        $service = new PhotoService();
        $path    = $service->processAndStore($file);

        $this->assertMatchesRegularExpression(
            '#^users/user_[0-9a-f]+\.jpg$#',
            $path
        );

        Storage::disk('public')->assertExists($path);

        $content = Storage::disk('public')->get($path);
        $this->assertSame('binary-image-data', $content);
    }
}

<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Mockery;

use App\Models\User;
use App\Services\UserService;
use App\Services\PhotoService;
use App\Exceptions\UserConflictException;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected PhotoService $photoServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        DB::table('positions')->insert([
            ['id' => 1, 'name' => 'Lawyer'],
            ['id' => 2, 'name' => 'Designer'],
        ]);

        Storage::fake('public');

        $this->photoServiceMock = Mockery::mock(PhotoService::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_createUser_throws_conflict_exception_if_email_or_phone_exists(): void
    {
        User::factory()->create([
            'email'       => 'test@example.com',
            'phone'       => '380123456789',
            'position_id' => 1,
            'photo'       => 'users/existing.jpg',
        ]);

        $this->photoServiceMock->shouldNotReceive('processAndStore');

        $service = new UserService($this->photoServiceMock);

        $data = [
            'name'        => 'John Doe',
            'email'       => 'test@example.com',
            'phone'       => '380123456789',
            'position_id' => 1,
            'photo'       => UploadedFile::fake()->image('photo.jpg'),
        ];

        $this->expectException(UserConflictException::class);

        $service->createUser($data);
    }

    public function test_createUser_creates_and_returns_user_on_success(): void
    {
        $this->photoServiceMock
            ->shouldReceive('processAndStore')
            ->once()
            ->andReturn('users/fake_photo.jpg');

        $service = new UserService($this->photoServiceMock);

        $data = [
            'name'        => 'Jane Smith',
            'email'       => 'jane@example.com',
            'phone'       => '380987654321',
            'position_id' => 2,
            'photo'       => UploadedFile::fake()->image('avatar.png'),
        ];

        $user = $service->createUser($data);

        $this->assertDatabaseHas('users', [
            'id'           => $user->id,
            'name'         => 'Jane Smith',
            'email'        => 'jane@example.com',
            'phone'        => '380987654321',
            'position_id'  => 2,
            'photo'        => 'users/fake_photo.jpg',
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('jane@example.com', $user->email);
        $this->assertEquals('380987654321', $user->phone);
        $this->assertEquals('users/fake_photo.jpg', $user->photo);
    }
}

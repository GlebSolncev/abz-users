<?php

namespace Database\Factories;

use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        $firstName = $this->faker->firstName();
        $name = sprintf('%s %s',
            $firstName,
            $this->faker->lastName()
        );

        $photoUrl = "https://api.dicebear.com/7.x/avataaars/jpg?seed={$firstName}&size=70";
        $filename = 'users/test-'.$name.'.jpg';

        return [
            'name'                   => $name,
            'email'                  => $this->faker->unique()->safeEmail(),
            'phone'                  => '+380' . $this->faker->numerify('#########'),
            'position_id'            => Position::inRandomOrder()->first()->id,
            'photo'                  => $this->uploadImage($photoUrl, $filename),
        ];
    }

    private function uploadImage(string $url, string $filename): string
    {
        $remoteStream = fopen($url, 'r');
        if ($remoteStream === false) {
            throw new \RuntimeException("Could not open remote file for reading");
        }

        $storageStream = Storage::disk('public')->writeStream($filename, $remoteStream);
        if ($storageStream === false) {
            throw new \RuntimeException("Failed to write file to storage");
        }

        return $filename;
    }
}

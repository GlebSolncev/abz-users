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
        $gender      = $this->faker->randomElement(['men', 'women']);
        $photoNumber = $this->faker->numberBetween(1, 99);
        $photoUrl    = "https://randomuser.me/api/portraits/{$gender}/{$photoNumber}.jpg";
        Storage::disk('public')->put('te', $photoUrl);

        return [
            'name'                   => $this->faker->name(),
            'email'                  => $this->faker->unique()->safeEmail(),
            'phone'                  => '+380' . $this->faker->numerify('#########'),
            'position_id'            => Position::inRandomOrder()->first()->id,
            'photo'                  => $this->uploadImage($photoUrl),
        ];
    }

    private function uploadImage(string $url): string
    {
        $remoteStream = fopen($url, 'r');
        $filename = 'users/test-'.time().'.jpg';
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

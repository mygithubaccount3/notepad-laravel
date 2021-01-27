<?php

namespace Database\Factories;

use App\Models\Note;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class NoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Note::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uid' => Uuid::uuid4(),
            'category' => $this->faker->randomElement(array('general', 'sport', 'work', 'relax')),
            'visibility' => $this->faker->randomElement(array('public', 'private')),
            'en' => [
                'title' => $this->faker->sentence(7, true),
                'text' => $this->faker->text(200),

            ], 'ru' => [
                'title' => $this->faker->sentence(7, true),
                'text' => $this->faker->text(200),
            ]
        ];
    }
}

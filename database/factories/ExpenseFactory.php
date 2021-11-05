<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Expense::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id' => Category::factory(),
            'date' => $this->faker->dateTimeThisYear()->format('Y-m-d'),
            'description' => $this->faker->words(3, true),
            'amount' => $this->faker->numberBetween(2000, 100000)
        ];
    }
}

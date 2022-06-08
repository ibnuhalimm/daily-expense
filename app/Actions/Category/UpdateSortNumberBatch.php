<?php

namespace App\Actions\Category;

use App\Models\Category;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateSortNumberBatch
{
    use AsAction;

    public function handle(Category $currentCategory, int $sortNumber)
    {
        $this->updateUpperCategories($sortNumber);

        $currentCategory->sort_number = $sortNumber;
        $currentCategory->save();

        $this->updateSortNumberofAllCategories();
    }


    private function updateUpperCategories(int $sortNumber)
    {
        $upperCategories = Category::query()
            ->where('sort_number', '>=', $sortNumber)
            ->orderBy('sort_number', 'asc')
            ->get();

        $upperSortNumber = $sortNumber;
        foreach ($upperCategories as $upperCategory) {
            $upperCategory->sort_number = ++$upperSortNumber;
            $upperCategory->save();
        }
    }


    private function updateSortNumberofAllCategories()
    {
        $categories = Category::query()
            ->orderBy('sort_number', 'asc')
            ->get();

        $sortNumber = 1;
        foreach ($categories as $currentCategory) {
            $currentCategory->sort_number = $sortNumber++;
            $currentCategory->save();
        }
    }
}

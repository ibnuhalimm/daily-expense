<?php

namespace App\Actions\Category;

use App\Models\Category;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateOtherSortNumber
{
    use AsAction;

    public function handle(Category $category, int $sortNumber)
    {
        $otherCategories = Category::query()
            ->where('id', '<>', $category->id)
            ->where('sort_number', '>=', $sortNumber)
            ->orderBy('sort_number', 'asc')
            ->get();

        foreach ($otherCategories as $otherCategory) {
            $otherCategory->sort_number = ++$sortNumber;
            $otherCategory->save();
        }
    }
}

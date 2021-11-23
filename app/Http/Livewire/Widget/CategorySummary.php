<?php

namespace App\Http\Livewire\Widget;

use App\Models\Category;
use Livewire\Component;

class CategorySummary extends Component
{
    public $month;
    public $year;


    public function mount()
    {
        $this->month = now()->month;
        $this->year = now()->year;
    }


    public function render()
    {
        $dateStart = date('Y-m-d', strtotime($this->year . '-' . $this->month . '-01'));
        $dateEnd = date('Y-m-t', strtotime($dateStart));

        $categories = Category::reportExpenseCategoryDateRange($dateStart, $dateEnd);
        $sumOfTotalAmount = $categories->sum('amount_sum');

        return view('livewire.widget.category-summary', compact('categories', 'sumOfTotalAmount'));
    }
}

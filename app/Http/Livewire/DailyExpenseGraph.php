<?php

namespace App\Http\Livewire;

use App\Models\Expense;
use Livewire\Component;

class DailyExpenseGraph extends Component
{
    /**
     * Define properties
     *
     * @var mixed
     */
    public $filter_month;
    public $filter_year;

    public function mount()
    {
        $this->filter_month = date('m');
        $this->filter_year = date('Y');
    }

    /**
     * Render to view
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $from_date = $this->filter_year . '-' . $this->filter_month . '-01';
        $to_date = $this->filter_year . '-' . $this->filter_month . '-31';

        $expenses = Expense::selectRaw('date, SUM(amount) AS total_amount')
                        ->whereBetween('date', [ $from_date, $to_date ])
                        ->groupBy('date')
                        ->orderBy('date', 'asc')
                        ->get();

        $expense_data = $expenses->map(function($expense) {
            return $expense->total_amount;
        });

        $expense_day = $expenses->map(function($expense) {
            return substr($expense->date, 8, 2);
        });

        $data = [
            'expense_data' => $expense_data,
            'expense_day' => $expense_day
        ];

        return view('livewire.daily-expense-graph', $data);
    }
}

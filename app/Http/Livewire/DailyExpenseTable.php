<?php

namespace App\Http\Livewire;

use App\Models\Expense;
use Livewire\Component;
use Livewire\WithPagination;

class DailyExpenseTable extends Component
{
    use WithPagination;

    /**
     * Define properties
     *
     * @var mixed
     */
    public $is_create_modal_show;
    public $is_delete_modal_show;

    public $filter_day;
    public $filter_month;
    public $filter_year;

    public $is_edit_mode;
    public $expense_id;
    public $day;
    public $month;
    public $year;
    public $description;
    public $amount;

    /**
     * Initialize properties data
     *
     * @return void
     */
    public function mount()
    {
        $this->is_new_modal_show = false;
        $this->is_delete_modal_show = false;

        $this->is_edit_mode = false;
        $this->filter_day = $this->day = date('d');
        $this->filter_month = $this->month = date('m');
        $this->filter_year = $this->year = date('Y');
    }

    /**
     * Form validation rules
     *
     * @return array
     */
    private function formValidationRules()
    {
        return [
            'day' => [ 'required' ],
            'month' => [ 'required' ],
            'year' => [ 'required' ],
            'description' => [ 'required', 'string', 'min:3', 'max:100' ],
            'amount' => [ 'required' ]
        ];
    }

    /**
     * Reset description, amount, expense_id property
     * when user cancel to store/update expense
     *
     * @return void
     */
    public function updatedIsCreateModalShow($value)
    {
        if ($value === false) {
            $this->reset('description', 'amount');

            if ($this->is_edit_mode === true) {
                $this->reset('expense_id');
            }
        }
    }

    /**
     * Show create expense modal
     *
     * @return void
     */
    public function createExpense()
    {
        $this->is_edit_mode = false;
        $this->is_create_modal_show = true;
    }

    /**
     * Store/update expense into/on database
     *
     * @return void
     */
    public function storeExpense()
    {
        $this->validate($this->formValidationRules());

        try {

            if ($this->is_edit_mode === true) {
                Expense::where('id', $this->expense_id)
                    ->update([
                        'date' => $this->year . '-' . $this->month . '-' . $this->day,
                        'description' => $this->description,
                        'amount' => $this->amount
                    ]);

            } else {
                Expense::create([
                    'date' => $this->year . '-' . $this->month . '-' . $this->day,
                    'description' => $this->description,
                    'amount' => $this->amount
                ]);

            }

            $this->filter_day = $this->day;
            $this->filter_month = $this->month;
            $this->filter_year = $this->year;

            $this->reset('description', 'amount');

            $this->is_create_modal_show = false;

        } catch (\Throwable $th) {
            report($th);

            session()->flash('create-alert-body', 'Oops, something went wrong');
        }
    }

    /**
     * Show edit expense modal
     *
     * @param int $expense_id
     * @return void
     */
    public function editExpense($expense_id)
    {
        $expense = Expense::findOrFail($expense_id);

        $this->is_edit_mode = true;
        $this->is_create_modal_show = true;

        $this->expense_id = $expense->id;
        $this->day = $expense->date->format('d');
        $this->month = $expense->date->format('m');
        $this->year = $expense->date->format('Y');
        $this->description = $expense->description;
        $this->amount = $expense->amount;
    }

    /**
     * Show delete modal confirm modal
     *
     * @param int $expense_id
     * @return void
     */
    public function deleteExpense($expense_id)
    {
        $this->is_delete_modal_show = true;
        $this->expense_id = $expense_id;
    }

    /**
     * Reset `expense_id` properties when user cancel to delete expense
     *
     * @return void
     */
    public function updatedIsDeleteModalShow($value)
    {
        if ($value === false) {
            $this->reset('expense_id');
        }
    }

    /**
     * Delete expense from database
     *
     * @return void
     */
    public function destroyExpense()
    {
        try {
            Expense::destroy($this->expense_id);
            $this->is_delete_modal_show = false;

        } catch (\Throwable $th) {
            report($th);
            session()->flash('delete-alert-body', 'Oops, something went wrong');

        }
    }

    /**
     * Render to view
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $filter_date = $this->filter_year . '-' . $this->filter_month . '-' . $this->filter_day;

        $data = [
            'expenses' => Expense::whereDate('date', $filter_date)->orderBy('created_at', 'desc')->paginate(20),
            'total_amount' => Expense::whereDate('date', $filter_date)->sum('amount')
        ];

        return view('livewire.daily-expense-table', $data);
    }
}

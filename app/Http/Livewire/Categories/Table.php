<?php

namespace App\Http\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    public $keyword;
    public $isEditMode;
    public $isCreateModalShow;
    public $isDeleteModalShow;

    public $categoryId;
    public $name;


    protected $rules = [
        'name' => [
            'required',
            'max:100'
        ]
    ];


    protected $validationAttributes = [
        'name' => __('Category Name')
    ];


    public function mount()
    {
        $this->keyword = null;
        $this->isEditMode = false;
        $this->isCreateModalShow = false;
        $this->isDeleteModalShow = false;
    }


    public function updatingKeyword()
    {
        $this->resetPage();
    }


    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }


    public function updatedIsCreateModalShow($value)
    {
        if ($value === false) {
            $this->reset('name');
            $this->resetErrorBag();

            if ($this->isEditMode === true) {
                $this->reset('categoryId');
            }
        }
    }


    public function updatedIsDeleteModalShow($value)
    {
        if ($value === false) {
            $this->reset('categoryId');
            $this->resetErrorBag();
        }
    }


    public function createCategory()
    {
        $this->isEditMode = false;
        $this->isCreateModalShow = true;
    }


    public function storeCategory()
    {
        $this->validate();

        try {

            if ($this->isEditMode === true) {
                Category::where('id', $this->categoryId)
                    ->update([
                        'name' => $this->name
                    ]);

            } else {
                Category::create([
                    'name' => $this->name
                ]);

            }

            $this->reset('categoryId', 'name');
            $this->isCreateModalShow = false;

        } catch (\Throwable $th) {
            report($th);

            session()->flash('create-alert-body', __('Oops, something went wrong'));
        }
    }


    public function editCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);

        $this->isCreateModalShow = true;
        $this->isEditMode = true;
        $this->categoryId = $category->id;
        $this->name = $category->name;
    }


    public function deleteCategory($categoryId)
    {
        $this->isDeleteModalShow = true;
        $this->categoryId = $categoryId;
    }


    public function destroyCategory()
    {
        try {
            Category::destroy($this->categoryId);
            $this->isDeleteModalShow = false;

        } catch (\Throwable $th) {
            report($th);
            session()->flash('delete-alert-body', __('Oops, something went wrong'));
        }
    }


    public function render()
    {
        $categories = Category::query()
                            ->search($this->keyword)
                            ->orderBy('name', 'asc')
                            ->paginate(10);

        return view('livewire.categories.table', compact('categories'));
    }
}

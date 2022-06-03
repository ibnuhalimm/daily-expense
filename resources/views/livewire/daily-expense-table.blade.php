<div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="bg-white border-b border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ __('Daily Expense Data') }}
                    </h3>
                    <div class="mt-6 flex flex-col items-center justify-between gap-4 sm:flex-row">
                        <div class="w-full sm:w-1/3 lg:w-1/2 xl:w-3/5">
                            <x-jet-button wire:click="createExpense">
                                {{ __('New Expense') }}
                            </x-jet-button>
                        </div>
                        <div class="w-full sm:w-2/3 lg:w-1/2 xl:w-2/5">
                            <div class="flex flex-row item-center justify-between gap-4">
                                <div class="w-1/2">
                                    <div wire:ignore>
                                        <x-form.select wire:model.defer="filter_category_id" id="filter_category_id">
                                            <option value="">- {{ __('All Categories') }} -</option>

                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </x-form.select>
                                    </div>
                                </div>
                                <div class="w-1/2" wire:ignore>
                                    <x-jet-input type="date" wire:model.defer="filter_date_range" id="filter_date_range"  autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-0">
                    <x-primary-table>
                        <thead>
                            <x-tr-head>
                                <x-th class="w-20 pl-6">#</x-th>
                                <x-th>
                                    {{ __('Amount') }}
                                </x-th>
                                <x-th>
                                    {{ __('Description') }}
                                </x-th>
                                <x-th>
                                    {{ __('Category') }}
                                </x-th>
                                <x-th class="w-32">&nbsp;</x-th>
                            </x-tr-head>
                        </thead>
                        <tbody>
                            @if ($expenses->isEmpty())
                                <x-tr-body class="hover:bg-gray-100">
                                    <td class="py-3 px-6" colspan="5">
                                        {{ __('No data available') }}
                                    </td>
                                </x-tr-body>
                            @else
                                @foreach ($expenses as $idx => $expense)
                                    <x-tr-body>
                                        <td class="py-2 px-1 pl-6">
                                            {{ $idx + $expenses->firstItem() }}
                                        </td>
                                        <td class="py-2 px-1">
                                            Rp {{ number_format($expense->amount, 0, ',', '.') }},-
                                        </td>
                                        <td class="py-2 px-1">
                                            {{ $expense->description }}
                                            <span class="block whitespace-nowrap mt-1 text-xs text-gray-600">
                                                {{ $expense->date->format('d M Y') }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-1">
                                            {{ $expense->category->name }}
                                        </td>
                                        <td class="py-2 px-1 pr-6">
                                            <x-table-button type="button" wire:click="editExpense({{ $expense->id }})">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </x-table-button>
                                            <x-table-button type="button" wire:click="deleteExpense({{ $expense->id }})">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </x-table-button>
                                        </td>
                                    </x-tr-body>
                                @endforeach
                            @endif
                        </tbody>
                    </x-primary-table>

                    <div class="px-6 my-3">
                        {!! $expenses->links() !!}
                    </div>

                </div>
                <div class="text-center py-4 px-6">
                    {{ __('Total Amount') }} : <span class="text-red-600">Rp {{ number_format($total_amount, 0, ',', '.') }},-</span>
                </div>
            </div>
        </div>
    </div>


    <x-jet-dialog-modal wire:model="is_create_modal_show" maxWidth="sm">
        <x-slot name="title">
            @if ($is_edit_mode === true) {{ __('Edit Expense') }} @else {{ __('New Expense') }} @endif
        </x-slot>

        <x-slot name="content">

            @if (session('create-alert-body'))
                <div class="font-medium text-sm text-red-600 mb-4">
                    {{ session('create-alert-body') }}
                </div>
            @endif

            <form action="#" method="post" wire:submit.prevent="storeExpense">
            <div class="col-span-6 sm:col-span-4 mb-6">
                <x-jet-label for="date" value="{{ __('Date') }}" required />
                <div class="w-full mt-1">
                    <div wire:ignore>
                        <x-jet-input id="store_date" type="date" class="mt-1 block w-full" wire:model.defer="store_date" autocomplete="off" />
                    </div>
                </div>
                <x-jet-input-error for="store_date" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4 mb-6">
                <x-jet-label for="amount" value="{{ __('Amount') }}" required />
                <x-jet-input id="amount" type="number" class="mt-1 block w-full" wire:model.defer="amount" autocomplete="amount" />
                <x-jet-input-error for="amount" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4 mb-6">
                <x-jet-label for="description" value="{{ __('Description') }}" required />
                <x-jet-input id="description" type="text" class="mt-1 block w-full" wire:model.defer="description" autocomplete="description" />
                <x-jet-input-error for="description" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4 mb-6">
                <x-jet-label for="category_id" value="{{ __('Category') }}" required />
                @if (!empty($category_id) && !empty($category_name))
                    <div class="mt-2 px-4">
                        <div class="mb-2">
                            <input id="selected_category_{{ $category_id }}" type="radio" class="mt-1" name="category_id" wire:model.defer="category_id" value="{{ $category_id }}" checked />
                            <label for="category_{{ $category_id }}" class="ml-1">
                                {{ $category_name }}
                            </label>
                        </div>
                    </div>
                @endif
                <div class="mt-2 w-full h-36 px-4 py-2 overflow-y-auto border border-solid border-gray-300 rounded-lg">
                    @foreach ($categories as $category)
                        @if ($category_id == $category->id)
                            @php
                                continue;
                            @endphp
                        @else
                            <div class="mb-2">
                                <input id="category_{{ $category->id }}" type="radio" class="mt-1" name="category_id" wire:model.defer="category_id" value="{{ $category->id }}" />
                                <label for="category_{{ $category->id }}" class="ml-1">
                                    {{ $category->name }}
                                </label>
                            </div>
                        @endif
                    @endforeach
                </div>
                <x-jet-input-error for="category_id" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button type="button" wire:click="$toggle('is_create_modal_show')" wire:loading.attr="disabled" wire:target="storeExpense">
                {{ __('Cancel') }}
            </x-jet-secondary-button>

            <x-jet-button type="submit" class="ml-2" wire:loading.attr="disabled" wire:target="storeExpense">
                <span wire:loading.remove wire:target="storeExpense">
                    {{ __('Save') }}
                </span>
                <span wire:loading wire:target="storeExpense">
                    {{ __('Saving') }}...
                </span>
            </x-jet-button>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <x-jet-confirmation-modal wire:model="is_delete_modal_show" maxWidth="sm">
        <x-slot name="title">
            {{ __('Delete Expense') }}
        </x-slot>

        @if (session('delete-alert-body'))
            <div class="font-medium text-sm text-red-600 mb-4">
                {{ session('delete-alert-body') }}
            </div>
        @endif

        <x-slot name="content">
            {{ __('Are you sure you want to delete this expense?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('is_delete_modal_show')" wire:loading.attr="disabled" wire:target="destroyExpense">
                {{ __('No, Close') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="destroyExpense" wire:loading.attr="disabled" wire:target="destroyExpense">
                <span wire:loading.remove wire:target="destroyExpense">
                    {{ __('Yes, Delete') }}
                </span>
                <span wire:loading wire:target="destroyExpense">
                    {{ __('Deleting') }}...
                </span>
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>

</div>


@push('top_css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
@endpush

@push('bottom_js')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $('#filter_date_range').flatpickr({
            mode: 'range',
            dateFormat: 'Y-m-d',
            disableMobile: true,
            onChange: function (selectedDates, dateStr, instance) {
                if (selectedDates.length === 1) {
                    instance.config.minDate = moment(selectedDates[0]).subtract(1, 'month').format('YYYY-MM-DD');
                    instance.config.maxDate = moment(selectedDates[0]).add(1, 'month').format('YYYY-MM-DD');

                } else if (selectedDates.length === 2) {
                    @this.set('filter_date_range', dateStr);
                    @this.set('store_date', moment(selectedDates[0]).format('YYYY-MM-DD'));

                    instance.config.minDate = null;
                    instance.config.maxDate = null;

                }
            }
        });

        $('#store_date').flatpickr({
            mode: 'single',
            dateFormat: 'Y-m-d',
            disableMobile: true,
            onChange: function (selectedDates, dateStr, instance) {
                @this.set('store_date', moment(selectedDates[0]).format('YYYY-MM-DD'));
            }
        });


        // $('#category_id').select2({
        //     width: 'resolve'
        // });

        // $('#category_id').on('select2:select', function (e) {
        //     const data = e.params.data;
        //     @this.set('category_id', data.id);
        // });

        // $('#category_id').on('select2:unselect', function (e) {
        //     @this.set('category_id', null);
        // });

        Livewire.on('expenseFetched', function (category) {
            $('#category_id').trigger('change');
        });

        Livewire.on('expenseCreated', function () {
            $('#category_id').val(null).trigger('change');
        });

        $('#filter_category_id').select2({
            width: 'resolve'
        });

        $('#filter_category_id').on('select2:select', function (e) {
            const data = e.params.data;
            @this.set('filter_category_id', data.id);
        });

        $('#filter_category_id').on('select2:unselect', function (e) {
            @this.set('filter_category_id', null);
        });
    </script>
@endpush

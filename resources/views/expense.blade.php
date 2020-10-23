<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Expense') }}
        </h2>
    </x-slot>

    <div class="py-12">
        @livewire('daily-expense-table')
    </div>
</x-app-layout>

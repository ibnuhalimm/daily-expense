<div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="bg-white border-b border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ __('Categories') }}
                    </h3>
                    <div class="mt-6 flex flex-col items-center justify-between gap-4 sm:flex-row">
                        <div class="w-full sm:w-3/5 lg:w-2/3 xl:w-3/4">
                            <x-jet-button wire:click="createCategory">
                                {{ __('New Category') }}
                            </x-jet-button>
                        </div>
                        <div class="w-full sm:w-2/5 lg:w-1/3 xl:w-1/4">
                            <x-jet-input class="w-full" wire:model.debounce.500ms="keyword" type="search" placeholder="Search categories" />
                        </div>
                    </div>
                </div>
                <div class="mb-0">
                    <x-primary-table>
                        <thead>
                            <x-tr-head>
                                <x-th class="w-20 pl-6">#</x-th>
                                <x-th>
                                    {{ __('Name') }}
                                </x-th>
                                <x-th class="w-32">&nbsp;</x-th>
                            </x-tr-head>
                        </thead>
                        <tbody>
                            @if ($categories->isEmpty())
                                <x-tr-body class="hover:bg-gray-100">
                                    <td class="py-3 px-6" colspan="3">
                                        {{ __('No data available') }}
                                    </td>
                                </x-tr-body>
                            @else
                                @foreach ($categories as $idx => $category)
                                    <x-tr-body>
                                        <td class="py-2 px-1 pl-6">
                                            {{ $category->sort_number }}
                                        </td>
                                        <td class="py-2 px-1">
                                            {{ $category->name }}
                                        </td>
                                        <td class="py-2 px-1 pr-6">
                                            <x-table-button type="button" wire:click="editCategory({{ $category->id }})">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </x-table-button>
                                            <x-table-button type="button" wire:click="deleteCategory({{ $category->id }})">
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
                        {!! $categories->links() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>


    <x-jet-dialog-modal wire:model="isCreateModalShow" maxWidth="sm">
        <x-slot name="title">
            @if ($isEditMode === true) {{ __('Edit Category') }} @else {{ __('New Category') }} @endif
        </x-slot>

        <x-slot name="content">

            @if (session('create-alert-body'))
                <div class="font-medium text-sm text-red-600 mb-4">
                    {{ session('create-alert-body') }}
                </div>
            @endif

            <form action="#" method="post" wire:submit.prevent="storeCategory">
            <div class="col-span-6 sm:col-span-4 mb-6">
                <x-jet-label for="name" value="{{ __('Category Name') }}" required />
                <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="name" autocomplete="false" />
                <x-jet-input-error for="name" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4 mb-6">
                <x-jet-label for="name" value="{{ __('Nomor Urut') }}" />
                <x-jet-input id="sort_number" type="number" class="mt-1 block w-full" wire:model.defer="sort_number" autocomplete="false" min="0" />
                <x-jet-input-error for="sort_number" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button type="button" wire:click="$toggle('isCreateModalShow')" wire:loading.attr="disabled" wire:target="storeCategory">
                {{ __('Cancel') }}
            </x-jet-secondary-button>

            <x-jet-button type="submit" class="ml-2" wire:loading.attr="disabled" wire:target="storeCategory">
                <span wire:loading.remove wire:target="storeCategory">
                    {{ __('Save') }}
                </span>
                <span wire:loading wire:target="storeCategory">
                    {{ __('Saving') }}...
                </span>
            </x-jet-button>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <x-jet-confirmation-modal wire:model="isDeleteModalShow" maxWidth="sm">
        <x-slot name="title">
            {{ __('Delete Category') }}
        </x-slot>

        @if (session('delete-alert-body'))
            <div class="font-medium text-sm text-red-600 mb-4">
                {{ session('delete-alert-body') }}
            </div>
        @endif

        <x-slot name="content">
            {{ __('Are you sure you want to delete this data?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('isDeleteModalShow')" wire:loading.attr="disabled" wire:target="destroyCategory">
                {{ __('No, Close') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="destroyCategory" wire:loading.attr="disabled" wire:target="destroyCategory">
                <span wire:loading.remove wire:target="destroyCategory">
                    {{ __('Yes, Delete') }}
                </span>
                <span wire:loading wire:target="destroyCategory">
                    {{ __('Deleting') }}...
                </span>
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>

</div>

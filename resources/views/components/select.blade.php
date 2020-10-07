<div class="relative">
    <select {{ $attributes->merge([
        'class' => 'shadow border rounded-md w-full appearance-none py-2 px-3 text-gray-600 leading-tight focus:outline-none text-md bg-white'
    ]) }}>
        {{ $slot }}
    </select>
    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-600">
        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
        </svg>
    </div>
</div>
<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center p-2 font-semibold text-xs text-gray-700 uppercase tracking-widest hover:text-gray-500 focus:outline-none active:text-gray-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

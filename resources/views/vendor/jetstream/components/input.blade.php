@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'form-input w-full h-10 px-3 py-2 rounded-md shadow-sm outline-none focus:outline-none border border-solid border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mt-1 text-sm']) !!}>

@props(['icon' => null, 'routeName' => null, 'title' => null])

@php
    $isActive = request()->routeIs($routeName);
@endphp

<li>
    <button type="button"
        class="flex items-center w-full p-2 text-base transition duration-200 rounded-lg group
        {{ $isActive 
            ? 'bg-primary-50 text-primary-600 dark:bg-primary-900/20 dark:text-primary-400' 
            : 'text-gray-700 hover:bg-cream-100 hover:text-primary-600 dark:text-gray-200 dark:hover:bg-gray-700' 
        }}"
        aria-controls="{{ $routeName }}" data-collapse-toggle="{{ $routeName }}">

        <div class="flex-shrink-0 w-6 h-6 transition duration-75 
            {{ $isActive 
                ? 'text-primary-500' 
                : 'text-gray-500 group-hover:text-primary-500 dark:text-gray-400 dark:group-hover:text-primary-400' 
            }}">
            {{ $icon }}
        </div>

        <span class="flex-1 ml-3 text-left whitespace-nowrap {{ $isActive ? 'font-bold' : '' }}" sidebar-toggle-item>{{ $title }}</span>

        <svg sidebar-toggle-item class="w-6 h-6 transition duration-75 
            {{ $isActive ? 'text-primary-500 rotate-180' : 'text-gray-400 group-hover:text-primary-500' }}" 
            fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd"></path>
        </svg>
    </button>

    <ul id="{{ $routeName }}" class="space-y-2 py-2 {{ $isActive ? 'block' : 'hidden' }}">
        {{ $slot }}
    </ul>
</li>
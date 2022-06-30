<div class="min-h-screen flex flex-col justify-center items-center sm:pt-6 pt-0 bg-gray-100">
    <div class="w-16 h-16 mb-2">
        {{ $logo }}
    </div>
    
    <div class="mb-4">
        {{ $title }}
    </div>

    <div class="w-full sm:max-w-md max-w-lg px-6 py-6 bg-white shadow-md overflow-hidden sm:rounded-none rounded-lg">
        {{ $slot }}
    </div>
</div>

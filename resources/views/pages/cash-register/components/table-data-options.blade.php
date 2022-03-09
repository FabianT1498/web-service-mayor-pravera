<td>
    <form action="POST" action="{{ route('cash_register.finish', $record->id) }}">
        @csrf
        @method('PUT')
        <button class="h-10 bg-gray-200 rounded-sm">
            <i class="fa-solid fa-floppy-disk text-base text-gray-900"></i> 
        </button>
    </form>
    <a 
        href="{{ route('cash_register.edit', $record->id) }}" 
        class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500"
    >
        <i class="fa-solid fa-pencil"></i>
    </a>
</td>
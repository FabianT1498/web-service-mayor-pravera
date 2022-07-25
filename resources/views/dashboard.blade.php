@extends('layouts.app')

@section('main')
<div class="w-5/6 mx-auto flex">
    @foreach($modules_indexes as $key => $value)
        <a href="{{ route($modules_indexes[$key]) }}" class="block first:ml-0 ml-8">
            <div class="h-72 w-72 p-8 bg-white rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-700 flex flex-col justify-between items-center">
                <div class="h-3/5">
                    <img class="h-full w-full" src="{{ asset("images/" . $modules_images[$key]) }}" />
                </div>
                <div class="h-1/4">
                    <h5 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $modules_titles[$key] }}</h5>
                </div>
            </div>
        </a>
    @endforeach
</div>
@endsection

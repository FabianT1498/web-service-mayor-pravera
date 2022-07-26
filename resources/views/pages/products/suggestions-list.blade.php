@extends('layouts.app')

@section('js')
    <script src="{{ asset('js/products_index.js') }}" defer></script>
@endsection

@section('main')
    <div>
        <div class="mb-4">
            @include('pages.products.components.suggestion-filter-form')
            
            @if ($paginator->count() > 0)
                <div class="w-11/12 mx-auto">
                    <a 
                        class="underline text-sm text-gray-600 hover:text-gray-900 pt-4" 
                        href="#"
                        data-generate-pdf="interval-report"
                    >
                        {{ __('Generar reporte de los productos en formato PDF') }}
                    </a>
                </div>
            @endif
        </div>
        <table class="table table-bordered table-hover mx-auto w-11/12 text-center">
            <thead class="bg-blue-300">
                <tr>
                    @foreach ($columns as $colum)
                        <th scope="col text-center align-middle">{{ $colum }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody id="products-tbody">
                @foreach ($paginator as $key => $value)
                    <tr>
                        <td class="text-center">{{ $value->cod_prod }}</td>
                        <td class="text-center">{{ $value->descrip }}</td>
                        <td class="text-center">{{ number_format($value->percent_suggested, 2) }}</td>
                        <td class="text-center">{{ date('d-m-Y', strtotime($value->created_at)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="w-11/12 mx-auto">
            @if ($paginator->count() === 0)
                <p class="text-center">No hay registros</p>
            @endif
            <div class="mt-8 pb-32">
                {{ $paginator->onEachSide(1)->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
    <x-modal-loading :title="__('Espere mientras se genera el documento PDF')"/>
@endsection
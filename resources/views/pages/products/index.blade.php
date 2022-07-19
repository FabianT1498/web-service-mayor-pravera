@extends('layouts.app')

@section('js')
    <script src="{{ asset('js/products_index.js') }}" defer></script>
@endsection

@section('main')
    <div>
        <div class="mb-4">
            @include('pages.products.components.filter-form')
            
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
        <div class="mx-auto w-11/12">
            <p class="mb-8"> Costo del inventario: {{ number_format($costo_inventario->CostoInventario, 2) }} Bs.s</p>
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
                        <td class="text-center">{{ $value->CodProd }}</td>
                        <td class="text-center">{{ $value->Descrip }}</td>
                        <td class="text-center">{{ number_format($value->CostoPro, 2) }}</td>
                        <td class="text-center">{{ number_format($value->PrecioV + (!$value->EsManual && $value->IVA ? $value->PrecioV * $value->IVA : 0), 2)  }}</td>
                        <td class="text-center">{{ number_format($value->IVA, 2) }}</td>
                        <td class="text-center">{{ number_format($value->Existencia, 2) }}</td>
                        <td class="text-center">{{ number_format($value->CostoExistencia, 2) }}</td>
                        <td class="text-center">{{ number_format($value->PorcentajeUtil, 2) }}</td>
                        <td>
                            <button
                                type="button"
                                data-tooltip-target="suggestion-tooltip"
                                data-modal-toggle="suggestion-modal"
                                data_product_id="{{ $value->CodProd }}"
                                class="font-medium hover:text-teal-600 transition ease-in-out duration-500"
                            >
                                <i class="fas fa-comment"></i>
                            </button>
                            <div 
                                id="suggestion-tooltip"
                                role="tooltip" 
                                class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700"
                            >
                                Ver observaciones
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div> 
                        </td>
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
        <x-modal-product-suggestions
            :modalID="__('suggestion-modal')"
        />
    </div>
    <x-modal-loading :title="__('Espere mientras se genera el documento PDF')"/>
@endsection
  <!-- start sidebar -->
  <div id="sideBar" class="h-full bg-white border-r border-gray-300 p-4 md:-ml-64 
      sticky top-0 md:z-30 md:shadow-xl animate__animated animate__faster basis-1/4">

      @if (session('current_module') === 'cash_register')
        <div class="mb-6 pb-2 border-b border-slate-400 text-sm">
          <p class="text-base font-bold text-gray-600 mb-2">Última tasa del dolar</p>
          <p><span class="font-semibold ">Fecha:</span>&nbsp;<span data-dollar-exchange="dollar_exchange_date">{{ $dollar_exchange?->created_at ? date('d-m-Y', strtotime($dollar_exchange->created_at)) : 'No hay ninguna tasa registrada' }}</span></p>
          <p><span class="font-semibold">Valor:</span>&nbsp;<span data-dollar-exchange="dollar_exchange_value">{{ $dollar_exchange?->bs_exchange ? number_format($dollar_exchange->bs_exchange, 2) : 0 }} Bs.S</span></p>
        </div>
      @endif

    <!-- sidebar content -->
    <div class="flex flex-col text-sm">

      <!-- sidebar toggle -->
      <div class="text-right hidden md:block mb-4">
        <button id="sideBarHideBtn">
          <i class="fad fa-times-circle"></i>
        </button>
      </div>
      <!-- end sidebar toggle -->
      
      @if (session('current_module') === 'products')
        <p class="text-base font-bold text-gray-600 mb-3">Consultar productos</p>

        <!-- link -->
        <a href="{{ route('products.index') }}" class="mb-2 capitalize font-medium  hover:text-teal-600 transition ease-in-out duration-500">
          Consultar productos
        </a>

        <!-- link -->
        <a href="{{ route('products.productsSuggestions') }}" class="mb-2 capitalize font-medium  hover:text-teal-600 transition ease-in-out duration-500">
          Consultar productos con sugerencias
        </a>
      @elseif (session('current_module') === 'cash_register')
        <p class="text-base font-bold text-gray-600 mb-3">Gestion del sistema</p>

        <!-- link -->
        <a href="./index.html" class="mb-2 capitalize font-medium  hover:text-teal-600 transition ease-in-out duration-500">
          Habilitar/Deshabilitar arqueo de caja
        </a>
        <!-- end link -->

        <p class="text-base font-bold text-gray-600 mb-2">Gestión de arqueo de caja</p>

        <!-- link -->
        <a href="{{ route('cash_register.create') }}" class="mb-2 capitalize font-medium  hover:text-teal-600 transition ease-in-out duration-500">
          Registrar arqueo de caja
        </a>
        <!-- end link -->

        <!-- link -->
        <a href="{{ route('cash_register.index') }}" class="mb-2 capitalize font-medium  hover:text-teal-600 transition ease-in-out duration-500">
          Consultar arqueos de caja
        </a>
        <!-- end link -->

        <p class="text-base font-bold text-gray-600 mb-3">Reportes</p>
        <a  href="{{ route('money_entrance.index') }}" class="mb-2 capitalize font-medium  hover:text-teal-600 transition ease-in-out duration-500">Entrada de dinero en cajas</a>
        <a  href="{{ route('drink-bills.index') }}" class="mb-2 capitalize font-medium  hover:text-teal-600 transition ease-in-out duration-500">Facturas fiscales con items de bebidas alcoholicas</a>
        <a href="{{ route('z_bill.index') }}" class="mb-2 capitalize font-medium  hover:text-teal-600 transition ease-in-out duration-500">
          Resumen de facturas fiscales
        </a>
        <a href="{{ route('igtf_tax.index') }}" class="mb-2 capitalize font-medium  hover:text-teal-600 transition ease-in-out duration-500">
          Impuesto IGTF
        </a>
        <a href="{{ route('entradas_divisas.index') }}" class="mb-2 capitalize font-medium  hover:text-teal-600 transition ease-in-out duration-500">
          Registros de entradas electronicas en divisas
        </a>
        <a href="{{ route('vales_vueltos_facturas.index') }}" class="mb-2 capitalize font-medium  hover:text-teal-600 transition ease-in-out duration-500">
          Vales y vueltos por factura
        </a>

        <p class="text-base font-bold text-gray-600 mb-3">Configuraciones</p>
        <!-- link -->
        <a data-modal-toggle="dollar-exchange-modal" href="#" class="mb-3 capitalize font-medium  hover:text-teal-600 transition ease-in-out duration-500">
          Actualizar tasa del dolar
        </a>
        <!-- end link -->
      @elseif (session('current_module') === 'bill_payable')
        <p class="text-base font-bold text-gray-600 mb-3">Facturas por pagar</p>

        <!-- link -->
        <a href="{{ route('bill_payable.index') }}" class="mb-2 capitalize font-medium  hover:text-teal-600 transition ease-in-out duration-500">
          Listado de facturas por pagar
        </a>
        <!-- end link -->

        <!-- link -->
        <a href="{{ route('bill_payable_groups.index') }}" class="mb-2 capitalize font-medium  hover:text-teal-600 transition ease-in-out duration-500">
          Listado de lotes de facturas por pagar
        </a>
        <!-- end link -->

        <p class="text-base font-bold text-gray-600 mb-2">Programaciones de facturas por pagar</p>

        <!-- link -->
        <a href="{{ route('schedule.index') }}" class="mb-2 capitalize font-medium  hover:text-teal-600 transition ease-in-out duration-500">
          Listado de programaciones
        </a>
        <!-- end link -->
      @endif
      <!-- end link -->
    </div>
    <!-- end sidebar content -->
  </div>
  <!-- end sidbar -->

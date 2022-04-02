  <!-- start sidebar -->
  <div id="sideBar" class="relative flex flex-col flex-wrap bg-white border-r border-gray-300 p-6 flex-none w-64 md:-ml-64 md:fixed md:top-0 md:z-30 md:h-screen md:shadow-xl animate__animated animate__faster">


    <!-- sidebar content -->
    <div class="flex flex-col">

      <!-- sidebar toggle -->
      <div class="text-right hidden md:block mb-4">
        <button id="sideBarHideBtn">
          <i class="fad fa-times-circle"></i>
        </button>
      </div>
      <!-- end sidebar toggle -->

      <p class="uppercase text-xs text-gray-600 mb-4 tracking-wider">Gestion del sistema</p>

      <!-- link -->
      <a href="./index.html" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
        <i class="fad fa-cash-register text-xs mr-2"></i>
        Habilitar/Deshabilitar arqueo de caja
      </a>
      <!-- end link -->

      <p class="uppercase text-xs text-gray-600 mb-4 mt-4 tracking-wider">Gestión de arqueo de caja</p>

      <!-- link -->
      <a href="{{ route('cash_register.create') }}" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
        <i class="fad fa-money-bill-wave text-xs mr-2"></i>
        Registrar arqueo de caja
      </a>
      <!-- end link -->

      <!-- link -->
      <a href="{{ route('cash_register.index') }}" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
        <i class="fad fa-book text-xs mr-2"></i>
        Consultar arqueos de caja
      </a>
      <!-- end link -->

      <p class="uppercase text-xs text-gray-600 mb-4 mt-4 tracking-wider">Reportes</p>
      <a  href="{{ route('money_entrance.index') }}" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">Entrada de dinero en cajas</a>
      <a  href="{{ route('drink-bills.index') }}" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">Facturas fiscales con items de bebidas alcoholicas</a>

      <p class="uppercase text-xs text-gray-600 mb-4 mt-4 tracking-wider">Configuraciones</p>
      <!-- link -->
      <a data-modal-toggle="dollar-exchange-modal" href="#" class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
        <i class="fad fa-money-bill-wave text-xs mr-2"></i>
        Actualizar tasa del dolar
      </a>
      <!-- end link -->

    </div>
    <!-- end sidebar content -->

    <x-dollar-exchange-modal />

    </div>
  <!-- end sidbar -->

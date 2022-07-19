  <!-- start sidebar -->
  <div id="sideBar" class="h-full bg-white border-r border-gray-300 p-4 md:-ml-64 
      sticky top-0 md:z-30 md:shadow-xl animate__animated animate__faster basis-1/4">

    <!-- sidebar content -->
    <div class="flex flex-col text-sm">

      <!-- sidebar toggle -->
      <div class="text-right hidden md:block mb-4">
        <button id="sideBarHideBtn">
          <i class="fad fa-times-circle"></i>
        </button>
      </div>
      <!-- end sidebar toggle -->

      <p class="text-base font-bold text-gray-600 mb-3">Consultar productos</p>

      <!-- link -->
      <a href="{{ route('products.index') }}" class="mb-2 capitalize font-medium  hover:text-teal-600 transition ease-in-out duration-500">
        Consultar productos
      </a>
      <!-- end link -->
    </div>
    <!-- end sidebar content -->
  </div>
  <!-- end sidbar -->

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use Flasher\SweetAlert\Prime\SweetAlertFactory;

use App\Repositories\BillsPayableRepository;

use App\Http\Requests\StoreProductSuggestionRequest;

use App\Models\Product;
use App\Models\ProductSuggestion;

use App\Http\Traits\SessionTrait;

class BillsPayableController extends Controller
{
    private $flasher = null;

    public function __construct(SweetAlertFactory $flasher)
    {
        $this->flasher = $flasher;
    }

    public function index(Request $request, BillsPayableRepository $repo){

        $is_dolar = $request->query('is_dolar', '');
        $start_emision_date = $request->query('start_emision_date', '');
        $end_emision_date = $request->query('end_emision_date');
        $min_remaining_days = $request->query('min_remaining_days', 1);
        $max_remaining_days = $request->query('max_remaining_days', 1);
        $is_caduced = $request->query('is_caduced', 1);

        $page = $request->query('page', '');

        $paginator = $repo->getBillsPayable($is_dolar, $start_emision_date, $end_emision_date)->paginate(5);

        if ($paginator->lastPage() < $page){
            $paginator = $repo->getBillsPayable($is_dolar, $start_emision_date, $end_emision_date)->paginate(5, ['*'], 'page', 1);
        }

        $columns = [
            "Numero Fac.",
            "F. Emision.",
            "F. Posteo",
            "Proveedor",
            'Monto',
            'Es dolar',
            "Opciones"
        ];

        return view('pages.products.index', compact(
            'columns',
            'paginator',
            'is_dolar',
            'start_emision_date',
            'end_emision_date',
            'min_remaining_days',
            'max_remaining_days',
            'is_caduced',
            'page',
        ));
    }

    // public function storeProduct(StoreProductSuggestionRequest $request, ProductsRepository $repo){

    //     $validated = $request->validated();

    //     $data = [
    //         'cod_prod' =>  $validated['cod_prod'],
    //         'percent_suggested' => $validated['percent_suggested'],
    //         'user_name' => Auth::user()->CodUsua,
    //         'status' => config('constants.SUGGESTION_STATUS.PROCESSING')
    //     ];

    //     $conn = config("constants.DB_CONN_MAP." . $validated['database']);

    //     $product = $repo->getProductByID($data['cod_prod'], $conn);
        
    //     if (is_null(Product::where('cod_prod', $data['cod_prod'])->first())){
    //         $product = new Product(['cod_prod' => $data['cod_prod'], 'descrip' => $product->Descrip]);
    //         $product->save();
    //     }

    //     $product_suggestion = new ProductSuggestion($data);
        
    //     if ($product_suggestion->save()){

    //         $data['created_at'] = date('d-m-Y', strtotime($product_suggestion->created_at));
    //         return $this->jsonResponse(['data' => $data], 200);
    //     }

    //     return $this->jsonResponse(['data' => []], 500);
        
    // }
}

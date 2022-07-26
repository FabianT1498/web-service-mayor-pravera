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

use App\Repositories\ProductsRepository;

use App\Http\Requests\StoreProductSuggestionRequest;

use App\Models\Product;
use App\Models\ProductSuggestion;

use App\Http\Traits\SessionTrait;

class ProductsController extends Controller
{
    private $flasher = null;

    use SessionTrait;

    public function __construct(SweetAlertFactory $flasher)
    {
        $this->flasher = $flasher;
    }

    public function index(Request $request, ProductsRepository $repo){

        $this->setSession($request, 'current_module', 'products');

        $databases = array_map(function($val, $key){
            return (object) array("key" => $key, "value" => $val);
        }, config('constants.DB_CONN_NAMES'), array_keys(config('constants.DB_CONN_NAMES')));

        $database = $request->query('database', $databases[1]->key);

        $prev_conn = $request->query('prev_conn', $database);

        $conn = config("constants.DB_CONN_MAP." . $database);
        
        $instances = $repo->getInstances($conn)->map(function($item, $key) {
            return (object) array("key" => $item->CodInst, "value" => $item->Descrip);
        });

        $instances->prepend((object) array("key" => null, "value" => 'TODOS')); 
      
        $descrip = $request->query('description', '');
        $instance = $request->query('product_instance', count($instances) > 0 ? $instances[0]->key : '');
        $there_existance = $request->query('there_existance') === '1' ? true : false;
        $is_active = $request->query('is_active', 1);
        $page = $request->query('page', '');

        if ($prev_conn !== $database) {
            $descrip = '';
            $instance = count($instances) > 0 ? $instances[0]->key : '';
            $there_existance = true;
            $is_active = 1;
            $page = 1;
        }

        $costo_inventario = $repo->getTotalCostProducts($conn);

        if (config('constants.DB_CONN_MAP.PRAV') === $conn){
            $instance = count($instances) > 0 ? $instances[0]->key : '';
        }

        $paginator = $repo->getProducts($descrip, $is_active, $instance, $there_existance, $conn)->paginate(5);

        if ($paginator->lastPage() < $page){
            $paginator = $repo->getProducts($descrip, $is_active, $instance, $there_existance, $conn)->paginate(5, ['*'], 'page', 1);
        }

        $columns = [
            "Cod. Produc",
            "Descrip.",
            "Es Fijo",
            "Costo ($)",
            'Precio venta ($)',
            'IVA',
            'Existencia',
            'Costo Inventario ($)',
            "% de ganancia",
            "Sugerencias"
        ];

        return view('pages.products.index', compact(
            'columns',
            'paginator',
            'descrip',
            'instance',
            'instances',
            'database',
            'databases',
            'there_existance',
            'costo_inventario',
            'page',
        ));
    }

    public function getProductsWithSuggestions(Request $request, ProductsRepository $repo){
        
        $this->setSession($request, 'current_module', 'products');

        $status = $request->query('status', config('constants.SUGGESTION_STATUS.PROCESSING'));
        $page = $request->query('page', '');

        $columns = [
            "Cod. Produc",
            'Descripcion',
            "% de ganancia",
            "Ult. Fecha Solicitud",
        ];

        $paginator = $repo->getProductsBySuggestionStatus($status)->paginate(5);

        $suggestion_status = array_map(function($key, $value){
            return (object) array("key" => $key, "value" => $value);
        }, array_keys(config('constants.SUGGESTION_STATUS_ES_UI')), array_values(config('constants.SUGGESTION_STATUS_ES_UI')));

        if ($paginator->lastPage() < $page){
            $paginator = $repo->getProductsBySuggestionStatus($status)->paginate(5, ['*'], 'page', 1);
        }


        return view('pages.products.suggestions-list', compact(
            'columns',
            'paginator',
            'page',
            'suggestion_status',
            'status'
        ));
    }

    public function getProductSuggestions(ProductsRepository $repo, $codProduct){
        $suggestions = $repo->getSuggestions($codProduct);

        return $this->jsonResponse(['data' => $suggestions], 200);
    }

    public function storeProduct(StoreProductSuggestionRequest $request, ProductsRepository $repo){

        $validated = $request->validated();

        $data = [
            'cod_prod' =>  $validated['cod_prod'],
            'percent_suggested' => $validated['percent_suggested'],
            'user_name' => Auth::user()->CodUsua,
            'status' => config('constants.SUGGESTION_STATUS.PROCESSING')
        ];

        $conn = config("constants.DB_CONN_MAP." . $validated['database']);

        $product = $repo->getProductByID($data['cod_prod'], $conn);
        
        if (is_null(Product::where('cod_prod', $data['cod_prod'])->first())){
            $product = new Product(['cod_prod' => $data['cod_prod'], 'descrip' => $product->Descrip]);
            $product->save();
        }

        $product_suggestion = new ProductSuggestion($data);
        
        if ($product_suggestion->save()){

            $data['created_at'] = date('d-m-Y', strtotime($product_suggestion->created_at));
            return $this->jsonResponse(['data' => $data], 200);
        }

        return $this->jsonResponse(['data' => []], 500);
        
    }
}

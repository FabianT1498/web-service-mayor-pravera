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

class ProductsController extends Controller
{
    private $flasher = null;

    public function __construct(SweetAlertFactory $flasher)
    {
        $this->flasher = $flasher;
    }

    public function index(Request $request, ProductsRepository $repo){

        $descrip = $request->query('description', '');
        $instance = $request->query('product_instance', 1652);
        $is_active = $request->query('is_active', 1);
        $page = $request->query('page', '');

        $paginator_params = [];
        
        $instances = $repo->getInstances()->map(function($item, $key) {
            return (object) array("key" => $item->CodInst, "value" => $item->Descrip);
        });

        $paginator = $repo->getProducts($descrip, $is_active, $instance, $paginator_params)->paginate(5);

        $costo_inventario = $repo->getTotalCostProducts();

        if ($paginator->lastPage() < $page){
            $paginator = $repo->getProducts($descrip, $is_active, $instance, $paginator_params)->paginate(5, ['*'], 'page', 1);
        }

        $columns = [
            "Cod. Produc",
            "Descrip.",
            "Costo",
            'Precio venta con IVA($)',
            'IVA',
            'Existencia',
            'Costo Inventario',
            "% de ganancia",
            "Sugerencias"
        ];

        return view('pages.products.index', compact(
            'columns',
            'paginator',
            'descrip',
            'instance',
            'is_active',
            'instances',
            'costo_inventario',
            'page',
        ));
    }

    public function getProductSuggestions(ProductsRepository $repo, $codProduct){
        $suggestions = $repo->getSuggestions($codProduct);

        return $this->jsonResponse(['data' => $suggestions], 200);
    }

    public function storeProduct(StoreProductSuggestionRequest $request){

        $validated = $request->validated();

        $data = [
            'cod_prod' =>  $validated['cod_prod'],
            'percent_suggested' => $validated['percent_suggested'],
            'user_name' => Auth::user()->CodUsua,
        ];
        
        if (is_null(Product::where('cod_prod', $data['cod_prod'])->first())){
            $product = new Product(['cod_prod' => $data['cod_prod']]);
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

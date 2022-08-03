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

use App\Models\Worker;
use App\Models\CashRegisterData;
use App\Models\CashRegister;
use App\Models\DollarCashRecord;
use App\Models\PagoMovilRecord;
use App\Models\BsDenominationRecord;
use App\Models\DollarDenominationRecord;
use App\Models\PointSaleBsRecord;
use App\Models\PointSaleDollarRecord;
use App\Models\ZelleRecord;
use App\Models\Note;

use App\Models\DollarExchange;

use App\Http\Requests\StoreCashRegisterRequest;
use App\Http\Requests\EditCashRegisterRequest;
use App\Http\Requests\UpdateCashRegisterRequest;
use App\Http\Requests\PrintSingleCashRegisterRequest;
use App\Http\Requests\PrintIntervalCashRegisterRequest;

use App\Repositories\CashRegisterRepository;
use App\Repositories\BillRepository;

class DashboardController extends Controller
{
    
    public function index(Request $request){

        $modules_indexes = [
            'products' => 'products.index', 
            'cash_register' => 'cash_register.index',
            'bill_payable' => 'bill_payable.index'
        ];

        $modules_titles = [
            'products' => 'Estadisticas de productos', 
            'cash_register' => 'Cierre de caja',
            'bill_payable' => 'Facturas por pagar'
        ];

        $modules_images = [
            'products' => 'products.jpg', 
            'cash_register' => 'cash-register.jpg',
            'bill_payable' => 'cash-register.jpg',
        ];

        return view('dashboard', compact('modules_indexes', 'modules_images', 'modules_titles'));
    }
}

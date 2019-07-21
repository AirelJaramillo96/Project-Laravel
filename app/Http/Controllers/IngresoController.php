<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Ingreso;
use App\DetalleIngreso;

class IngresoController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterion = $request->criterion;
    
        if ($buscar==''){
            $ingresos = Ingreso::join('personas','ingresos.idsupplier','=','personas.id')
            ->join('users','ingresos.iduser','=','users.id')
            ->select('ingresos.id','ingresos.type_voucher','ingresos.serie_voucher',
            'ingresos.num_voucher','ingresos.date_hour','ingresos.tax','ingresos.total',
            'ingresos.state','personas.name','users.user')
            ->orderBy('ingresos.id', 'desc')->paginate(3);
        }
        else{
            $ingresos = Ingreso::join('personas','ingresos.idsupplier','=','personas.id')
            ->join('users','ingresos.iduser','=','users.id')
            ->select('ingresos.id','ingresos.type_voucher','ingresos.serie_voucher',
            'ingresos.num_voucher','ingresos.date_hour','ingresos.tax','ingresos.total',
            'ingresos.state','personas.name','users.user')
            ->where('ingresos.'.$criterion, 'like', '%'. $buscar . '%')->orderBy('ingresos.id', 'desc')->paginate(3);
        }

        return [
            'pagination' => [
                'total'         =>  $ingresos->total(),
                'current_page'  =>  $ingresos->currentPage(),
                'per_page'      =>  $ingresos->perPage(),
                'last_page'     =>  $ingresos->lastPage(),
                'from'          =>  $ingresos->firstItem(),
                'to'            =>  $ingresos->lastItem(),
            ],
            'ingresos' => $ingresos
        ];
    }
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        try{
            DB::beginTransaction();

            $mytime= Carbon::now('America/Mexico_City');

            $ingreso = new Ingreso();
            $ingreso->idsupplier = $request->idsupplier;
            $ingreso->iduser = \Auth::user()->id;
            $ingreso->type_voucher = $request->type_voucher;
            $ingreso->serie_voucher = $request->serie_voucher;
            $ingreso->num_voucher = $request->num_voucher;
            $ingreso->date_hour = $mytime ->toDateString();
            $ingreso->tax = $request->$tax;
            $ingreso->total = $request->total;
            $ingreso->state = 'Registrado';
            $ingreso->save();


            $detalles = $request->data; //Arrya de detalles
            foreach($detalles as $ep=>$det){
                $detalle = new DetalleIngreso();
                $detalle->identry = $ingreso->id;
                $detalle->idarticle = $det['idarticle'];
                $detalle->quantity = $det['quantity'];
                $detalle->price = $det['price'];
                $detalle->save();
            }

            DB::commit();

        }catch(exception $e){
            DB::rollBack();

        }
        
        //
    }
    public function deactivate(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $ingreso = Ingreso::findOrFail($request->id);
        $ingreso->State = 'Anulado';
        $ingreso->save();
        
        //
    }
}

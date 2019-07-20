<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Supplier;
use App\Persona;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterion = $request->criterion;
         
        if ($buscar == ''){
            $persons = Supplier::join('personas','suppliers.id','=','personas.id')
            ->select('personas.id','personas.name','personas.type_document',
            'personas.num_document','personas.address','personas.phone',
            'personas.email','suppliers.contact','suppliers.phone_contact')
            ->orderBy('personas.id', 'desc')->paginate(3);
        }
        else{
            $persons = Supplier::join('personas','suppliers.id','=','personas.id')
            ->select('personas.id','personas.name','personas.type_document',
            'personas.num_document','personas.address','personas.phone',
            'personas.email','suppliers.contact','suppliers.phone_contact')
            ->where('personas.'.$criterion, 'like', '%'. $buscar . '%')
            ->orderBy('personas.id', 'desc')->paginate(3);
        }
        

        return [
            'pagination' => [
                'total'         =>  $persons->total(),
                'current_page'  =>  $persons->currentPage(),
                'per_page'      =>  $persons->perPage(),
                'last_page'     =>  $persons->lastPage(),
                'from'          =>  $persons->firstItem(),
                'to'            =>  $persons->lastItem(),
            ],
            'persons' => $persons
        ];
    }
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        try{
            DB::beginTransaction();
            $person = new Persona();
            $person->name = $request->name;
            $person->type_document = $request->type_document;
            $person->num_document = $request->num_document;
            $person->address = $request->address;
            $person->phone = $request->phone;
            $person->email = $request->email;
            $person->save();

            $supplier = new Supplier();
            $supplier->contact = $request->contact;
            $supplier->phone_contact = $request->phone_contact;
            $supplier->id = $person->id;
            $supplier->save();

            DB::commit();

        }catch(exception $e){
            DB::rollBack();

        }
        
        //
    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        try{
            DB::beginTransaction();

            $supplier = Supplier::findOrFail($request->id);
            $person = Persona::findOrFail($supplier->id);

            $person->name = $request->name;
            $person->type_document = $request->type_document;
            $person->num_document = $request->num_document;
            $person->address = $request->address;
            $person->phone = $request->phone;
            $person->email = $request->email;
            $person->save();

            $supplier->contact = $request->contact;
            $supplier->phone_contact = $request->phone_contact;
            $supplier->save();

            DB::commit();

        }catch(exception $e){
            DB::rollBack();

        }
        
    }
}

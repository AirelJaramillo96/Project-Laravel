<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Persona;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterion = $request->criterion;
         
        if ($buscar == ''){
            $persons = Persona::orderBy('id', 'desc')->paginate(3);
        }
        else{
            $persons = Persona::where($criterion, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(3);
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
        $person = new Persona();
        $person->name = $request->name;
        $person->type_document = $request->type_document;
        $person->num_document = $request->num_document;
        $person->address = $request->address;
        $person->phone = $request->phone;
        $person->email = $request->email;

        $person->save();
        //
    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $person = Persona::findOrFail($request->id);
        $person->name = $request->name;
        $person->type_document = $request->type_document;
        $person->num_document = $request->num_document;
        $person->address = $request->address;
        $person->phone = $request->phone;
        $person->email = $request->email;
        
        $person->save();
        
        //
    }
}

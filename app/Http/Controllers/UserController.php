<?php

namespace App\Http\Controllers;
use App\User;
use App\Persona;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterion = $request->criterion;
         
        if ($buscar == ''){
            $persons = User::join('personas','users.id','=','personas.id')
            ->join('roles','idrol','=','roles.id')
            ->select('personas.id','personas.name','personas.type_document',
            'personas.num_document','personas.address','personas.phone',
            'personas.email','users.user','users.password',
            'users.condition','users.idrol','roles.name as rol')
            ->orderBy('personas.id', 'desc')->paginate(3);
        }
        else{
            $persons = User::join('personas','users.id','=','personas.id')
            ->join('roles','idrol','=','roles.id')
            ->select('personas.id','personas.name','personas.type_document',
            'personas.num_document','personas.address','personas.phone',
            'personas.email','users.user','users.password',
            'users.condition','users.idrol','roles.name as rol')
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

            $user = new User();
            $user->user = $request->user;
            $user->password = bcrypt($request->password);
            $user->condition = '1';
            $user->idrol = $request->idrol;

            $user->id = $person->id;

            $user->save();

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

            $user = User::findOrFail($request->id);
            $person = Persona::findOrFail($user->id);

            $person->name = $request->name;
            $person->type_document = $request->type_document;
            $person->num_document = $request->num_document;
            $person->address = $request->address;
            $person->phone = $request->phone;
            $person->email = $request->email;
            $person->save();

            $user->user = $request->user;
            $user->password = bcrypt($request->password);
            $user->condition = '1';
            $user->idrol = $request->idrol;
            $user->save();

            DB::commit();

        }catch(exception $e){
            DB::rollBack();

        }
        
    }
    public function deactivate(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condition = '0';
        $user->save();
        
        //
    }
    public function activate(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condition = '1';
        $user->save();
        //
    }



}

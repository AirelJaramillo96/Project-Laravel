<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterion = $request->criterion;
         
        if ($buscar == ''){
            $articles = Article::join('categories','articles.idcategory','=','categories.id')
            ->select('articles.id','articles.idcategory','articles.code','articles.name','categories.name as name_category', 'articles.price_vent','articles.stock','articles.description','articles.condition')
            ->orderBy('articles.id', 'desc')->paginate(3);
        }
        else{
            $articles = Article::join('categories','articles.idcategory','=','categories.id')
            ->select('articles.id','articles.idcategory','articles.code','articles.name','categories.name as name_category', 'articles.price_vent','articles.stock','articles.description','articles.condition')
            ->where('articles.'.$criterion, 'like', '%'. $buscar . '%')
            ->orderBy('articles.id', 'desc')->paginate(3);

        }
        


        return [
            'pagination' => [
                'total'         =>  $articles->total(),
                'current_page'  =>  $articles->currentPage(),
                'per_page'      =>  $articles->perPage(),
                'last_page'     =>  $articles->lastPage(),
                'from'          =>  $articles->firstItem(),
                'to'            =>  $articles->lastItem(),
            ],
            'articles' => $articles
        ];
    }

    public function listarArticulo(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterion = $request->criterion;
         
        if ($buscar == ''){
            $articles = Article::join('categories','articles.idcategory','=','categories.id')
            ->select('articles.id','articles.idcategory','articles.code','articles.name','categories.name as name_category', 'articles.price_vent','articles.stock','articles.description','articles.condition')
            ->orderBy('articles.id', 'desc')->paginate(10);
        }
        else{
            $articles = Article::join('categories','articles.idcategory','=','categories.id')
            ->select('articles.id','articles.idcategory','articles.code','articles.name','categories.name as name_category', 'articles.price_vent','articles.stock','articles.description','articles.condition')
            ->where('articles.'.$criterion, 'like', '%'. $buscar . '%')
            ->orderBy('articles.id', 'desc')->paginate(10);

        }
      
            return ['articles' => $articles];

    }




    public function buscarArticulo(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $filter = $request->filter;
        $articles = Article::where('code','=', $filter)
        ->select('id','name')->orderBy('name', 'asc')->take(1)->get();
 
        return ['articles' => $articles];
    }
    

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $article = new Article();
        $article->idcategory = $request->idcategory;
        $article->code = $request->code;
        $article->name = $request->name;
        $article->price_vent = $request->price_vent;
        $article->stock = $request->stock;
        $article->description = $request->description;
        $article->condition = '1';
        $article->save();
        //
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $article = Article::findOrFail($request->id);
        $article->idcategory = $request->idcategory;
        $article->code = $request->code;
        $article->name = $request->name;
        $article->price_vent = $request->price_vent;
        $article->stock = $request->stock;
        $article->description = $request->description;
        $article->condition = '1';
        $article->save();
        
        //
    }
    public function deactivate(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $article = Article::findOrFail($request->id);
        $article->condition = '0';
        $article->save();
        
        //
    }
    public function activate(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $article = Article::findOrFail($request->id);
        $article->condition = '1';
        $article->save();
        //
    }
}

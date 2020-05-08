<?php

namespace siscont\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use siscont\Extremidad;


class ExtremidadsController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        
        //Controladores de usuarios
        $this->middleware('admin');
    }      
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            $extremidads = Extremidad::select('id','name','active')->orderBy('name')->paginate(10);
            
            return view('extremidads.index',compact('extremidads'));
        }
        else {
            return view('auth/login');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check()) {
            return view('extremidads.create');      
        }
        else {
            return view('auth/login');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::check()) {
            // validate
            $validator = validator::make($request->all(), [
                'name' => 'required|string|max:150|unique:extremidads',
            ]);
            
            if ($validator->fails()) {
                return redirect('extremidads/create')
                            ->withErrors($validator)
                            ->withInput();
            }
            else {
                $extremidads = new Extremidad;
                
                $extremidads->name = $request->input('name');
                $extremidads->active = $request->input('active');
            
                $extremidads->save();            
                
                return redirect('/extremidads')->with('message','store');
            }
        }
        else {
            return view('auth/login');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::check()) {
            $extremidads = Extremidad::find($id);
            
            return view('extremidads.edit',compact('extremidads'));
        }
        else {
            return view('auth/login');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Auth::check()) {
            // validate
            $validator = validator::make($request->all(), [
                'name' => 'required|string|max:150|unique:extremidads,name,'.$id,
            ]);
            
            if ($validator->fails()) {
                return redirect('extremidads/'.$id.'/edit')
                            ->withErrors($validator)
                            ->withInput();
            }
            else {
                $extremidad = Extremidad::find($id);
                
                $extremidad->name = $request->input('name');
                $extremidad->active = $request->input('active');
            
                $extremidad->save();            
                
                return redirect('/extremidads')->with('message','update');
            }
        }
        else {
            return view('auth/login');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

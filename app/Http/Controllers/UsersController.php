<?php

namespace siscont\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

use siscont\User;
use siscont\Role;
use siscont\Establecimiento;
use siscont\Especialidad;

use DB;
use Illuminate\Support\Facades\Auth;

use Excel;

/**
 * Clase Controlador Causal de Egresos
 * Rol: superUsuarioLE
 */
class UsersController extends Controller
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
        $this->middleware('superUsuarioLE');
    }

	/**
     * Display a listing of the resource.
	 * Vista: users.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
		if (Auth::check()) {
			$users = User::search($request->get('search'))
					->select('id','name', 'email','active')
					->where('sistema', '1')
					->orderBy('name')
					->paginate(10)
					->appends('search',$request->get('search'));

			return view('users.index',compact('users'));
		}
		else {
			return view('auth/login');
		}
    }

	/**
	 * Genera archivo excel con el mismo resultado de busqueda de usuarios en resultado
     *
	 * @return archivo excel
	 */
	public function excel() {
		if (Auth::check()) {
			$users = User::select('id','name', 'email','active')->where('sistema', '1')->orderBy('name')->get();

			$usersArray[] = ['Nombre','Nombre de Usuario','Estado','Roles','Establecimientos','Especialidades'];

			foreach ($users as $user) {
				//determina estado
				if( $user->active == 1 ){
					$estado = 'Activo';
				}
				else {
					$estado = 'Inactivo';
				}
				//determina roles establecimientos y especialidades asociadas al usuario
				$usuario = User::find($user->id);
				//rol
				$rol = "";
				foreach ($usuario->roles()->get() as $role) {
					if ($rol != "") {
						$rol = $rol." - ".$role->rol;
					}
					else {
						$rol = $role->rol;
					}
				}
				//establecimiento
				$establecimiento = "";
				foreach ($usuario->establecimientos()->get() as $estab) {
					if ($establecimiento != "") {
						$establecimiento = $establecimiento." - ".$estab->name;
					}
					else {
						$establecimiento = $estab->name;
					}
				}
				//especialidad
				$especialidad = "";
				foreach ($usuario->especialidades()->get() as $espec) {
					if ($especialidad != "") {
						$especialidad = $especialidad." - ".$espec->name;
					}
					else {
						$especialidad = $espec->name;
					}
				}

				$usersArray[] =	[ $user->name, $user->email, $estado, $rol, $establecimiento, $especialidad];

			}

			//Genera archivo Excel
			Excel::create('Usuarios', function($excel) use($usersArray) {
 				$excel->sheet('Usuarios', function($sheet) use($usersArray) {
					$sheet->fromArray($usersArray,null,'A1',true,false);
	 			});
			})->export('xls');
		}
		else {
			return view('auth/login');
		}
	}

    /**
     * Show the form for creating a new resource.
	 * Vista: users.create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
		if (Auth::check()) {
			return view('users.create');
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
				'name' => 'required|string|max:150',
				'email' => 'required|string|max:150|unique:users',
				'password' => 'required|string|min:6|confirmed',
			]);

			if ($validator->fails()) {
				return redirect('users/create')
							->withErrors($validator)
							->withInput();
			}
			else {
				$user = new User;

				$user->name = $request->input('name');
				$user->email = $request->input('email');
				$user->password = bcrypt($request->input('password'));
				$user->sistema = 1;  /* Sistema 1=Lista de Espera, 2 = Siscont */
				$user->active = $request->input('active');

				$user->save();

				return redirect('/users')->with('message','store');
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
	 * Vista: users.edit
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
		if (Auth::check()) {
			$user = User::find($id);

			return view('users.edit',compact('user'));
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
			// valida si password nuevo es correcto
			if( $request->input('password') != null ) {

				$validator2 = validator::make($request->all(), [
					'password' => 'required|string|min:6|confirmed',
				]);

				if ($validator2->fails()) {
					return redirect('users/'.$id.'/edit')
								->withErrors($validator2)
								->withInput();
				}
			}
			//valida otros campos
			$validator = validator::make($request->all(), [
				'name' => 'required|string|max:150',
				'email' => 'required|string|max:150|unique:users,email,'.$id,
			]);

			if ($validator->fails()) {
				return redirect('users/'.$id.'/edit')
							->withErrors($validator)
							->withInput();
			}
			else {
				$user = User::find($id);

				$user->name = $request->input('name');
				$user->email = $request->input('email');
				$user->active = $request->input('active');
				$user->sistema = 1;  /* Sistema 1=Lista de Espera, 2 = Siscont */

				//si cambia el password
				if( $request->input('password') != null ) {
                    $validator = validator::make($request->all(), [
                        'email'     => 'required|string|max:150|unique:users,email,'.$id,
                        'password'  => 'required|string|min:6|confirmed',
                    ]);
                    if ($validator->fails()) {
                        return redirect('users/'.$id.'/edit')
                                    ->withErrors($validator)
                                    ->withInput();
                    }
                    else {
                        $user->password = bcrypt($request->input('password'));
                    }
                }

				$user->save();

				return redirect('/users')->with('message','update');
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

	/**
	 * Función que Actualiza ROL
	 * Vista: users.asignRole
	 *
	 * @param  int  $id
     * @return \Illuminate\Http\Response
	 */
	public function	asignRole($id) {
		if (Auth::check()) {
			$roles     = Role::whereIn('id',[2, 6, 7, 8])->orderBy('rol')->get();
			$user      = User::find($id);

			return view('users.asignRole',compact('user','roles','id'));
		}
		else {
			return view('auth/login');
		}
	}

	/**
	 * Función que Guarda ROL
	 *
	 * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
	 */
	public function saveRole(Request $request) {
		if (Auth::check()) {
			$id = $request->input('userID');
			$user = User::find($id);

			//Graba nuevos roles asignados
			$roles = $request->input('rolesUsuarios');

			$user->roles()->sync($roles);

			return redirect('/users')->with('message','roles');
		}
		else {
			return view('auth/login');
		}
	}

	/**
	 * Función que Actualiza ESTABLECMIENTO
	 * Vista: users.asignEstab
	 *
	 * @param  int  $id
     * @return \Illuminate\Http\Response
	 */
	public function	asignEstab($id) {
		if (Auth::check()) {
			$establecimientos = Establecimiento::orderBy('name')->where('active',1)->get();
			$user             = User::find($id);

			return view('users.asignEstab',compact('user','establecimientos','id'));
		}
		else {
			return view('auth/login');
		}
	}

	/**
	 * Función que Guarda ESTABLECMIENTO
	 *
	 * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
	 */
	public function saveEstab(Request $request) {
		if (Auth::check()) {
			$id = $request->input('userID');
			$user = User::find($id);

			//Graba nuevos roles asignados
			$establecimientos = $request->input('estabUsuarios');

			$user->establecimientos()->sync($establecimientos);

			return redirect('/users')->with('message','establecimientos');
		}
		else {
			return view('auth/login');
		}
	}

	/**
	 * Función que Actualiza ESPECIALIDAD
	 * Vista: users.asignEspec
	 *
	 * @param  int  $id
     * @return \Illuminate\Http\Response
	 */
	public function	asignEspec($id) {
		if (Auth::check()) {
			$especialidads = Especialidad::orderBy('name')->get();
			$user          = User::find($id);

			return view('users.asignEspec',compact('user','especialidads','id'));
		}
		else {
			return view('auth/login');
		}
	}

	/**
	 * Guarda ESPECIALIDAD
	 *
	 * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
	 */
	public function saveEspec(Request $request) {
		if (Auth::check()) {
			$id = $request->input('userID');
			$user = User::find($id);

			//Graba nuevos roles asignados
			$especialidads = $request->input('especUsuarios');

			$user->especialidades()->sync($especialidads);

			return redirect('/users')->with('message','especialidades');
		}
		else {
			return view('auth/login');
		}
	}

}

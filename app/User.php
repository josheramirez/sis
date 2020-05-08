<?php

namespace siscont;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Clase Modelo User - Administra datos de Usuarios del Sistema
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'validate',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	/**
     * Funcion para el filtro de usuarios
     *
     * @param string $query Consulta
     * @param string $search paramentro de consulta
     * @return void
     */
	public function scopeSearch($query,$search) {
		if( trim($search) != "" ){
			$query->where('email', "LIKE", "%$search%");
		}
	}

	/**
     * Funcion que retorna Roles Asociados a un Usuario
     *
     * @return void clase Rol
     */
	public function roles()
    {
        return $this->belongsToMany('siscont\Role');
    }
    
    /**
     * Funcion que verifica si Usuario se encuentra asociado a Rol
     *
     * @param string $roleName Nombre de Rol
     * @return boolean True - Si Rol está relacionado al Usuario. Sino, Falso.
     */
	public function isRole($roleName)
    {
        foreach ($this->roles()->get() as $role)
        {
			if ($role->rol == $roleName)
            {
                return true;
            }
        }

        return false;
    }
	
	/**
     * Funcion que retorna Establecimientos Asociados a un Usuario
     *
     * @return void clase Establecimiento
     */
	public function establecimientos()
    {
        return $this->belongsToMany('siscont\Establecimiento');
    }
    
    /**
     * Funcion que verifica si Usuario se encuentra asociado a Establecimiento
     *
     * @param string $estabName Nombre de Establecimiento
     * @return boolean True - Si Establecimiento está relacionado al Usuario. Sino, Falso.
     */
	public function isEstab($estabName)
    {
        foreach ($this->establecimientos()->get() as $estab)
        {
			if ($estab->name == $estabName)
            {
                return true;
            }
        }

        return false;
    }	
	
    /**
     * Funciones para determinar Especialidades de Usuario
     *
     * @return void clase Especialidades
     */
	public function especialidades()
    {
        return $this->belongsToMany('siscont\Especialidad');
    }
    
    /**
     * Funcion que verifica si Usuario se encuentra asociado a una Especialidad
     *
     * @param string $especName  Nombre Especialidad
     * @return boolean  True - Si Especialida está relacionado al Usuario. Sino, Falso
     */
	public function isEspec($especName)
    {
        foreach ($this->especialidades()->get() as $espec)
        {
			if ($espec->name == $especName)
            {
                return true;
            }
        }

        return false;
    }	
}

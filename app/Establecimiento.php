<?php

namespace siscont;

use Illuminate\Database\Eloquent\Model;

/**
 * Clase Modelo Establecimiento - Administra datos de Establecimientos
 */
class Establecimiento extends Model
{
    /**
     * Funcion que retorna Comunas Asociados a un Establecimiento
     *
     * @return void clase Comuna
     */
    public function comuna()
    {
        return $this->hasMany('siscont\Comuna');
    }
    
    /**
     * Funcion que retorna Tipos de Establecimientos Asociados a un Establecimiento
     *
     * @return void clase Tipo
     */
	public function tipo()
    {
        return $this->hasMany('siscont\TipoEstab');
    }
	
	/**
     * Funcion que retorna Usuarios Asociados a un Establecimiento
     *
     * @return void clase Usuarios
     */
	public function users()
    {
        return $this->belongsToMany('siscont\User');
    }
	
	/**
     * Funcion que retorna el nombre del establecimiento
     *
     * @param int $id id de Establecimiento
     * @return string nombre de establecimiento
     */
	public function nombreEstablecimiento($id)
	{
		return Establecimiento::select('name')->where('id',$id);
	}
}

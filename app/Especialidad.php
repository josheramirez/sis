<?php

namespace siscont;

use Illuminate\Database\Eloquent\Model;

/**
 * Clase Modelo Especialidad - Administra datos de Especialidades
 */
class Especialidad extends Model
{
	/**
     * Funciones para determinar Usuarios asociados a un Especialidad
	 * 
	 * @return void clase Users
     */
	public function users()
    {
        return $this->belongsToMany('siscont\User');
    }
    
	/**
	 * Funcion para el filtro de Cie10 por Nombre
	 * 
	 * @param string $query Consulta
     * @param string $searchName paramentro de consulta por Nombre
	 * @return list resultado de consulta
	 */
	public function scopeSearchName($query,$searchName) {
		if( trim($searchName) != "" ){
			$query->where('name', "LIKE", "%$searchName%");
		}
	}
	
	/**
	 * Funcion para el filtro de proveedores por REM
	 * 
	 * @param string $query Consulta
     * @param string $searchRem paramentro de consulta código REM
	 * @return list resultado de consulta
	 */
	public function scopeSearchRem($query,$searchRem) {
		if( trim($searchRem) != "" ){
			$query->where('rem', "LIKE", "%$searchRem%");
		}
	}
}

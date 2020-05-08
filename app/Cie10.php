<?php

namespace siscont;

use Illuminate\Database\Eloquent\Model;

/**
 * Clase Modelo Cie10 - Administra datos de Diagnosticos Cie10s
 */
class Cie10 extends Model
{
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
	 * Funcion para el filtro de proveedores por Codigo
	 * 
	 * @param string $query Consulta
     * @param string $searchCodigo paramentro de consulta por Codigo
	 * @return list resultado de consulta
	 */
	public function scopeSearchCodigo($query,$searchCodigo) {
		if( trim($searchCodigo) != "" ){
			$query->where('codigo', "LIKE", "%$searchCodigo%");
		}
	}
}

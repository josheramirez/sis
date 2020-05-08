<?php

namespace siscont;

use Illuminate\Database\Eloquent\Model;
use DB;

/**
 * Clase Modelo Paciente - Administra datos de Pacientes
 */
class Paciente extends Model
{
	/**
	 * Funcion para el filtro de proveedores por Nombre
	 *
	 * @param string $query Consulta
	 * @param string $searchNombre paramentro de consulta por Nombre
	 * @return list resultado de consulta
	 */
	public function scopeSearchNombre($query,$searchNombre) {
		if( trim($searchNombre) != "" ){
			$query->where(DB::raw("CONCAT(`nombre`, ' ', `apPaterno`, ' ', `apMaterno`)"), "LIKE", "%$searchNombre%");
		}
	}

	/**
	 * Funcion para el filtro de proveedores por Rut
	 *
	 * @param string $query Consulta
	 * @param string $searchRut paramentro de consulta por RUT
	 * @return void list resultado de consulta
	 */
	public function scopeSearchRut($query,$searchRut) {
		if( trim($searchRut) != "" ){
			$query->where('rut', "LIKE", "%$searchRut%");
		}
	}

	/**
	 * Funcion para el filtro de proveedores por Número de Documento
	 *
	 * @param string $query Consulta
	 * @param string $searchDoc paramentro de consulta por Número de Documento
	 * @return void list resultado de consulta
	 */
	public function scopeSearchDoc($query,$searchDoc) {
		if( trim($searchDoc) != "" ){
			$query->where('numDoc', "LIKE", "%$searchDoc%");
		}
	}
}

<?php

namespace siscont;

use Illuminate\Database\Eloquent\Model;

/**
 * Clase Modelo TipoProcedimiento - Administra datos de Tipo de Procedimiento
 */
class TipoProcedimiento extends Model
{
    /**
     * Funcion que retorna TipoPrestacion Asociados a Procedimientos
     *
     * @return void clase TipoPrestacion
     */
    public function tipoprocedimiento()
    {
        return $this->hasMany('siscont\TipoPrestacion');
    }

    /**
     * Funcion que retorna TipoPrestacion
     *
     * @return void clase TipoPrestacion
     */
	public function tipoprestacion()
    {
        return $this->belongsTo('siscont\TipoPrestacion','tipo_prestacion_id','id');
    }
}

<?php

namespace siscont;

use Illuminate\Database\Eloquent\Model;

/**
 * Clase Modelo TipoProcedimiento - Administra datos de Tipo de Procedimiento
 */
class TipoProcedimientosPm extends Model
{
    /**
     * Funcion que retorna TipoProcedimientos Asociados a Procedimientos_PMS
     *
     * @return void clase TipoProcedimiento
     */
    public function tipoprocedimientospm()
    {
        return $this->hasMany('siscont\TipoProcedimiento');
    }

    /**
     * Funcion que retorna TipoProcedimiento
     *
     * @return void clase TipoProcedimiento
     */
	public function tipoprocedimiento()
    {
        return $this->belongsTo('siscont\TipoProcedimiento','tipo_procedimiento_id','id');
    }

    /**
     * Funcion que retorna Extremidades Asociados a Procedimientos_PMS
     *
     * @return void clase Extremidad
     */
    public function extremidads()
    {
    	return $this->belongsToMany('siscont\Extremidad','proced_pms_extremidad','proced_pms_id','extremidad_id');
    }

    /**
     * Funcion que verifica si TipoProcedimientoPMS se encuentra asociado a una Estremidad
     *
     * @param string $extremidadName Nombre del Extremidad
     * @return boolean True - Si Extremidad está relacionado al TipoProcedimientoPMS. Sino, Falso
     */
    public function isExtremidad($extremidadName)
    {
        foreach ($this->extremidads()->get() as $extremidad)
        {
			if ($extremidad->name == $extremidadName)
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Funcion que retorna Planos Asociados a Procedimientos_PMS
     *
     * @return void clase Plano
     */
    public function planos()
    {
    	return $this->belongsToMany('siscont\Plano','proced_pms_plano','proced_pms_id','plano_id');
    }

    /**
     * Funcion que verifica si TipoProcedimientoPMS se encuentra asociado a una Plano
     *
     * @param string $planoName Nombre del Plano
     * @return boolean True - Si Plano está relacionado al TipoProcedimientoPMS. Sino, Falso
     */
    public function isPlano($planoName)
    {
        foreach ($this->planos()->get() as $plano)
        {
			if ($plano->name == $planoName)
            {
                return true;
            }
        }

        return false;
    }

}

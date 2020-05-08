<?php

namespace siscont;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Clase Modelo Causal de ListaEspera - Administra datos de Listas de Espera
 */
class ListaEspera extends Model
{
   	/**
	 * Función que determina si Lista de Espera Ingresada se encuentra Duplicada.
	 * La duplicidad de la lista de espera se define según manual de ingreso de lista de esperas no ges, desarrollado por Minasl
	 *
	 * @return boolean Indica True si lista de espera se encuentra duplicada y False si lista de espera no se encuentra duplicada
	 */
	public function Duplicado()
	{
		//parametros iniciales
		$pacientes_id               = $this->pacientes_id;
		$idCie10                    = $this->cie10s_id;
		$especialidads_ingreso_id   = $this->especialidads_ingreso_id ;
		$establecimiento_destino_id = $this->establecimiento_destino_id;
		$planos_id                  = $this->planos_id; 
		$extremidads_id             = $this->extremidads_id;
		$requiere_plano 			= DB::table('tipo_procedimientos_pms')->WHERE([['id',$this->tipo_procedimientos_pms_id],['active', 1],['requiere_plano',1]])->exists();
		$requiere_extremidad 		= DB::table('tipo_procedimientos_pms')->WHERE([['id',$this->tipo_procedimientos_pms_id],['active', 1],['requiere_extremidad',1]])->exists();
		$establecimiento_destino    = Establecimiento::find($this->establecimientos_id_destino);
		$prestamin_ing				= $this->prestamin_ing;
		$tipo_prestacions_id		= $this->tipo_prestacions_id;
		$f_entrada					= $this->fechaingreso;

		if ($tipo_prestacions_id == 1) { // Consulta Nueva
			//determina duplicidad    
			$listaEsperas = DB::table('lista_esperas')
				->join('establecimientos as estdestino', 'estdestino.id', '=', 'lista_esperas.establecimientos_id_destino')
				->where('lista_esperas.pacientes_id', '=', $pacientes_id)
				->where('lista_esperas.tipo_prestacions_id', '=', $tipo_prestacions_id)
				->where('lista_esperas.especialidads_ingreso_id', '=', $especialidads_ingreso_id)
				->where('lista_esperas.active', '=', 1)
				->where('estdestino.nivel_id','=',$establecimiento_destino->nivel_id)
				->select('lista_esperas.id')->get();

			if (count($listaEsperas) > 0) {
				return true;
			}

			//determina fecha de ultimo egreso
			$fechaEgreso = DB::table('lista_esperas')
				->join('establecimientos as estdestino', 'estdestino.id', '=', 'lista_esperas.establecimientos_id_destino')
				->where('lista_esperas.pacientes_id', '=', $pacientes_id)
				->where('lista_esperas.tipo_prestacions_id', '=', $tipo_prestacions_id)
				->where('lista_esperas.especialidads_ingreso_id', '=', $especialidads_ingreso_id)
				->where('lista_esperas.active', '=', 0)
				->where('estdestino.nivel_id','=',$establecimiento_destino->nivel_id)
				->where('lista_esperas.fechaegreso','>',$f_entrada)
				->whereNotIn('lista_esperas.causal_egresos_id',['13','15']) 
				->max('lista_esperas.fechaegreso');

			if( $fechaEgreso != null ) {
				return true;	
			}    
		}
		elseif ($tipo_prestacions_id == 3) { // Procedimiento
			//determina duplicidad    
			$listaEsperas = DB::table('lista_esperas')
				->join('establecimientos as estdestino', 'estdestino.id', '=', 'lista_esperas.establecimientos_id_destino')
				->where('lista_esperas.pacientes_id', '=', $pacientes_id)
				->where('lista_esperas.tipo_prestacions_id', '=', $tipo_prestacions_id)
				->where('lista_esperas.prestamin_ing', '=', $prestamin_ing)
				->where('lista_esperas.active', '=', 1)
				->where('estdestino.nivel_id','=',$establecimiento_destino->nivel_id)
				->select('lista_esperas.id')->get();

			if (count($listaEsperas) > 0) {
				return true;
			}

			//determina fecha de ultimo egreso
			$fechaEgreso = DB::table('lista_esperas')
				->join('establecimientos as estdestino', 'estdestino.id', '=', 'lista_esperas.establecimientos_id_destino')
				->where('lista_esperas.pacientes_id', '=', $pacientes_id)
				->where('lista_esperas.tipo_prestacions_id', '=', $tipo_prestacions_id)
				->where('lista_esperas.prestamin_ing', '=', $prestamin_ing)
				->where('lista_esperas.active', '=', 0)
				->where('estdestino.nivel_id','=',$establecimiento_destino->nivel_id)
				->where('lista_esperas.fechaegreso','>',$f_entrada)
				->whereNotIn('lista_esperas.causal_egresos_id',['13','15']) 
				->max('lista_esperas.fechaegreso');

			if( $fechaEgreso != null ) {				
				return true;
			}
		}
		elseif ($tipo_prestacions_id == 4 || $tipo_prestacions_id == 5) { //Intervención Quirurgica
            if($requiere_plano == FALSE && $requiere_extremidad == FALSE) { //No Requiere Plano Ni Extremidad
                //determina duplicidad    
                $listaEsperas = DB::table('lista_esperas')
                    ->where('lista_esperas.pacientes_id', '=', $pacientes_id)
                    ->whereIn('lista_esperas.tipo_prestacions_id', [4,5])
                    ->where('lista_esperas.prestamin_ing', '=', $prestamin_ing)
                    ->where('lista_esperas.active', '=', 1)
                    ->select('lista_esperas.id')->get();

                if (count($listaEsperas) > 0) {
                    return true;
                }

                //determina fecha de ultimo egreso
                $fechaEgreso = DB::table('lista_esperas')
                    ->where('lista_esperas.pacientes_id', '=', $pacientes_id)
                    ->whereIn('lista_esperas.tipo_prestacions_id', [4,5])
                    ->where('lista_esperas.prestamin_ing', '=', $prestamin_ing)
                    ->where('lista_esperas.active', '=', 0)
                    ->where('lista_esperas.fechaegreso','>',$f_entrada)
                    ->whereNotIn('lista_esperas.causal_egresos_id',[13,15])
                    ->max('lista_esperas.fechaegreso');

                if( $fechaEgreso != null ) {
            		return true;	
                }
            }
            elseif($requiere_plano == TRUE && $requiere_extremidad == FALSE) { //Requiere Plano, No Extremidad
                //determina duplicidad    
                $listaEsperas = DB::table('lista_esperas')
                    ->where('lista_esperas.pacientes_id', '=', $pacientes_id)
                    ->whereIn('lista_esperas.tipo_prestacions_id', [4,5])
                    ->where('lista_esperas.prestamin_ing', '=', $prestamin_ing)
                    ->where('lista_esperas.planos_id', '=', $planos_id)
                    ->where('lista_esperas.active', '=', 1)
                    ->select('lista_esperas.id')->get();

                if (count($listaEsperas) > 0) {
                    return true;
                }

                //determina fecha de ultimo egreso
                $fechaEgreso = DB::table('lista_esperas')
                    ->where('lista_esperas.pacientes_id', '=', $pacientes_id)
                    ->whereIn('lista_esperas.tipo_prestacions_id', [4,5])
                    ->where('lista_esperas.prestamin_ing', '=', $prestamin_ing)
                    ->where('lista_esperas.planos_id', '=', $planos_id)
                    ->where('lista_esperas.active', '=', 0)
                    ->where('lista_esperas.fechaegreso','>',$f_entrada)
                    ->whereNotIn('lista_esperas.causal_egresos_id',[13,15])
                    ->max('lista_esperas.fechaegreso');

                if( $fechaEgreso != null ) {
                    return true;
                }    
            }
            elseif($requiere_plano == TRUE && $requiere_extremidad == TRUE) { //Requiere Plano y Extremidad 
                //determina duplicidad    
                $listaEsperas = DB::table('lista_esperas')
                    ->where('lista_esperas.pacientes_id', '=', $pacientes_id)
                    ->whereIn('lista_esperas.tipo_prestacions_id', [4,5])
                    ->where('lista_esperas.prestamin_ing', '=', $prestamin_ing)
                    ->where('lista_esperas.planos_id', '=', $planos_id)
                    ->where('lista_esperas.extremidads_id', '=', $extremidads_id)
                    ->where('lista_esperas.active', '=', 1)
                    ->select('lista_esperas.id')->get();

                if (count($listaEsperas) > 0) {
                    return true;
                }

                //determina fecha de ultimo egreso
                $fechaEgreso = DB::table('lista_esperas')
                    ->where('lista_esperas.pacientes_id', '=', $pacientes_id)
                    ->whereIn('lista_esperas.tipo_prestacions_id', [4,5])
                    ->where('lista_esperas.prestamin_ing', '=', $prestamin_ing)
                    ->where('lista_esperas.planos_id', '=', $planos_id)
                    ->where('lista_esperas.extremidads_id', '=', $extremidads_id)
                    ->where('lista_esperas.active', '=', 0)
                    ->where('lista_esperas.fechaegreso','>',$f_entrada)
                    ->whereNotIn('lista_esperas.causal_egresos_id',[13,15])
                    ->max('lista_esperas.fechaegreso');

                if( $fechaEgreso != null ) {
                    return true;    
                }
            } 
        }		

		return false;			
	}
}

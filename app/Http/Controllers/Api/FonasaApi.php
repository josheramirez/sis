<?php

namespace siscont\Http\Controllers\Api;

use nusoap_client;
use Illuminate\Http\Request;
use siscont\Http\Controllers\Controller;
use Carbon\Carbon;

class FonasaApi extends Controller
{
    public function fetch($rut, $dv)
    {
        return $this->callFonasaApi($rut, $dv);
    }

    public function fetchNormalized($rut, $dv)
    {
        return $this->normalizePacienteFonasa2($this->callFonasaApi($rut, $dv));
    }

    protected function getClasificacionFonasa($tramo)
    {
        $clasificacion = 0;

        switch ($tramo) {
            case "A":
                $clasificacion = 1;
                break;
            case "B":
                $clasificacion = 2;
                break;
            case "C":
                $clasificacion = 3;
                break;
            case "D":
                $clasificacion = 4;
                break;
            default:
                break;
        }

        return $clasificacion;
    }

    protected static function getSexoFonasa($genero)
    {
        $sexo = 0;

        switch ($genero) {
            case 'M':
                $sexo = 1;
                break;
            case 'F':
                $sexo = 2;
                break;
            default:
                $sexo = 4;
                break;
        }

        return $sexo;
    }

    protected function callFonasaApi($rut, $dv)
    {
        $objSOAP = new nusoap_client("http://ws.fonasa.cl:8080/Certificados/Previsional?wsdl", "wsdl", "", "", "", "");
        $objSOAP->soap_defencoding = 'UTF-8';
        $objSOAP->decode_utf8 = FALSE;
        $clientError = $objSOAP->getError();

        if ($clientError) {
            return $clientError;
        }

        $parametros = [
            'query' => [
                'queryTO' => ['tipoEmisor' => '0', 'tipoUsuario' => '0'],
                'entidad' => '61608200',
                'claveEntidad' => '6160',
                'rutBeneficiario' => $rut,
                'dgvBeneficiario' => $dv,
                'canal' => '0'
            ]
        ];

        try {
            $result = $objSOAP->call('getCertificadoPrevisional', ['parameters' => $parametros], '', '', false, true);
            if ($objSOAP->fault) {
                return $result;
            } else {
                $error = $objSOAP->getError();
                if ($error) {
                    return $error;
                } else {
                    return $result;
                }
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    protected function normalizePacienteFonasa($fonasaResponse)
    {
        $clasificacion = 0;

        $data = [
            "nr_run" => null,
            "prevision" => null,
            "id_prevision" => null,
            "tramo" => null,
            "clasificacion" => null,
            "direccion" => null,
            "nombre_completo" => null,
            "comuna" => null,
            "fecha_nacimiento" => null,
            "encontrado" => false
        ];

        if (isset($fonasaResponse["getCertificadoPrevisionalResult"])) {
            $cert = $fonasaResponse["getCertificadoPrevisionalResult"];
            if ($cert["replyTO"]["estado"] != -4 && $cert["replyTO"]["errorM"] == "") {

                $beneficiario = $cert["beneficiarioTO"];
                $clasificacion = $this->getClasificacionFonasa($cert["afiliadoTO"]["tramo"]);
                $sexo = $this->getSexoFonasa($beneficiario["genero"]);

                $fecha_nacimiento = Carbon::parse($beneficiario['fechaNacimiento'], 'America/Santiago');
                $hoy = Carbon::now();
                $edad = $hoy->diff($fecha_nacimiento)->y;
                $data = [
                    "nr_run" => $beneficiario["rutbenef"],
                    "tx_digito_verificador" => $beneficiario["dgvbenef"],
                    "tx_apellido_paterno" => $beneficiario['apell1'],
                    "tx_apellido_materno" => $beneficiario['apell2'],
                    "tx_nombre" => $beneficiario['nombres'],
                    "id_sexo" => $sexo,
                    "coddesc" => $cert["coddesc"],
                    "cdgComuna" => $beneficiario["cdgComuna"],
                    "prevision" => $cert['cdgIsapre'] != " " ? "ISAPRE" : "FONASA",
                    "id_prevision" => $cert['cdgIsapre'] != " " ? 2 : 1,
                    "tramo" => $cert["afiliadoTO"]["tramo"],
                    "id_clasificacion_fonasa" => $clasificacion === 0 ? null : $clasificacion,
                    "tx_direccion" => $beneficiario['direccion'],
                    "nombre_completo" => "{$beneficiario['nombres']} {$beneficiario['apell1']} {$beneficiario['apell2']}",
                    "comuna" => "{$beneficiario['desComuna']}",
                    "fecha_nacimiento" => $fecha_nacimiento->format('d/m/Y'),
                    "edad" => $edad,
                    "encontrado" => true,
                    "rut" => "{$beneficiario["rutbenef"]}-{$beneficiario["dgvbenef"]}",
                    "nr_ficha" => null
                ];
            }
        }

        return $data;
    }

    protected function normalizePacienteFonasa2($fonasaResponse)
    {
        $clasificacion = 0;

        // $data = [
        //     "fc_ingreso" => null,
        //     "RUT" => null,
        //     "DV" => null,
        //     "FICHA" => null,
        //     "APEPAT" => null,
        //     "APEMAT" => null,
        //     "NOMBRES" => null,
        //     "NACIONALIDAD" => null,
        //     "SEXO" => null,
        //     "F_NACTO" => null,
        //     "EDAD" => null,
        //     "T_EDAD" => null,
        //     "PAC_DIR" => null,
        //     "COD_COMU" => null,
        //     "FONO" => null,
        //     "COD_CPR" => null,
        //     "COD_INP" => null,
        //     "COD_ECIVIL" => null,
        //     "NOM_COM" => null,
        //     "EPISODIO" => null,
        //     "DESC_PREV" => null,
        //     "DESC_PLAN" => null
        // ];
        
        if (gettype($fonasaResponse) == "string") {
            return "error conexion fonasa";
        } else {
            if (isset($fonasaResponse["getCertificadoPrevisionalResult"])) {
                $cert = $fonasaResponse["getCertificadoPrevisionalResult"];
                if (empty($cert["replyTO"]['errorM'])) {
                    if ($cert["replyTO"]["estado"] != -4 && $cert["replyTO"]["errorM"] == "") {
                        $beneficiario = $cert["beneficiarioTO"];
                        $clasificacion = $this->getClasificacionFonasa($cert["afiliadoTO"]["tramo"]);
                        $sexo = $this->getSexoFonasa($beneficiario["genero"]);

                        $fecha_nacimiento = Carbon::parse($beneficiario['fechaNacimiento'], 'America/Santiago');
                        $hoy = Carbon::now();
                        $data = [
                            "RESPUESTA_ORIGINAL" => $fonasaResponse["getCertificadoPrevisionalResult"],
                            "fc_ingreso" => date('Y-m-d', strtotime($cert["replyTO"]["fecha"])),
                            "rut" => $beneficiario["rutbenef"],
                            "dv" => $beneficiario["dgvbenef"],
                            "ficha" => $cert["folio"],
                            "apellido_paterno" => $beneficiario["apell1"],
                            "apellido_materno" => $beneficiario["apell2"],
                            "nombres" => $beneficiario["nombres"],
                            "nacionalidad" => $beneficiario["desNacionalidad"],
                            "genero" => $beneficiario["genero"],
                            "fecha_nacimiento" => $beneficiario["fechaNacimiento"],
                            "edad" => $hoy->diff($fecha_nacimiento)->y,
                            "direccion" => $beneficiario["direccion"],
                            "comuna" => $beneficiario["cdgComuna"],
                            "telefono" => "",
                            "des_comuna" => $beneficiario["desComuna"],
                        ];
                    }
                } else {
                    return $cert["replyTO"]['errorM'];
                }
            }

            return $data;
        }
    }
}

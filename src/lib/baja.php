<?php

namespace Lib;

use Greenter\Model\Voided\Voided;
use Greenter\Model\Voided\VoidedDetail;

use Lib\Base;

class Baja extends Base {

    public function build($data){

        $template = array(
			"emisor" => null,
			"cabecera" => null,
			"detalle" => array()
		);

        $detalle = [];
        foreach ($data->detalle as $row){
            $doc = new VoidedDetail();
            $doc->setTipoDoc($row->tipo_documento)
                ->setSerie($row->serie)
                ->setCorrelativo($row->numero)
                ->setDesMotivoBaja($row->motivo);
            array_push($detalle, $doc);
        }

        $cabecera = $data->cabecera;
        $voided = new Voided();
        $voided->setCorrelativo($cabecera->correlativo)
            ->setFecComunicacion(new \DateTime($cabecera->fecha_comunicacion))
            ->setFecGeneracion(new \DateTime($cabecera->fecha_generacion))
            ->setCompany($this->getEmisor($data->emisor))
            ->setDetails($detalle);
            
        return $voided;
    }

}
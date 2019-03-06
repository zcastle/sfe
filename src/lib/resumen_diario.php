<?php

namespace Lib;

use Greenter\Model\Summary\Summary;
use Greenter\Model\Summary\SummaryDetail;
//
use Lib\See;

class ResumenDiario {

  public function build($data, $fechaHora){
    $cabecera = $data->cabecera;

    $summary = new Summary();
    $summary->setFecGeneracion($fechaHora);
    $summary->setFecResumen($fechaHora);
    $summary->setCorrelativo($cabecera->correlativo);
    //$summary->setCompany($this->getCompany());

    // DETALLE
    $items = [];
    foreach ($data->detalle as $row) {
        $item = new SummaryDetail();
        $item->setTipoDoc(See::BOLETA);
        $item->setSerieNro($row->serie . '-' . $row->numero);
        $item->setEstado(1); // ?
        $item->setClienteTipo($row->tipo_cliente);
        $item->setClienteNro($row->ruc);
        $item->setMtoOperGravadas($row->operaciones_gravadas);
        $item->setMtoIGV($row->igv);
        if($row->servicio > 0){
            $item->setMtoOperInafectas($row->servicio);
        }
        $item->setTotal($row->importe_total);
        
        array_push($items, $item);
    }
    $summary->setDetails($items);
    //

    return $summary;
  }

}
?>

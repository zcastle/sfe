<?php

namespace Lib;

use Greenter\Model\Sale\Note;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Company\Address;
use Greenter\Model\Client\Client;
//
use Lib\See;
use Lib\Base;
use Lib\NumberToLetterConverter;

class Nota extends Base {

	public function __construct(){}

	public function build($data){
		$cabecera = $data->cabecera;
		$note = new Note();
		$note->setUblVersion('2.1');
		$note->setTipDocAfectado($cabecera->tipo_documento_afectado);
		$note->setNumDocfectado($cabecera->numero_documento_afectado);
		$note->setCodMotivo($cabecera->codigo_motivo);
		$note->setDesMotivo($cabecera->descripcion_motivo);
		$note->setTipoDoc($cabecera->tipo_documento);
		$note->setFechaEmision(new \DateTime($cabecera->fecha_emision));
		$note->setSerie("FN01");
		$note->setCorrelativo($cabecera->numero);
		$note->setTipoMoneda($cabecera->tipo_moneda);
		$note->setClient($this->getReceptor($data->receptor));
		$note->setMtoOperGravadas($cabecera->operaciones_gravadas);
		$note->setMtoOperExoneradas(0);
		$note->setMtoOperInafectas(0);
		$note->setMtoIGV($cabecera->igv);
		$note->setTotalImpuestos($cabecera->igv);
		$note->setMtoImpVenta($cabecera->importe_total);
		$note->setCompany($this->getEmisor($data->emisor));

		$items = [];
		foreach ($data->detalle as $row) {
			$item = new SaleDetail();
			$item->setUnidad($row->unidad_medida); //See::UNIDAD_MEDIDA
			$item->setCantidad($row->cantidad);
			$item->setDescripcion($row->descripcion);
			$item->setMtoBaseIgv($row->valor_venta);
			$item->setPorcentajeIgv($row->igv_per); // See::IGV%
			$item->setIgv($row->igv);
			$item->setTipAfeIgv($row->tipo_afectacion_igv); //See::GRAVADO
			$item->setTotalImpuestos($row->igv);
			$item->setMtoValorVenta($row->valor_venta);
			$item->setMtoValorUnitario($row->valor_unitario);
			$item->setMtoPrecioUnitario($row->precio_unitario);
			array_push($items, $item);
		}
		$note->setDetails($items);
		//
		$legends = [];
		$ntlc = new NumberToLetterConverter();
		$letras = strtoupper($ntlc->ValorEnLetras($cabecera->importe_total, $cabecera->tipo_moneda == "PEN" ? See::MONEDA_SOLES : See::MONEDA_DOLARES));
		$legend = new Legend();
		$legend->setCode('1000');
		$legend->setValue($letras);
		array_push($legends, $legend);

		$note->setLegends($legends);

    	return $note;
  }

}
?>

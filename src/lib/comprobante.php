<?php

namespace Lib;

//use Greenter\Model\Sale\Document;
use Greenter\Model\Sale\Invoice;
//use Greenter\Model\Sale\Detraction;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Company\Address;
use Greenter\Model\Client\Client;
//
use Lib\See;
use Lib\Base;
use Lib\NumberToLetterConverter;

class Comprobante extends Base {

	private $esGratuito;

	public function __construct($esGratuito = false){
		$this->esGratuito = $esGratuito;
	}

	public function build($data){
		$template = array(
			"emisor" => null,
			"receptor" => null,
			"cabecera" => null,
			"detalle" => array()
		);
		//
    	$cabecera = $data->cabecera;

		$invoice = new Invoice();
		$invoice->setUblVersion('2.1');
		$invoice->setTipoOperacion($cabecera->tipo_operacion); // Catalog. 51 - '0101'
		// EMISOR
		$invoice->setCompany($this->getEmisor($data->emisor));
		//
		$invoice->setFechaEmision(new \DateTime($cabecera->fecha_emision));
		/*if($cabecera->fecha_vencimiento!=""){
			$invoice->setFecVencimiento(new \DateTime($cabecera->fecha_vencimiento));
		}*/
		$invoice->setTipoDoc($cabecera->tipo_documento); // See::FACTURA
		$invoice->setSerie($cabecera->serie);
		$invoice->setCorrelativo($cabecera->numero);
		$invoice->setTipoMoneda($cabecera->tipo_moneda);
		//RECEPTOR
		$invoice->setClient($this->getReceptor($data->receptor));
		//
		$invoice->setMtoOperGravadas($cabecera->operaciones_gravadas);
		$invoice->setMtoOperExoneradas(0.00);
		$invoice->setMtoOperInafectas(0.00);
		$invoice->setMtoIGV($cabecera->igv);
		$invoice->setTotalImpuestos($cabecera->igv); // 18.00
		$invoice->setValorVenta($cabecera->operaciones_gravadas);
		$invoice->setMtoImpVenta($cabecera->importe_total);
		if($cabecera->servicio > 0){
			$invoice->setMtoOperInafectas($cabecera->servicio);
		}
		// DETALLE
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
		$invoice->setDetails($items);
		//
		$legends = [];
		$ntlc = new NumberToLetterConverter();
		$letras = strtoupper($ntlc->ValorEnLetras($cabecera->importe_total, $cabecera->tipo_moneda == "PEN" ? See::MONEDA_SOLES : See::MONEDA_DOLARES));
		$legend = new Legend();
		$legend->setCode('1000');
		$legend->setValue($letras);
		array_push($legends, $legend);

		$invoice->setLegends($legends);

    return $invoice;
  }

}
?>

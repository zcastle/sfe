<?php

namespace Lib;

use Greenter\Model\Company\Address;
use Greenter\Model\Client\Client;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
//
use Lib\See;
use Lib\NumberToLetterConverter;

class Boleta {

  public function build($data){
    $cabecera = $data->cabecera;

		//Cliente
		$direccion = new Address();
    $direccion->setDepartamento($cabecera->departamento);
    $direccion->setProvincia($cabecera->provincia);
    $direccion->setDistrito($cabecera->distrito);
    $direccion->setDireccion($cabecera->direccion);

		$client = new Client();
		$client->setTipoDoc($cabecera->tipo_cliente);
		//$client->setTipoDoc(See::TIPO_DOCUMENTO_BOLETA);
		$client->setNumDoc($cabecera->ruc);
		$client->setRznSocial($cabecera->razon_social);
		$client->setAddress($direccion);

		$invoice = new Invoice();
		$invoice->setUblVersion('2.1');
    $invoice->setTipoOperacion('0101'); // Catalog. 51
		$invoice->setFechaEmision(new \DateTime($cabecera->fecha_emision));
		$invoice->setTipoDoc(See::BOLETA);
		$invoice->setSerie($cabecera->serie);
		$invoice->setCorrelativo($cabecera->numero);
		$invoice->setTipoMoneda($cabecera->tipo_moneda);
		//if($cabecera->ruc > 0){
			$invoice->setClient($client);
		//}
		$invoice->setMtoOperGravadas($cabecera->operaciones_gravadas);
		//$invoice->setMtoOperExoneradas(0.00);
		//$invoice->setMtoOperInafectas(0.00);
		$invoice->setMtoIGV($cabecera->igv);
		$invoice->setTotalImpuestos($cabecera->igv); // 18.00
		$invoice->setValorVenta($cabecera->operaciones_gravadas); // 100.00
		$invoice->setMtoImpVenta($cabecera->importe_total);
		$invoice->setMtoOperInafectas($cabecera->servicio);
		// DETALLE
		$items = [];
		foreach ($data->detalle as $row) {
			$item = new SaleDetail();
			//$item->setCodProducto('P001');
      $item->setUnidad(See::UNIDAD_MEDIDA);
			$item->setCantidad($row->cantidad);
			$item->setDescripcion($row->descripcion);
			$item->setMtoBaseIgv($row->valor_venta);
			$item->setPorcentajeIgv(See::IGV); // %
			$item->setIgv($row->igv);
			$item->setTipAfeIgv(See::GRAVADO);
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

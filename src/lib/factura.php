<?php

namespace Lib;

use Greenter\Model\Company\Address;
use Greenter\Model\Client\Client;
//use Greenter\Model\Sale\Document;
use Greenter\Model\Sale\Invoice;
//use Greenter\Model\Sale\Detraction;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
//
use Lib\See;
use Lib\NumberToLetterConverter;

class Factura {

  //private $util;
  private $esGratuito;

  public function __construct($esGratuito = false){
		//$this->util = Util::getInstance();
		$this->esGratuito = $esGratuito;
	}

  public function build($data){
    $cabecera = $data->cabecera;

		//Cliente
		$direccion = new Address();
    //$direccion->setUbigueo('150136');
    $direccion->setDepartamento($cabecera->departamento);
    $direccion->setProvincia($cabecera->provincia);
    $direccion->setDistrito($cabecera->distrito);
    $direccion->setDireccion($cabecera->direccion);

		$client = new Client();
		$client->setTipoDoc($cabecera->tipo_cliente);
		//$client->setTipoDoc(See::TIPO_DOCUMENTO_FACTURA);
		$client->setNumDoc($cabecera->ruc);
		$client->setRznSocial($cabecera->razon_social);
		$client->setAddress($direccion);

		$invoice = new Invoice();
		//
		$invoice->setUblVersion('2.1');
    $invoice->setTipoOperacion('0101'); // Catalog. 51
		//
		//$invoice->setCompany($this->util->getCompany());
		//
		$invoice->setFechaEmision(new \DateTime($cabecera->fecha_emision));
		/*if(!$this->esGratuito){
      if($cabecera->fecha_vencimiento!=""){
  			$invoice->setFecVencimiento(new DateTime($cabecera->fecha_vencimiento));
      }
		}*/
		$invoice->setTipoDoc(See::FACTURA);
		$invoice->setSerie($cabecera->serie);
		$invoice->setCorrelativo($cabecera->numero);
		$invoice->setTipoMoneda($cabecera->tipo_moneda);
		$invoice->setClient($client);
		//$invoice->setCompra($cabecera->orden);
		/*if($this->esGratuito){
			$invoice->setMtoOperGravadas(0.00);
			$invoice->setMtoOperExoneradas(0.00);
			$invoice->setMtoOperInafectas(0.00);
			$invoice->setMtoIGV(0.00);
			$invoice->setMtoImpVenta(0.00);
			$invoice->setMtoOperGratuitas($cabecera->importe_gratuito);
		}else{*/
			$invoice->setMtoOperGravadas($cabecera->operaciones_gravadas);
			$invoice->setMtoOperExoneradas(0.00);
			$invoice->setMtoOperInafectas(0.00);
			$invoice->setMtoIGV($cabecera->igv);
			//
			$invoice->setTotalImpuestos($cabecera->igv); // 18.00
			$invoice->setValorVenta($cabecera->operaciones_gravadas); // 100.00
			//
			$invoice->setMtoImpVenta($cabecera->importe_total);
			//
			//$invoice->setMtoBaseOth($cabecera->operaciones_gravadas); // Servicio Base
			//$invoice->setMtoOtrosTributos($cabecera->servicio); // Servicio Valor
			$invoice->setMtoOperInafectas($cabecera->servicio);
			//
		//}
		// GUIAS
		/*$guias = [];
		foreach ($data->guias as $guia) {
			array_push($guias, (new Document())->setTipoDoc(See::GUIA_REMISION)->setNroDoc($guia->numero));
		}
		$invoice->setGuias($guias);*/
		// DETALLE
		$items = [];
		foreach ($data->detalle as $row) {
			$item = new SaleDetail();
			//$item->setCodProducto('P001');
      $item->setUnidad(See::UNIDAD_MEDIDA);
			$item->setCantidad($row->cantidad);
			$item->setDescripcion($row->descripcion);
			//$item->setIgv($this->esGratuito ? 0 : See::IGV);
			//$item->setTipAfeIgv($this->esGratuito ? See::GRATIS : See::GRAVADO);
			$item->setMtoBaseIgv($row->valor_venta); //--
			//$item->setMtoBaseIgv($row->valor_unitario);
			$item->setPorcentajeIgv(See::IGV); // %
			$item->setIgv($row->igv);
			$item->setTipAfeIgv(See::GRAVADO);
			$item->setTotalImpuestos($row->igv);
			/*if($this->esGratuito){
				$item->setMtoValorVenta($row->valor_venta);
				$item->setMtoValorUnitario($row->valor_unitario);
				$item->setMtoPrecioUnitario(0.00);
				$item->setMtoValorGratuito($cabecera->importe_gratuito);
			}else{*/
				$item->setMtoValorVenta($row->valor_venta);
				$item->setMtoValorUnitario($row->valor_unitario);
				$item->setMtoPrecioUnitario($row->precio_unitario);
				//
				//$item->setMtoBaseOth($row->valor_unitario); // Servicio Base
				//$item->setMtoBaseOth($row->valor_venta); // Servicio Base
				//$item->setOtroTributo($row->servicio); // Servicio Valor
				//$item->setPorcentajeOth(10.00); // Servicio Porcentaje
				//
			//}
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
		//
		/*if($this->esGratuito){
			$legend = new Legend();
			$legend->setCode('1002');
			$legend->setValue('TRANSFERENCIA A TITULO GRATUITO');
			array_push($legends, $legend);
		}*/

    //DETRACCION
		/*if($cabecera->detraccion > 0){
      $detraccion = new Detraction();
      $detraccion->setMount($cabecera->detraccion);
      $detraccion->setPercent($cabecera->detraccion_per);
      //$detraccion->setValueRef($cabecera->detraccion_code);
      $invoice->setDetraccion($detraccion);
      //
			$legend = new Legend();
			$legend->setCode('2006');
			$legend->setValue('OPERACION SUJETA A DETRACCION (' . intval($cabecera->detraccion_per) . '%)');
			array_push($legends, $legend);
			$legend = new Legend();
			$legend->setCode('3001');
			$legend->setValue('NUMERO DE CUENTA EN EL BANCO DE LA NACION: ' . See::CTA_DET_BN);
			array_push($legends, $legend);
		}*/

		$invoice->setLegends($legends);

    return $invoice;
  }

}
?>
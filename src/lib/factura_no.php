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

class Factura2 {

  //private $util;
  private $esGratuito;

  public function __construct($esGratuito = false){
		//$this->util = Util::getInstance();
		$this->esGratuito = $esGratuito;
	}

	private function getCompany($emisor){
    $direccion = new Address();
		$direccion->setDepartamento($emisor->departamento)
			->setProvincia($emisor->provincia)
			->setDistrito($emisor->distrito)
			->setDireccion($emisor->direccion)
      ->setUrbanizacion('LAS AMERICAS');
    
		$company = new Company();
		$company->setRuc($emisor->numeroDocId)
			->setRazonSocial($emisor->razonSocial)
			->setNombreComercial($emisor->nombreComercial)
			->setAddress($direccion);

		return $company;
	}
	  
  public function build($data){
		$emisor = (object) $data["EMI"];
		$receptor = (object) $data["REC"];
		$identificacion = (object) $data["IDE"];
		$cabecera = (object) $data["CAB"];
		$detalle = (object) $data["DET"];

		//Cliente
		$direccion = new Address();
		$direccion->setDepartamento($receptor->departamento)
			->setProvincia($receptor->provincia)
			->setDistrito($receptor->distrito)
			->setDireccion($receptor->direccion);

		$client = new Client();
		$client->setTipoDoc($receptor->tipoDocId)
			->setNumDoc($receptor->numeroDocId)
			->setRznSocial($receptor->razonSocial)
			->setAddress($direccion);
		//

		$invoice = new Invoice();
		$invoice->setUblVersion('2.1');
		$invoice->setTipoOperacion($cabecera->tipoOperacion); // Catalog. 51
			//
		$invoice->setCompany($this->getCompany($emisor));
		//
		$invoice->setFechaEmision(new \DateTime($identificacion->fechaEmision . " " . $identificacion->horaEmision));
		/*if(!$this->esGratuito){
      if($cabecera->fecha_vencimiento!=""){
  			$invoice->setFecVencimiento(new DateTime($cabecera->fecha_vencimiento));
      }
		}*/
		$invoice->setTipoDoc($identificacion->codTipoDocumento);
		$numeracion = explode("-", $identificacion->numeracion);
		$invoice->setSerie($numeracion[0]);
		$invoice->setCorrelativo($numeracion[0]);
		$invoice->setTipoMoneda($identificacion->tipoMoneda);
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
			$invoice->setMtoOperGravadas($cabecera->gravadas->totalVentas);
			$invoice->setMtoOperExoneradas(0.00);
			$invoice->setMtoOperInafectas(0.00);
			$invoice->setMtoIGV($cabecera->totalImpuestos->montoImpuesto);
			//
			$invoice->setTotalImpuestos($cabecera->montoTotalImpuestos); // 18.00
			$invoice->setValorVenta($cabecera->gravadas->totalVentas); // 100.00
			//
			$invoice->setMtoImpVenta($cabecera->importe_total); ////
			//
			//$invoice->setMtoBaseOth($cabecera->operaciones_gravadas); // Servicio Base
			//$invoice->setMtoOtrosTributos($cabecera->servicio); // Servicio Valor
			$invoice->setMtoOperInafectas($cabecera->servicio); ////
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
		foreach ($detalle as $row) {
			$item = new SaleDetail();
			//$item->setCodProducto('P001');
      $item->setUnidad($row->unidad);
			$item->setCantidad($row->cantidadItems);
			$item->setDescripcion($row->descripcionProducto);
			//$item->setIgv($this->esGratuito ? 0 : See::IGV);
			//$item->setTipAfeIgv($this->esGratuito ? See::GRATIS : See::GRAVADO);
			$item->setMtoBaseIgv($row->totalImpuestos->montoBase); //--
			//$item->setMtoBaseIgv($row->valor_unitario);
			$item->setPorcentajeIgv(See::IGV); // %
			$item->setIgv($row->totalImpuestos->montoImpuesto);
			$item->setTipAfeIgv($row->totalImpuestos->tipoAfectacion); ////
			$item->setTotalImpuestos($row->montoTotalImpuestos);
			/*if($this->esGratuito){
				$item->setMtoValorVenta($row->valor_venta);
				$item->setMtoValorUnitario($row->valor_unitario);
				$item->setMtoPrecioUnitario(0.00);
				$item->setMtoValorGratuito($cabecera->importe_gratuito);
			}else{*/
				$item->setMtoValorVenta($row->valorVenta);
				$item->setMtoValorUnitario($row->valorUnitario);
				$item->setMtoPrecioUnitario($row->precioVentaUnitario);
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
		//$ntlc = new NumberToLetterConverter();
		//$letras = strtoupper($ntlc->ValorEnLetras($cabecera->importe_total, $identificacion->tipoMoneda == "PEN" ? See::MONEDA_SOLES : See::MONEDA_DOLARES));
		$legend = new Legend();
		$legend->setCode($cabecera->leyenda->codigo);
		$legend->setValue($cabecera->leyenda->descripcion);
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

<?php

namespace Lib;

use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Client\Client;

class Base {

	public function getEmisor($emisor){
		$direccion = new Address();
		$direccion->setUbigueo($emisor->ubigeo);
		$direccion->setDepartamento($emisor->departamento);
		$direccion->setProvincia($emisor->provincia);
		$direccion->setDistrito($emisor->distrito);
		$direccion->setUrbanizacion($emisor->urbanizacion);
		$direccion->setDireccion($emisor->direccion);
		$direccion->setCodLocal($emisor->codigo_asig_sunat);

		$company = new Company();
		$company->setRuc($emisor->ruc);
		$company->setRazonSocial($emisor->razon_social);
		$company->setNombreComercial($emisor->nombre_comercial);
		$company->setEmail($emisor->email);
		$company->setAddress($direccion);

		return $company;
	}

	public function getReceptor($receptor){
		$direccion = new Address();
		$direccion->setUbigueo($receptor->ubigeo);
		$direccion->setDepartamento($receptor->departamento);
		$direccion->setProvincia($receptor->provincia);
		$direccion->setDistrito($receptor->distrito);
		$direccion->setDireccion($receptor->direccion);

		$client = new Client();
		$client->setTipoDoc($receptor->tipo_cliente);
		$client->setNumDoc($receptor->ruc);
		$client->setRznSocial($receptor->razon_social);
		$client->setEmail($receptor->email);
		$client->setAddress($direccion);

		return $client;
	}

}
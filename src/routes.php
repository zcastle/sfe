<?php

use Slim\Http\Request;
use Slim\Http\Response;
//
use Lib\See;
use Lib\Data;
use Lib\SeeUtil;
//
use Ramsey\Uuid\Uuid;
//
use Greenter\XMLSecLibs\Certificate\X509Certificate;
use Greenter\XMLSecLibs\Certificate\X509ContentType;
// Routes

$app->get('/', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

//FACTURAS
$app->get('/verificar_comprobantes[/{tipo_comprobante}]', function (Request $request, Response $response, array $args) {
    $res = array("success" => true, "message" => "");
    $tipo_comprobante = $args['tipo_comprobante'];
    if(empty($tipo_comprobante) || !in_array($tipo_comprobante, array('01', '03'))){
        $res["message"] = "No ha especificado el tipo de compobante: 01: FACTURA, 03 BOLETA";
    }else{
        $data = new Data($this->db, $this->logger);
        $comprobantesIds = $data->getComprobantesId($tipo_comprobante);
        if(count($comprobantesIds) > 0){
            $see = new See();
            $messages = array();
            //$res["comp"] = [];
            foreach($comprobantesIds AS $row){
                $factura = $data->getComprobante($row->id);
                //array_push($res["comp"], $factura);
                $m = $see->enviar($factura, $tipo_comprobante);
                //$data->setEnviado($row->id);
                array_push($messages, $m);
            }
            $res["message"] = $messages;
        }else{
            $res["message"] = "No existen COMPROBANTES para procesar";
        }
    }
    return $response->withJson($res);
});

$app->get("/ver_comprobante/{tipo_comprobante}/{serie}/{numero}", function(Request $request, Response $response, array $args){
    $tipo_comprobante = $args['tipo_comprobante'];
    $serie = $args['serie'];
    $numero = $args['numero'];

    $documento_nombre = See::EMISOR_RUC . "-" . $tipo_comprobante . "-" . $serie  . "-" . $numero . ".xml";

    //console.log(See::RUTA_XML .$documento_nombre);

    if(file_exists(See::RUTA_XML . $documento_nombre)){
        $data = new Data($this->db, $this->logger);
        $id = $data->getComprobanteIdByName($tipo_comprobante, $serie, $numero);
        $documento = $data->getComprobante($id);
        $see = new See();
        $xml = file_get_contents(See::RUTA_XML . $documento_nombre);
        //console.log(See::RUTA_XML);


        list($pdf, $name) = $see->generarPdf($documento, $xml, $tipo_comprobante);

        $response = $response->withHeader( 'Content-type', 'application/pdf' );
        return $response->write($pdf);
    }else{
        return $response->withJson(array("success" => true, "message" => "No se ha generado el archivo XML"));
    }

});

//BOLETAS
$app->get('/enviar_resumen_boletas', function (Request $request, Response $response) {
    $res = array("success" => true, "message" => "");
    $data = new Data($this->db, $this->logger);
    $boletas = $data->getBoletas();
    if(count($boletas->detalle) > 0){
        $fechaHora = new \DateTime();
        $see = new See();
        $m = $see->enviar_resumen_boletas($boletas, $fechaHora);
        $res["message"] = $m;
        if($m["code"] == 0){
            $data->guardarResumen($boletas, $fechaHora, $m["ticket"], $m["nombre"]);
        }

    }else{
        $res["message"] = "No existen BOLETAS que procesar";
    }
    return $response->withJson($res);
});

//BAJA
$app->get('/bajas', function (Request $request, Response $response) {
    $res = array("success" => true, "message" => "");
    $data = new Data($this->db, $this->logger);
    $bajas = $data->getBajas();
    if(count($bajas) > 0){
        
    }else{
        $res["message"] = "No existen COMPROBANTES para dar de BAJA";
    }
    return $response->withJson($res);
});

$app->group("/sfe/v1", function(\Slim\App $app){

    $app->post('/comprobante', function(Request $request, Response $response, $args) {
        $result = array("success" => true, "message" => null, "test" => null);

        $body = $request->getParsedBody();
        $result["test"] = $body["test"];
        $uuid1 = Uuid::uuid1();
        $result["uuid"] = $uuid1->toString();
        $this->logger->info(base64_decode($body["data"]));
        $data = json_decode(base64_decode($body["data"]));

        //$body = json_decode('{"usuario":"20000000000DEMO","clave":"123","test":true,"data":"eyJlbWlzb3IiOnsicnVjIjoiMjA1MTEwNDU1MjYiLCJyYXpvbl9zb2NpYWwiOiJET0dJQSBTLkEuQy4iLCJub21icmVfY29tZXJjaWFsIjoiT3N0ZXJpYSBkaSBHaWFuRnJhbmNvIENhZmZlIiwiZGlyZWNjaW9uIjoiQVYgQU5HQU1PUyBPRVNURTU5OCBNSVJBRkxPUkVTIExJTUEgUEVSVSIsInVyYmFuaXphY2lvbiI6ImxhcyBtYWVyaWNhcyIsImRlcGFydGFtZW50byI6Ii0iLCJwcm92aW5jaWEiOiItIiwiZGlzdHJpdG8iOiItIiwidWJpZ2VvIjoiMDEwMDAwIiwidGVsZWZvbm8iOiIwMSA0NDYgOTUgMTgiLCJlbWFpbCI6ImdpYW5jYWZmZUB5YWhvby5jb20iLCJjb2RpZ29fYXNpZ19zdW5hdCI6IjAwMDEifSwicmVjZXB0b3IiOnsidWJpZ2VvIjoiMDEwMTAxIiwiZGlzdHJpdG8iOiItIiwicHJvdmluY2lhIjoiLSIsImRlcGFydGFtZW50byI6Ii0iLCJkaXJlY2Npb24iOiJjbGllbnRzLmFkZHJlc3MiLCJ0aXBvX2NsaWVudGUiOiI2IiwicnVjIjoiMjAxMDAwNDc2NDEiLCJyYXpvbl9zb2NpYWwiOiJQQVBFTEVSQSBOQUNJT05BTCBTIEEiLCJlbWFpbCI6Ii0ifSwiY2FiZWNlcmEiOnsidGlwb19vcGVyYWNpb24iOiIwMTAxIiwiZmVjaGFfZW1pc2lvbiI6IjIwMTktMDItMTIgMTk6MjQ6MDUiLCJ0aXBvX2RvY3VtZW50byI6IjA3Iiwic2VyaWUiOiJORjAxIiwibnVtZXJvIjoiMSIsInRpcG9fbW9uZWRhIjoiUEVOIiwib3BlcmFjaW9uZXNfZ3JhdmFkYXMiOiIxMDYuNjQiLCJpZ3YiOiIxOS4yMCIsInNlcnZpY2lvIjoiMTAuNjYiLCJpbXBvcnRlX3RvdGFsIjoiMTM2LjUwIiwiZG9jdW1lbnRfcmVsIjpudWxsLCJ0aXBvX2RvY3VtZW50b19hZmVjdGFkbyI6IjAxIiwibnVtZXJvX2RvY3VtZW50b19hZmVjdGFkbyI6bnVsbCwiY29kaWdvX21vdGl2byI6IjAxIiwiZGVzY3JpcGNpb25fbW90aXZvIjoiQU5VTEFDSU9OIERFIExBIE9QRVJBQ0lPTiJ9LCJkZXRhbGxlIjpbXX0="}');
        /*$body = json_decode('{
            "usuario": "20000000000DEMO",
            "clave": "123",
            "test": true,
            "data": "eyJlbWlzb3IiOnsicnVjIjoiMjA1MTEwNDU1MjYiLCJyYXpvbl9zb2NpYWwiOiJET0dJQSBTLkEuQy4iLCJub21icmVfY29tZXJjaWFsIjoiT3N0ZXJpYSBkaSBHaWFuRnJhbmNvIENhZmZlIiwiZGlyZWNjaW9uIjoiQVYgQU5HQU1PUyBPRVNURTU5OCBNSVJBRkxPUkVTIExJTUEgUEVSVSIsInVyYmFuaXphY2lvbiI6ImxhcyBtYWVyaWNhcyIsImRlcGFydGFtZW50byI6Ii0iLCJwcm92aW5jaWEiOiItIiwiZGlzdHJpdG8iOiItIiwidWJpZ2VvIjoiMDEwMDAwIiwidGVsZWZvbm8iOiIwMSA0NDYgOTUgMTgiLCJlbWFpbCI6ImdpYW5jYWZmZUB5YWhvby5jb20iLCJjb2RpZ29fYXNpZ19zdW5hdCI6IjAwMDEifSwicmVjZXB0b3IiOnsidWJpZ2VvIjoiMDEwMTAxIiwiZGlzdHJpdG8iOiItIiwicHJvdmluY2lhIjoiLSIsImRlcGFydGFtZW50byI6Ii0iLCJkaXJlY2Npb24iOiJjbGllbnRzLmFkZHJlc3MiLCJ0aXBvX2NsaWVudGUiOiI2IiwicnVjIjoiMjAxMDAwNDc2NDEiLCJyYXpvbl9zb2NpYWwiOiJQQVBFTEVSQSBOQUNJT05BTCBTIEEiLCJlbWFpbCI6Ii0ifSwiY2FiZWNlcmEiOnsidGlwb19vcGVyYWNpb24iOiIwMTAxIiwiZmVjaGFfZW1pc2lvbiI6IjIwMTktMDItMTIgMTk6MjQ6MDUiLCJ0aXBvX2RvY3VtZW50byI6IjA3Iiwic2VyaWUiOiJGTjAxIiwibnVtZXJvIjoiMSIsInRpcG9fbW9uZWRhIjoiUEVOIiwib3BlcmFjaW9uZXNfZ3JhdmFkYXMiOiIxMDYuNjQiLCJpZ3YiOiIxOS4yMCIsInNlcnZpY2lvIjoiMTAuNjYiLCJpbXBvcnRlX3RvdGFsIjoiMTM2LjUwIiwiZG9jdW1lbnRfcmVsIjoxMDU4NSwidGlwb19kb2N1bWVudG9fYWZlY3RhZG8iOiIwMSIsIm51bWVyb19kb2N1bWVudG9fYWZlY3RhZG8iOiJGQTAzLTE0NzAiLCJjb2RpZ29fbW90aXZvIjoiMDEiLCJkZXNjcmlwY2lvbl9tb3Rpdm8iOiJBTlVMQUNJT04gREUgTEEgT1BFUkFDSU9OIn0sImRldGFsbGUiOlt7InVuaWRhZF9tZWRpZGEiOiJOSVUiLCJjYW50aWRhZCI6IjMuMDAiLCJkZXNjcmlwY2lvbiI6IlNBTiBNQVRFTyBDXC9HIDYwME1MIiwidmFsb3JfdmVudGEiOiIxMi45MCIsImlndl9wZXIiOiIxOC4wMCIsImlndiI6IjIuMzEiLCJ0aXBvX2FmZWN0YWNpb25faWd2IjoiMTAiLCJzZXJ2aWNpb19wZXIiOiIxMC4wMCIsInNlcnZpY2lvIjoiMS4yOSIsInZhbG9yX3VuaXRhcmlvIjoiNC4zMCIsInByZWNpb191bml0YXJpbyI6IjUuNTAifSx7InVuaWRhZF9tZWRpZGEiOiJOSVUiLCJjYW50aWRhZCI6IjQuMDAiLCJkZXNjcmlwY2lvbiI6IlBJWlpBIENVQVRSTyBHVVNUT1MiLCJ2YWxvcl92ZW50YSI6IjkzLjc2IiwiaWd2X3BlciI6IjE4LjAwIiwiaWd2IjoiMTYuODgiLCJ0aXBvX2FmZWN0YWNpb25faWd2IjoiMTAiLCJzZXJ2aWNpb19wZXIiOiIxMC4wMCIsInNlcnZpY2lvIjoiOS4zNiIsInZhbG9yX3VuaXRhcmlvIjoiMjMuNDQiLCJwcmVjaW9fdW5pdGFyaW8iOiIzMC4wMCJ9XX0="
            }');
        $data = json_decode(base64_decode($body->data));*/

        $see = new See($body["usuario"], $body["clave"], $body["test"] = "true");
        //$see = new See();
        $result["message"] = $see->enviar2($data);
        //$see = new See();
        //$result["message"] = $see->enviar2($data);

        return $response->withJson($result);
        //print_r($result["message"]);
    });

    $app->post('/baja', function (Request $request, Response $response) {
        $res = array("success" => true, "message" => "", "test" => null);

        $body = $request->getParsedBody();
        $result["test"] = $body["test"];
        $this->logger->info(base64_decode($body["data"]));
        $dataJson = json_decode(base64_decode($body["data"]));
        $data = (object) $dataJson;

        $see = new See($body["usuario"], $body["clave"], $body["test"] = "true");

        $result["message"] = $see->baja($data);

        return $response->withJson($result);
    });

    $app->get("/ver/{token}/{tipo}", function(Request $request, Response $response, array $args){
        $token = $args['token'];
        $tipo = $args['tipo'];
    
        $documento_nombre = "";
        $util = new SeeUtil();

        if($tipo == "pdf"){
            $data = $util->getPdf($documento_nombre);
            $response = $response->withHeader("Content-type", "application/pdf")
                                ->withHeader("Content-Disposition", "inline; filename='" . $documento_nombre . "'")
                                ->withHeader("Content-Transfer-Encoding", "binary")
                                ->withHeader("Content-Length", strlen($data));
        }else if($tipo == "xml"){
            $data = $util->getXml($documento_nombre);
            $response = $response->withHeader("Content-type", "text/xml")
                                ->withHeader("Content-Disposition", "inline; filename='" . $documento_nombre . "'");

        }else if($tipo == "cdr"){
            $data = $util->getCdr($documento_nombre);
            $response = $response->withHeader("Content-type", "application/zip")
                                ->withHeader("Content-Disposition", "inline; filename='" . $documento_nombre . "'")
                                ->withHeader("Content-Transfer-Encoding", "binary")
                                ->withHeader("Content-Length", strlen($data));
        }

        return $response->write($data);    
    });


});

/*$app->get('/convert', function (Request $request, Response $response, array $args) {
    $pfx = file_get_contents(__DIR__ . '/20511045526.pfx');
    $password = 'SOLARIS00++';

    $certificate = new X509Certificate($pfx, $password);
    $pem = $certificate->export(X509ContentType::PEM);
        
    file_put_contents(__DIR__ . '/certificate.pem', $pem);

    return $response->withJson(array("success" => true));
});*/
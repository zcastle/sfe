<?PHP

namespace Lib;

use Greenter\Ws\Services\SunatEndpoints;
//
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\DocumentInterface;
//use Greenter\Ubl\UblValidator;
use Greenter\Report\HtmlReport;
use Greenter\Report\PdfReport;
//
use Lib\Comprobante;
use Lib\Nota;
use Lib\Baja;
use Lib\Factura;
use Lib\Boleta;
use Lib\ResumenDiario;
//
use Lib\SeeUtil;
use Lib\Pdf;

class See {

    const EMISOR_RUC = "20511045526";
    const TIPO_DOCUMENTO_FACTURA = 6; // RUC
    const TIPO_DOCUMENTO_BOLETA = 1; // DNI
    //
    const FACTURA = '01';
    const BOLETA = '03';
	const NOTA_CREDITO = '07';
    const GUIA_REMISION = '09';
    //
	const BAJA = "RA";
	//
	const UNIDAD_MEDIDA = 'NIU';
	const UNIDAD_MEDIDA_SERVICIO = 'ZZ';
	//
	const IGV = 18.00;
	//
	const GRAVADO = 10; // "Gravado - Operación Onerosa"
	const PREMIO = 11; // "Gravado – Retiro por premio"
	const DONACION = 12; // "Gravado – Retiro por donación"
	const GRATUITO = 21; // "Exonerado – Transferencia Gratuita",
    const GRATIS = See::DONACION;
    //
    const MONEDA_SOLES = "Soles ";
	const MONEDA_DOLARES = "Dolares Americanos ";
    //
    const RUTA_XML = __DIR__ . "/../xml/";
    //
    private $see;

    public function __construct($usuario = "", $clave = "", $test = true) {
        $this->see = new \Greenter\See();

        // TEST
       // if($usuario == "" || $clave == "" || $test){
            $this->see->setService(SunatEndpoints::FE_BETA);
            $this->see->setCertificate(file_get_contents(__DIR__ . "/certificate.pem"));
            $this->see->setCredentials('20000000001MODDATOS', 'moddatos');
        //}
        
        // PRODUCCION
        //if($usuario != "" && $clave != "" && !$test){
            /*$this->see->setService(SunatEndpoints::FE_PRODUCCION);
            $this->see->setCertificate(file_get_contents(__DIR__ . "/dogia2019.pem"));
            $this->see->setCredentials('20511045526FE082018', 'SOLARIS00');*/

            /*$this->see->setCertificate(file_get_contents(__DIR__ . "/" . $usuario . ".pem"));
            $this->see->setCredentials($usuario, $clave);*/
            
        //}
        //
        //$this->see->setService("https://www.escondatagate.net/wsValidator_2_1/ol-ti-itcpe/billService");
        //$this->see->setCertificate(file_get_contents(__DIR__ . "/dogia2019.pem"));
        //$this->see->setCredentials('dogia02', 'Dogia2018*');
        //$this->see->setCredentials('FE082018', 'SOLARIS00');
    }

    private function getCompany(){
        $direccion = new Address();
        $direccion->setUbigueo('150122')
            ->setDepartamento('LIMA')
            ->setProvincia('LIMA')
            ->setDistrito('MIRAFLORES')
            ->setUrbanizacion('LAS AMERICAS')
            ->setDireccion('AV. ANGAMOS OESTE 598');
    
        $cia = new Company();
        $cia->setRuc(See::EMISOR_RUC)
            ->setRazonSocial('DOGIA S.A.C.')
            ->setNombreComercial('GIANFRANCO CAFFE')
            ->setAddress($direccion);
    
        return $cia;
      }

    public function enviar($data, $tipo_comprobante){
        if($tipo_comprobante == See::FACTURA){
            $builder = new Factura();
        } else if($tipo_comprobante == See::BOLETA){
            $builder = new Boleta();
        } else if($tipo_comprobante == See::NOTA_CREDITO){
            //$builder = new Boleta();
        }

        $document = $builder->build($data);
        $document->setCompany($this->getCompany());

        $this->writeXml($document, $this->see->getXmlSigned($document));
        //

        //
        $result = $this->see->send($document);
        //$this->writeXml($document, $this->see->getFactory()->getLastXml());
        
        if ($result->isSuccess()) {
            $this->writeCdr($document, $result->getCdrZip());
            $cdrResponse = $result->getCdrResponse();
            return array("code" => $cdrResponse->getCode(), "descripcion" => $cdrResponse->getDescription());
        } else {
            $error = $result->getError();
            return array("code" => $error->getCode(), "descripcion" => $error->getMessage());
        }
    }

    public function enviar2($data){
        $response = array("code" => 0, "descripcion" => null);
        $tipo_documento = $data->cabecera->tipo_documento;
        if($tipo_documento == See::FACTURA || $tipo_documento == See::BOLETA){
            $builder = new Comprobante();
        } else if($tipo_documento == See::NOTA_CREDITO){
            $builder = new Nota();
        }

        $document = $builder->build($data);

        //return $document;

        $util = new SeeUtil($document->getCompany()->getRuc());
        $util->writeXml($document->getName(), $this->see->getXmlSigned($document));
        try{
            $pdf = new Pdf();
            $data = $pdf->get($document);
            $util->writePdf($document->getName(), $data);
        } catch (Exception $e) {
            $response["error_pdf"] = $e->getMessage();
        }
        
        $result = $this->see->send($document);
        
        if ($result->isSuccess()) {
            $util->writeCdr($document->getName(), $result->getCdrZip());
            $cdr = $result->getCdrResponse();
            $response["code"] = $cdr->getCode();
            $response["descripcion"] = $cdr->getDescription();
            $response["observaciones"] = $cdr->getNotes();
        } else {
            $error = $result->getError();
            $response["code"] = $error->getCode();
            $response["descripcion"] = $error->getMessage();
        }

        return $response;
    }

    public function baja($data){
        $builder = new Baja();
        $document = $builder->build($data);

        $this->util->writeXml($document->getName(), $this->see->getXmlSigned($document));
        
        $result = $this->see->send($document);

        if ($result->isSuccess()) {
            $status = $this->see->getStatus($result->getTicket());
            if ($status->isSuccess()) {
                $util->util->writeCdr($document->getName(), $status->getCdrZip());
                $cdr = $status->getCdrResponse();
                return array("code" => $cdr->getCode(), "descripcion" => $cdr->getDescription());
            } else {
                $error = $status->getError();
                return array("code" => $error->getCode(), "descripcion" => $error->getMessage());
            }
        } else {
            $error = $result->getError();
            return array("code" => $error->getCode(), "descripcion" => $error->getMessage());
        }
    }

    public function enviar_resumen_boletas($data, $fechaHora){
        $builder = new ResumenDiario();

        $resumen = $builder->build($data, $fechaHora);
        $resumen->setCompany($this->getCompany());

        $result = $this->see->send($resumen);
        $this->writeXml($resumen, $this->see->getFactory()->getLastXml());

        if ($result->isSuccess()) {
            $ticket = $result->getTicket();
            $status = $this->see->getStatus($ticket);
            if ($status->isSuccess()) {
                $this->writeCdr($resumen, $status->getCdrZip());
                $cdrResponse = $status->getCdrResponse();
                return array("code" => $cdrResponse->getCode(), "descripcion" => $cdrResponse->getDescription(), "ticket" => $ticket, "nombre" => $resumen->getName());
            } else {
                $error = $status->getError();
                return array("code" => $error->getCode(), "descripcion" => $error->getMessage(), "place" => "status");
            }
        } else {
            $error = $result->getError();
            return array("code" => $error->getCode(), "descripcion" => $error->getMessage(), "place" => "send");
        }
    }

    public function generarPdf($data, $xml, $tipo_comprobante){
        if($tipo_comprobante == See::FACTURA){
            $builder = new Factura();
        } else if($tipo_comprobante == See::BOLETA){
            $builder = new Boleta();
        }

        $document = $builder->build($data);
        $document->setCompany($this->getCompany());

        $html = new HtmlReport('', [
            'cache' => __DIR__ . '/../cache',
            'strict_variables' => true,
        ]);
        $template = $this->getTemplate($document);
        if ($template) {
            $html->setTemplate($template);
        }

        $render = new PdfReport($html);
        $render->setOptions( [
            'no-outline',
            'viewport-size' => '1280x1024',
            'page-width' => '21cm',
            'page-height' => '29.7cm',
            'footer-html' => __DIR__.'/../resources/footer.html',
        ]);
        $binPath = self::getPathBin();
        if (file_exists($binPath)) {
            $render->setBinPath($binPath);
        }
        $hash = (new \Greenter\Report\XmlUtils())->getHashSign($xml);
        $params = self::getParametersPdf();
        $params['system']['hash'] = $hash;
        $params['user']['footer'] = '<div>consulte en <a href="https://www.GianFracoCaffe.com">www.GianFrancoCaffe.com</a></div>';

        //<a href="https://github.com/giansalex/sufel">www.GianFrancoCaffe.com</a></div>;

        $pdf = $render->render($document, $params);
        /*if ($pdf === false) {
            $error = $render->getExporter()->getError();
            echo 'Error: '.$error;
            exit();
        }*/
        // Write html
        //$this->writeFile($document->getName().'.html', $render->getHtml());
        //file_put_contents(__DIR__ . "/../pdf/" . $document->getName().'.html', $render->getHtml());
        file_put_contents(__DIR__ . "/../pdf/" . $document->getName().'.pdf', $pdf);
        return [$pdf, $document->getName()];
    }

    private function getTemplate($document){
        $className = get_class($document);
        switch ($className) {
            case \Greenter\Model\Retention\Retention::class:
                $name = 'retention';
                break;
            case \Greenter\Model\Perception\Perception::class:
                $name = 'perception';
                break;
            case \Greenter\Model\Despatch\Despatch::class:
                $name = 'despatch';
                break;
            case \Greenter\Model\Summary\Summary::class:
                $name = 'summary';
                break;
            case \Greenter\Model\Voided\Voided::class:
            case \Greenter\Model\Voided\Reversion::class:
                $name = 'voided';
                break;
            default:
                return '';
        }
        return $name.'.html.twig';
    }

    /*private function getHash(DocumentInterface $document){
        $xml = $this->see->getXmlSigned($document);
        $hash = (new \Greenter\Report\XmlUtils())->getHashSign($xml);
        return $hash;
    }*/

    public static function getPathBin(){
        $path = __DIR__ . '/../../vendor/bin/wkhtmltopdf';
        if (self::isWindows()) {
            $path .= '.exe';
        }
        return $path;
    }

    public static function isWindows(){
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }

    private static function getParametersPdf() {
        $logo = file_get_contents(__DIR__ . '/../resources/logo.png');
        return [
            'system' => [
                'logo' => $logo,
                'hash' => ''
            ],
            'user' => [
                'resolucion' => '-',
                'header' => 'Telf: <b>(01) 446-9518</b>',
                'extras' => [
                    ['name' => 'CONDICION DE PAGO', 'value' => 'Efectivo'],
                    ['name' => 'VENDEDOR', 'value' => 'CAJERO CAJERO'],
                ],
            ]
        ];
    }

    private function writeXml(DocumentInterface $document, $xml){
        $file = See::RUTA_XML . $document->getName() . '.xml';
        file_put_contents($file, $xml);
    }

    private function writeCdr(DocumentInterface $document, $zip){
        $file = __DIR__ . "/../cdr/R-" . $document->getName() . '.zip';
        file_put_contents($file, $zip);
    }

}
<?php

namespace Lib;

use Greenter\Model\DocumentInterface;
use Greenter\Report\HtmlReport;
use Greenter\Report\PdfReport;

use Lib\SeeUtil;

class Pdf {

    public function __construct(){}
    
    public function get(DocumentInterface $document){
        $util = new SeeUtil($document->getCompany()->getRuc());
        $html = new HtmlReport('', [
            //'cache' => __DIR__ . '/../cache',
            'strict_variables' => true
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
            //'footer-html' => __DIR__.'/../resources/footer.html',
        ]);
        
        $binPath = self::getPathBin();
        if (file_exists($binPath)) {
            $render->setBinPath($binPath);
        }
        $hash = (new \Greenter\Report\XmlUtils())->getHashSign($util->getXml($document->getName()));
        $params = $this->getParametersPdf();
        $params['system']['hash'] = $hash;
        $params['user']['footer'] = '<div>consulte en <a href="https://www.GianFracoCaffe.com">www.GianFrancoCaffe.com</a></div>';

        $pdf = $render->render($document, $params);
        if ($pdf === false) {
            //$error = $render->getExporter()->getError();
            //echo 'Error: '.$error;
            throw new Exception($render->getExporter()->getError());
        } else {
            // Write html
            //$this->writeFile($document->getName().'.html', $render->getHtml());
            //file_put_contents(__DIR__ . "/../pdf/" . $document->getName().'.html', $render->getHtml());
            //file_put_contents($util->path["PDF"] . $document->getName() . '.pdf', $pdf);
            //return [$pdf, $document->getName()];
            return $pdf;
        }
        
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
                return null;
        }
        //return $name.'.html.twig';
    }

    public function getPathBin(){
        $path = __DIR__ . '/../../vendor/bin/wkhtmltopdf';
        if ($this->isWindows()) {
            $path .= '.exe';
        }
        return $path;
    }

    public function isWindows(){
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }

    private function getParametersPdf() {
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
}
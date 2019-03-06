<?PHP

namespace Lib;

use Greenter\Report\HtmlReport;
use Greenter\Report\PdfReport;


class SeeUtil {

    const MAIN_PATH = "/var/www/html/sfe_archivos/";
    public $path = array();

    public function __construct($ruc) {
        $tmpPath = SeeUtil::MAIN_PATH . $ruc;
        if(!file_exists($tmpPath)){
            mkdir($tmpPath, 0777, true);
        }
        foreach (array("XML", "CDR", "PDF") AS $valor) {
            $folder = $tmpPath . "/" . $valor;
            if(!file_exists($folder)){
                mkdir($folder, 0777);
            }
            $this->path[$valor] = $folder . "/";
        }
    }

    public function getXml($name){
        $file = $this->path["XML"] . $name . '.xml';
        return file_get_contents($file);
    }

    public function writeXml($name, $data){
        $file = $this->path["XML"] . $name . '.xml';
        file_put_contents($file, $data);
    }

    public function writeCdr($name, $data){
        $file = $this->path["CDR"] . "R-" . $name . '.zip';
        file_put_contents($file, $data);
    }

    public function getCdr($name){
        $file = $this->path["CDR"] . "R-" . $name . '.zip';
        return file_get_contents($file);
    }

    public function writePdf($name, $data){
        $file = $this->path["PDF"] . $name . '.pdf';
        file_put_contents($file, $data);
    }

    public function getPdf($name){
        $file = $this->path["PDF"] . $name . '.pdf';
        return file_get_contents($file);
    }

}

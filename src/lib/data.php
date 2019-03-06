<?PHP

namespace Lib;

class Data {

  private $db;
  private $logger;

  public function __construct($db, $logger){
    $this->db = $db;
    $this->logger = $logger;
  }

  public function setEnviado($id){
    return $this->db->table('sales')->where("id", $id)->update(['estado_sunat' => 1]);
  }
  
  public function getComprobantesId($tipo_comprobante){
    $table = $this->db->table('sales')->select("id");
    if($tipo_comprobante == See::FACTURA){
      $table->where("document", "FACTURA");
    } else if($tipo_comprobante == See::BOLETA){
      $table->where("document", "BOLETA");
    }

    return $table->where("estado_sunat", 0)->get();
  }

  public function getComprobanteIdByName($tipo_comprobante, $serie, $numero){
    $table = $this->db->table('sales')->select("id");
    if($tipo_comprobante == See::FACTURA){
      $table->where("document", "FACTURA");
    } else if($tipo_comprobante == See::BOLETA){
      $table->where("document", "BOLETA");
    }

    //->where("serie", $serie)
    return $table->where("number", $numero)->get()[0]->id;
  }

  public function getComprobante($id){
    $data = (object) array();
    $cabecera = $this->db->table('sales')
                        ->select("sales.id", "sales.datetime_sale",
                          $this->db::raw("IFNULL(sales.ruc, '00000000') AS ruc"), 
                          $this->db::raw("IFNULL(TRIM(sales.name), 'VARIOS') AS name"), 
                          "sales.serie", "sales.number", "sales.base", "sales.igv", "sales.servicio", "sales.total", "sales.client_type", 
                          "salesd.product_cant", 
                          $this->db::raw("TRIM(salesd.product_name) AS product_name"), 
                          $this->db::raw("'PEN' AS moneda"),
                          $this->db::raw("ROUND(salesd.tbase, 2) AS valor_venta"), 
                          $this->db::raw("ROUND(salesd.tigv, 2) AS product_igv"), 
                          $this->db::raw("ROUND(salesd.tservice, 2) AS product_service"), 
                          $this->db::raw("ROUND(salesd.product_base, 2) AS product_base"), 
                          $this->db::raw("ROUND(salesd.product_price, 2) AS product_price"))
                        ->join('salesd', 'sales.id', '=', 'salesd.sales_id')
                        ->where("sales.id", $id)
                        ->get();

                        /*
                        $this->db::raw("ROUND(salesd.tbase, 2) AS valor_venta"), 
                          $this->db::raw("ROUND(salesd.tigv, 2) AS product_igv"), 
                          $this->db::raw("ROUND(salesd.tservice, 2) AS product_service"), 
                        */
                        
    $data->cabecera = (object) array(
      "id" => $cabecera[0]->id,
      "fecha_emision" => $cabecera[0]->datetime_sale,
      "ruc" => $cabecera[0]->ruc,
      "razon_social" => $cabecera[0]->name,
      "tipo_cliente" => $cabecera[0]->client_type,
      "serie" => $cabecera[0]->serie,
      "numero" => $cabecera[0]->number,
      "tipo_moneda" => $cabecera[0]->moneda,
      "operaciones_gravadas" => $cabecera[0]->base,
      "igv" => $cabecera[0]->igv,
      "servicio" => $cabecera[0]->servicio,
      "importe_total" => $cabecera[0]->total
    );

    $detalle = array();
    foreach($cabecera AS $row){
      array_push($detalle, (object) array(
        "cantidad" => $row->product_cant,
        "descripcion" => $row->product_name,
        "valor_venta" => $row->valor_venta,
        "igv" => $row->product_igv,
        "servicio" => $row->product_service,
        "valor_unitario" => $row->product_base,
        "precio_unitario" => $row->product_price
      ));
    }
    $data->detalle = $detalle;

    return $data;
  }

  public function getBoletas(){
    $data = (object) array();

    $cabecera = $this->db->table('secuencia')->select("numero AS correlativo")->where("documento", "RESUMEN_DIARIO")->where("periodo", date("Ymd"))->get();
    if(count($cabecera) == 0){
      $this->db->table('secuencia')->where("documento", "RESUMEN_DIARIO")->update(["numero" => 1, "periodo" => date("Ymd")]);
      //
      $cabecera = $this->db->table('secuencia')->select("numero AS correlativo")->where("documento", "RESUMEN_DIARIO")->where("periodo", date("Ymd"))->get();
    }
    
    $data->cabecera = $cabecera[0];

    $detalle = $this->db->table('sales')->select("id", "datetime_sale AS fecha_emision", $this->db::raw("IFNULL(sales.ruc, '00000000') AS ruc"), 
                $this->db::raw("IFNULL(TRIM(sales.name), 'VARIOS') AS razon_social"), "serie", "number AS numero", 
                "base AS operaciones_gravadas", "igv", "servicio", "total AS importe_total", "client_type AS tipo_cliente", $this->db::raw("'PEN' AS tipo_moneda"))
                ->where("document", "BOLETA")->where("resumen_sunat_id", 0)->skip(0)->take(100)->get();
    $data->detalle = $detalle;

    return $data;
  }

  public function guardarResumen($boletas, $fechaHora, $ticket, $nombre){

    $id = $this->db->table('resumen_sunat')->insertGetId([
      "fecha" => $fechaHora,
      "tk_sunat" => $ticket,
      "nro_send" => $nombre
    ]);

    foreach($boletas->detalle AS $row){
      $this->db->table('sales')->where("id", $row->id)->update(['resumen_sunat_id' => $id]);
    }

    $this->db->table('secuencia')->where("documento", "RESUMEN_DIARIO")->increment('numero');

    //return $id;
  }

}

?>

<?php

/* invoice.html.twig */
class __TwigTemplate_337d06b33714d8991a821cf481eabe8ebdd8877daa19a1cf4861d593f4776d46 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<html>
<head>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
    <style type=\"text/css\">
        ";
        // line 5
        $this->loadTemplate("assets/style.css", "invoice.html.twig", 5)->display($context);
        // line 6
        echo "    </style>
</head>
<body class=\"white-bg\">
";
        // line 9
        $context["cp"] = $this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "company", array());
        // line 10
        $context["isNota"] = twig_in_filter($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "tipoDoc", array()), array(0 => "07", 1 => "08"));
        // line 11
        $context["isAnticipo"] = ($this->getAttribute(($context["doc"] ?? null), "totalAnticipos", array(), "any", true, true) && ($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "totalAnticipos", array()) > 0));
        // line 12
        $context["name"] = $this->env->getRuntime('Greenter\Report\Filter\DocumentFilter')->getValueCatalog($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "tipoDoc", array()), "01");
        // line 13
        echo "<table width=\"100%\">
    <tbody><tr>
        <td style=\"padding:30px; !important\">
            <table width=\"100%\" height=\"200px\" border=\"0\" aling=\"center\" cellpadding=\"0\" cellspacing=\"0\">
                <tbody><tr>
                    <td width=\"50%\" height=\"90\" align=\"center\">
                        <span><img src=\"";
        // line 19
        echo $this->env->getRuntime('Greenter\Report\Filter\ImageFilter')->toBase64($this->getAttribute($this->getAttribute(($context["params"] ?? $this->getContext($context, "params")), "system", array()), "logo", array()));
        echo "\" height=\"80\" style=\"text-align:center\" border=\"0\"></span>
                    </td>
                    <td width=\"5%\" height=\"40\" align=\"center\"></td>
                    <td width=\"45%\" rowspan=\"2\" valign=\"bottom\" style=\"padding-left:0\">
                        <div class=\"tabla_borde\">
                            <table width=\"100%\" border=\"0\" height=\"200\" cellpadding=\"6\" cellspacing=\"0\">
                                <tbody><tr>
                                    <td align=\"center\">
                                        <span style=\"font-family:Tahoma, Geneva, sans-serif; font-size:29px\" text-align=\"center\">";
        // line 27
        echo ($context["name"] ?? $this->getContext($context, "name"));
        echo "</span>
                                        <br>
                                        <span style=\"font-family:Tahoma, Geneva, sans-serif; font-size:19px\" text-align=\"center\">E L E C T R Ó N I C A</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align=\"center\">
                                        &nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <td align=\"center\">
                                        <span style=\"font-size:15px\" text-align=\"center\">R.U.C.: ";
        // line 39
        echo $this->getAttribute(($context["cp"] ?? $this->getContext($context, "cp")), "ruc", array());
        echo "</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align=\"center\">
                                        No.: <span>";
        // line 44
        echo $this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "serie", array());
        echo "-";
        echo $this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "correlativo", array());
        echo "</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align=\"center\">
                                        Nro. R.I. Emisor: <span>";
        // line 49
        echo $this->getAttribute($this->getAttribute(($context["params"] ?? $this->getContext($context, "params")), "user", array()), "resolucion", array());
        echo "</span>
                                    </td>
                                </tr>
                                </tbody></table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td valign=\"bottom\" style=\"padding-left:0\">
                        <div class=\"tabla_borde\">
                            <table width=\"96%\" height=\"100%\" border=\"0\" border-radius=\"\" cellpadding=\"9\" cellspacing=\"0\">
                                <tbody><tr>
                                    <td align=\"center\">
                                        <strong><span style=\"font-size:15px\">";
        // line 62
        echo $this->getAttribute(($context["cp"] ?? $this->getContext($context, "cp")), "razonSocial", array());
        echo "</span></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td align=\"left\">
                                        <strong>Dirección: </strong>";
        // line 67
        echo $this->getAttribute($this->getAttribute(($context["cp"] ?? $this->getContext($context, "cp")), "address", array()), "direccion", array());
        echo "
                                    </td>
                                </tr>
                                <tr>
                                    <td align=\"left\">
                                        ";
        // line 72
        echo $this->getAttribute($this->getAttribute(($context["params"] ?? $this->getContext($context, "params")), "user", array()), "header", array());
        echo "
                                    </td>
                                </tr>
                                </tbody></table>
                        </div>
                    </td>
                </tr>
                </tbody></table>
            <div class=\"tabla_borde\">
                ";
        // line 81
        $context["cl"] = $this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "client", array());
        // line 82
        echo "                <table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\">
                    <tbody><tr>
                        <td width=\"60%\" align=\"left\"><strong>Razón Social:</strong>  ";
        // line 84
        echo $this->getAttribute(($context["cl"] ?? $this->getContext($context, "cl")), "rznSocial", array());
        echo "</td>
                        <td width=\"40%\" align=\"left\"><strong>";
        // line 85
        echo $this->env->getRuntime('Greenter\Report\Filter\DocumentFilter')->getValueCatalog($this->getAttribute(($context["cl"] ?? $this->getContext($context, "cl")), "tipoDoc", array()), "06");
        echo ":</strong>  ";
        echo $this->getAttribute(($context["cl"] ?? $this->getContext($context, "cl")), "numDoc", array());
        echo "</td>
                    </tr>
                    <tr>
                        <td width=\"60%\" align=\"left\">
                            <strong>Fecha Emisión: </strong>  ";
        // line 89
        echo twig_date_format_filter($this->env, $this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "fechaEmision", array()), "d/m/Y");
        echo "
                            ";
        // line 90
        if (($this->getAttribute(($context["doc"] ?? null), "fecVencimiento", array(), "any", true, true) && $this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "fecVencimiento", array()))) {
            // line 91
            echo "                            <br><br><strong>Fecha Vencimiento: </strong>  ";
            echo twig_date_format_filter($this->env, $this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "fecVencimiento", array()), "d/m/Y");
            echo "
                            ";
        }
        // line 93
        echo "                        </td>
                        <td width=\"40%\" align=\"left\"><strong>Dirección: </strong>  ";
        // line 94
        if ($this->getAttribute(($context["cl"] ?? $this->getContext($context, "cl")), "address", array())) {
            echo $this->getAttribute($this->getAttribute(($context["cl"] ?? $this->getContext($context, "cl")), "address", array()), "direccion", array());
        }
        echo "</td>
                    </tr>
                    ";
        // line 96
        if (($context["isNota"] ?? $this->getContext($context, "isNota"))) {
            // line 97
            echo "                    <tr>
                        <td width=\"60%\" align=\"left\"><strong>Tipo Doc. Ref.: </strong>  ";
            // line 98
            echo $this->env->getRuntime('Greenter\Report\Filter\DocumentFilter')->getValueCatalog($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "tipDocAfectado", array()), "01");
            echo "</td>
                        <td width=\"40%\" align=\"left\"><strong>Documento Ref.: </strong>  ";
            // line 99
            echo $this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "numDocfectado", array());
            echo "</td>
                    </tr>
                    ";
        }
        // line 102
        echo "                    <tr>
                        <td width=\"60%\" align=\"left\"><strong>Tipo Moneda: </strong>  ";
        // line 103
        echo $this->env->getRuntime('Greenter\Report\Filter\DocumentFilter')->getValueCatalog($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "tipoMoneda", array()), "021");
        echo " </td>
                        <td width=\"40%\" align=\"left\">";
        // line 104
        if (($this->getAttribute(($context["doc"] ?? null), "compra", array(), "any", true, true) && $this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "compra", array()))) {
            echo "<strong>O/C: </strong>  ";
            echo $this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "compra", array());
        }
        echo "</td>
                    </tr>
                    ";
        // line 106
        if ($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "guias", array())) {
            // line 107
            echo "                    <tr>
                        <td width=\"60%\" align=\"left\"><strong>Guias: </strong>
                        ";
            // line 109
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "guias", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["guia"]) {
                // line 110
                echo "                            ";
                echo $this->getAttribute($context["guia"], "nroDoc", array());
                echo "&nbsp;&nbsp;
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['guia'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 111
            echo "</td>
                        <td width=\"40%\"></td>
                    </tr>
                    ";
        }
        // line 115
        echo "                    </tbody></table>
            </div><br>
            ";
        // line 117
        $context["moneda"] = $this->env->getRuntime('Greenter\Report\Filter\DocumentFilter')->getValueCatalog($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "tipoMoneda", array()), "02");
        // line 118
        echo "            <div class=\"tabla_borde\">
                <table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\">
                    <tbody>
                        <tr>
                            <td align=\"center\" class=\"bold\">Cantidad</td>
                            <td align=\"center\" class=\"bold\">Código</td>
                            <td align=\"center\" class=\"bold\">Descripción</td>
                            <td align=\"center\" class=\"bold\">Valor Unitario</td>
                            <td align=\"center\" class=\"bold\">Valor Total</td>
                        </tr>
                        ";
        // line 128
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "details", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["det"]) {
            // line 129
            echo "                        <tr class=\"border_top\">
                            <td align=\"center\">
                                ";
            // line 131
            echo $this->env->getRuntime('Greenter\Report\Filter\FormatFilter')->number($this->getAttribute($context["det"], "cantidad", array()));
            echo "
                                ";
            // line 132
            echo $this->getAttribute($context["det"], "unidad", array());
            echo "
                            </td>
                            <td align=\"center\">
                                ";
            // line 135
            echo $this->getAttribute($context["det"], "codProducto", array());
            echo "
                            </td>
                            <td align=\"center\" width=\"300px\">
                                <span>";
            // line 138
            echo $this->getAttribute($context["det"], "descripcion", array());
            echo "</span><br>
                            </td>
                            <td align=\"center\">
                                ";
            // line 141
            echo ($context["moneda"] ?? $this->getContext($context, "moneda"));
            echo "
                                ";
            // line 142
            echo $this->env->getRuntime('Greenter\Report\Filter\FormatFilter')->number($this->getAttribute($context["det"], "mtoValorUnitario", array()));
            echo "
                            </td>
                            <td align=\"center\">
                                ";
            // line 145
            echo ($context["moneda"] ?? $this->getContext($context, "moneda"));
            echo "
                                ";
            // line 146
            echo $this->env->getRuntime('Greenter\Report\Filter\FormatFilter')->number($this->getAttribute($context["det"], "mtoValorVenta", array()));
            echo "
                            </td>
                        </tr>
                        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['det'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 150
        echo "                    </tbody>
                </table></div>
            <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
                <tbody><tr>
                    <td width=\"50%\" valign=\"top\">
                        <table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\">
                            <tbody>
                            <tr>
                                <td colspan=\"4\">
                                    <br>
                                    <br>
                                    <span style=\"font-family:Tahoma, Geneva, sans-serif; font-size:12px\" text-align=\"center\"><strong>";
        // line 161
        echo $this->env->getRuntime('Greenter\Report\Filter\ResolveFilter')->getValueLegend($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "legends", array()), "1000");
        echo "</strong></span>
                                    <br>
                                    <br>
                                    <strong>Información Adicional</strong>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\">
                            <tbody>
                            <tr class=\"border_top\">
                                <td width=\"30%\" style=\"font-size: 10px;\">
                                    LEYENDA:
                                </td>
                                <td width=\"70%\" style=\"font-size: 10px;\">
                                    <p>
                                        ";
        // line 177
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "legends", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["leg"]) {
            // line 178
            echo "                                        ";
            if (($this->getAttribute($context["leg"], "code", array()) != "1000")) {
                // line 179
                echo "                                            ";
                echo $this->getAttribute($context["leg"], "value", array());
                echo "<br>
                                        ";
            }
            // line 181
            echo "                                        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['leg'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 182
        echo "                                    </p>
                                </td>
                            </tr>
                            ";
        // line 185
        if (($context["isNota"] ?? $this->getContext($context, "isNota"))) {
            // line 186
            echo "                            <tr class=\"border_top\">
                                <td width=\"30%\" style=\"font-size: 10px;\">
                                    MOTIVO DE EMISIÓN:
                                </td>
                                <td width=\"70%\" style=\"font-size: 10px;\">
                                    ";
            // line 191
            echo $this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "desMotivo", array());
            echo "
                                </td>
                            </tr>
                            ";
        }
        // line 195
        echo "                            ";
        if ($this->getAttribute($this->getAttribute(($context["params"] ?? null), "user", array(), "any", false, true), "extras", array(), "any", true, true)) {
            // line 196
            echo "                                ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["params"] ?? $this->getContext($context, "params")), "user", array()), "extras", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 197
                echo "                                    <tr class=\"border_top\">
                                        <td width=\"30%\" style=\"font-size: 10px;\">
                                            ";
                // line 199
                echo twig_upper_filter($this->env, $this->getAttribute($context["item"], "name", array()));
                echo ":
                                        </td>
                                        <td width=\"70%\" style=\"font-size: 10px;\">
                                            ";
                // line 202
                echo $this->getAttribute($context["item"], "value", array());
                echo "
                                        </td>
                                    </tr>
                                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 206
            echo "                            ";
        }
        // line 207
        echo "                            </tbody>
                        </table>
                        ";
        // line 209
        if (($context["isAnticipo"] ?? $this->getContext($context, "isAnticipo"))) {
            // line 210
            echo "                        <table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\">
                            <tbody>
                            <tr>
                                <td>
                                    <br>
                                    <strong>Anticipo</strong>
                                    <br>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\" style=\"font-size: 10px;\">
                            <tbody>
                            <tr>
                                <td width=\"30%\"><b>Nro. Doc.</b></td>
                                <td width=\"70%\"><b>Total</b></td>
                            </tr>
                            ";
            // line 227
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "anticipos", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["atp"]) {
                // line 228
                echo "                            <tr class=\"border_top\">
                                <td width=\"30%\">";
                // line 229
                echo $this->getAttribute($context["atp"], "nroDocRel", array());
                echo "</td>
                                <td width=\"70%\">";
                // line 230
                echo ($context["moneda"] ?? $this->getContext($context, "moneda"));
                echo " ";
                echo $this->env->getRuntime('Greenter\Report\Filter\FormatFilter')->number($this->getAttribute($context["atp"], "total", array()));
                echo "</td>
                            </tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['atp'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 233
            echo "                            </tbody>
                        </table>
                        ";
        }
        // line 236
        echo "                    </td>
                    <td width=\"50%\" valign=\"top\">
                        <br>
                        <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"table table-valores-totales\">
                            <tbody>
                            ";
        // line 241
        if (($context["isAnticipo"] ?? $this->getContext($context, "isAnticipo"))) {
            // line 242
            echo "                                <tr class=\"border_bottom\">
                                    <td align=\"right\"><strong>Total Anticipo:</strong></td>
                                    <td width=\"120\" align=\"right\"><span>";
            // line 244
            echo ($context["moneda"] ?? $this->getContext($context, "moneda"));
            echo "  ";
            echo $this->env->getRuntime('Greenter\Report\Filter\FormatFilter')->number($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "totalAnticipos", array()));
            echo "</span></td>
                                </tr>
                            ";
        }
        // line 247
        echo "                            ";
        if ($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "mtoOperGravadas", array())) {
            // line 248
            echo "                            <tr class=\"border_bottom\">
                                <td align=\"right\"><strong>Op. Gravadas:</strong></td>
                                <td width=\"120\" align=\"right\"><span>";
            // line 250
            echo ($context["moneda"] ?? $this->getContext($context, "moneda"));
            echo "  ";
            echo $this->env->getRuntime('Greenter\Report\Filter\FormatFilter')->number($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "mtoOperGravadas", array()));
            echo "</span></td>
                            </tr>
                            ";
        }
        // line 253
        echo "                            ";
        if ($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "mtoOperInafectas", array())) {
            // line 254
            echo "                            <tr class=\"border_bottom\">
                                <td align=\"right\"><strong>Op. Inafectas:</strong></td>
                                <td width=\"120\" align=\"right\"><span>";
            // line 256
            echo ($context["moneda"] ?? $this->getContext($context, "moneda"));
            echo "  ";
            echo $this->env->getRuntime('Greenter\Report\Filter\FormatFilter')->number($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "mtoOperInafectas", array()));
            echo "</span></td>
                            </tr>
                            ";
        }
        // line 259
        echo "                            ";
        if ($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "mtoOperExoneradas", array())) {
            // line 260
            echo "                            <tr class=\"border_bottom\">
                                <td align=\"right\"><strong>Op. Exoneradas:</strong></td>
                                <td width=\"120\" align=\"right\"><span>";
            // line 262
            echo ($context["moneda"] ?? $this->getContext($context, "moneda"));
            echo "  ";
            echo $this->env->getRuntime('Greenter\Report\Filter\FormatFilter')->number($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "mtoOperExoneradas", array()));
            echo "</span></td>
                            </tr>
                            ";
        }
        // line 265
        echo "                            <tr>
                                <td align=\"right\"><strong>IGV:</strong></td>
                                <td width=\"120\" align=\"right\"><span>";
        // line 267
        echo ($context["moneda"] ?? $this->getContext($context, "moneda"));
        echo "  ";
        echo $this->env->getRuntime('Greenter\Report\Filter\FormatFilter')->number($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "mtoIGV", array()));
        echo "</span></td>
                            </tr>
                            ";
        // line 269
        if ($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "mtoISC", array())) {
            // line 270
            echo "                            <tr>
                                <td align=\"right\"><strong>ISC:</strong></td>
                                <td width=\"120\" align=\"right\"><span>";
            // line 272
            echo ($context["moneda"] ?? $this->getContext($context, "moneda"));
            echo "  ";
            echo $this->env->getRuntime('Greenter\Report\Filter\FormatFilter')->number($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "mtoISC", array()));
            echo "</span></td>
                            </tr>
                            ";
        }
        // line 275
        echo "                            ";
        if ($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "sumOtrosCargos", array())) {
            // line 276
            echo "                                <tr>
                                    <td align=\"right\"><strong>Otros Cargos:</strong></td>
                                    <td width=\"120\" align=\"right\"><span>";
            // line 278
            echo ($context["moneda"] ?? $this->getContext($context, "moneda"));
            echo "  ";
            echo $this->env->getRuntime('Greenter\Report\Filter\FormatFilter')->number($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "sumOtrosCargos", array()));
            echo "</span></td>
                                </tr>
                            ";
        }
        // line 281
        echo "                            ";
        if ($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "mtoOtrosTributos", array())) {
            // line 282
            echo "                                <tr>
                                    <td align=\"right\"><strong>Otros Tributos:</strong></td>
                                    <td width=\"120\" align=\"right\"><span>";
            // line 284
            echo ($context["moneda"] ?? $this->getContext($context, "moneda"));
            echo "  ";
            echo $this->env->getRuntime('Greenter\Report\Filter\FormatFilter')->number($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "mtoOtrosTributos", array()));
            echo "</span></td>
                                </tr>
                            ";
        }
        // line 287
        echo "                            <tr>
                                <td align=\"right\"><strong>Precio Venta:</strong></td>
                                <td width=\"120\" align=\"right\"><span id=\"ride-importeTotal\" class=\"ride-importeTotal\">";
        // line 289
        echo ($context["moneda"] ?? $this->getContext($context, "moneda"));
        echo "  ";
        echo $this->env->getRuntime('Greenter\Report\Filter\FormatFilter')->number($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "mtoImpVenta", array()));
        echo "</span></td>
                            </tr>
                            ";
        // line 291
        if (($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "perception", array()) && $this->getAttribute($this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "perception", array()), "mto", array()))) {
            // line 292
            echo "                                ";
            $context["perc"] = $this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "perception", array());
            // line 293
            echo "                                ";
            $context["soles"] = $this->env->getRuntime('Greenter\Report\Filter\DocumentFilter')->getValueCatalog("PEN", "02");
            // line 294
            echo "                                <tr>
                                    <td align=\"right\"><strong>Percepción:</strong></td>
                                    <td width=\"120\" align=\"right\"><span>";
            // line 296
            echo ($context["soles"] ?? $this->getContext($context, "soles"));
            echo "  ";
            echo $this->env->getRuntime('Greenter\Report\Filter\FormatFilter')->number($this->getAttribute(($context["perc"] ?? $this->getContext($context, "perc")), "mto", array()));
            echo "</span></td>
                                </tr>
                                <tr>
                                    <td align=\"right\"><strong>Total a Pagar:</strong></td>
                                    <td width=\"120\" align=\"right\"><span>";
            // line 300
            echo ($context["soles"] ?? $this->getContext($context, "soles"));
            echo " ";
            echo $this->env->getRuntime('Greenter\Report\Filter\FormatFilter')->number($this->getAttribute(($context["perc"] ?? $this->getContext($context, "perc")), "mtoTotal", array()));
            echo "</span></td>
                                </tr>
                            ";
        }
        // line 303
        echo "                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody></table>
            <br>
            <br>
            ";
        // line 310
        if (((isset($context["max_items"]) || array_key_exists("max_items", $context)) && (twig_length_filter($this->env, $this->getAttribute(($context["doc"] ?? $this->getContext($context, "doc")), "details", array())) > ($context["max_items"] ?? $this->getContext($context, "max_items"))))) {
            // line 311
            echo "                <div style=\"page-break-after:always;\"></div>
            ";
        }
        // line 313
        echo "            <div>
                <hr style=\"display: block; height: 1px; border: 0; border-top: 1px solid #666; margin: 20px 0; padding: 0;\"><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
                    <tbody><tr>
                        <td width=\"85%\">
                            <blockquote>
                                ";
        // line 318
        if ($this->getAttribute($this->getAttribute(($context["params"] ?? null), "user", array(), "any", false, true), "footer", array(), "any", true, true)) {
            // line 319
            echo "                                    ";
            echo $this->getAttribute($this->getAttribute(($context["params"] ?? $this->getContext($context, "params")), "user", array()), "footer", array());
            echo "
                                ";
        }
        // line 321
        echo "                                ";
        if (($this->getAttribute($this->getAttribute(($context["params"] ?? null), "system", array(), "any", false, true), "hash", array(), "any", true, true) && $this->getAttribute($this->getAttribute(($context["params"] ?? $this->getContext($context, "params")), "system", array()), "hash", array()))) {
            // line 322
            echo "                                    <strong>Resumen:</strong>   ";
            echo $this->getAttribute($this->getAttribute(($context["params"] ?? $this->getContext($context, "params")), "system", array()), "hash", array());
            echo "<br>
                                ";
        }
        // line 324
        echo "                                <span>Representación Impresa de la ";
        echo ($context["name"] ?? $this->getContext($context, "name"));
        echo " ELECTRÓNICA.</span>
                            </blockquote>
                        </td>
                        <td width=\"15%\" align=\"right\">
                            <img src=\"";
        // line 328
        echo $this->env->getRuntime('Greenter\Report\Filter\ImageFilter')->toBase64($this->env->getRuntime('Greenter\Report\Render\QrRender')->getImage(($context["doc"] ?? $this->getContext($context, "doc"))), "png");
        echo "\" alt=\"Qr Image\">
                        </td>
                    </tr>
                    </tbody></table>
            </div>
        </td>
    </tr>
    </tbody></table>
</body></html>";
    }

    public function getTemplateName()
    {
        return "invoice.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  667 => 328,  659 => 324,  653 => 322,  650 => 321,  644 => 319,  642 => 318,  635 => 313,  631 => 311,  629 => 310,  620 => 303,  612 => 300,  603 => 296,  599 => 294,  596 => 293,  593 => 292,  591 => 291,  584 => 289,  580 => 287,  572 => 284,  568 => 282,  565 => 281,  557 => 278,  553 => 276,  550 => 275,  542 => 272,  538 => 270,  536 => 269,  529 => 267,  525 => 265,  517 => 262,  513 => 260,  510 => 259,  502 => 256,  498 => 254,  495 => 253,  487 => 250,  483 => 248,  480 => 247,  472 => 244,  468 => 242,  466 => 241,  459 => 236,  454 => 233,  443 => 230,  439 => 229,  436 => 228,  432 => 227,  413 => 210,  411 => 209,  407 => 207,  404 => 206,  394 => 202,  388 => 199,  384 => 197,  379 => 196,  376 => 195,  369 => 191,  362 => 186,  360 => 185,  355 => 182,  349 => 181,  343 => 179,  340 => 178,  336 => 177,  317 => 161,  304 => 150,  294 => 146,  290 => 145,  284 => 142,  280 => 141,  274 => 138,  268 => 135,  262 => 132,  258 => 131,  254 => 129,  250 => 128,  238 => 118,  236 => 117,  232 => 115,  226 => 111,  217 => 110,  213 => 109,  209 => 107,  207 => 106,  199 => 104,  195 => 103,  192 => 102,  186 => 99,  182 => 98,  179 => 97,  177 => 96,  170 => 94,  167 => 93,  161 => 91,  159 => 90,  155 => 89,  146 => 85,  142 => 84,  138 => 82,  136 => 81,  124 => 72,  116 => 67,  108 => 62,  92 => 49,  82 => 44,  74 => 39,  59 => 27,  48 => 19,  40 => 13,  38 => 12,  36 => 11,  34 => 10,  32 => 9,  27 => 6,  25 => 5,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "invoice.html.twig", "/var/www/html/sfe/vendor/greenter/report/src/Report/Templates/invoice.html.twig");
    }
}

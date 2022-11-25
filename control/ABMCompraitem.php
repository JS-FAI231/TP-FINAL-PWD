<?php
class ABMCompraitem
{
    public function abm($datos)
    {
        $resp = false;
        if ($datos['accion'] == 'editar') {
            if ($this->modificacion($datos)) {
                $resp = true;
            }
        }
        if ($datos['accion'] == 'borrar') {
            if ($this->baja($datos)) {
                $resp = true;
            }
        }
        if ($datos['accion'] == 'nuevo') {
            if ($this->alta($datos)) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function do_stock($datos)
    {
        $exito=true;
        if (isset($datos['dosaldostock'])) {
            if ($datos['dosaldostock'] == 'menos') {
                if (isset($datos['idcompra'])) {
                    $exito = true;
                    $auxDatos['idcompra'] = $datos['idcompra'];
                    $arrItems = $this->buscar($auxDatos);  ///Items de la compra idcompra


                    //Controlo cantidades en stock
                    foreach ($arrItems as $objCompraitem) {
                        $ctrolProd = new ABMProducto();
                        $cant = $objCompraitem->getObjproducto()->getCantstock();
                        if ($cant < $objCompraitem->getCantidad()) {
                            $exito = false;
                        }
                    }
                    //Actualizo stock
                    if ($exito) {
                        foreach ($arrItems as $objCompraitem) {
                            $ctrolProd = new ABMProducto();
                            $cant = $objCompraitem->getObjproducto()->getCantstock();
                            $saldo = $cant - $objCompraitem->getCantidad();

                            $datosSaldo['accion'] = 'editar';
                            $datosSaldo['idproducto'] = $objCompraitem->getObjproducto()->getIdproducto();
                            $datosSaldo['cantstock'] = $saldo;

                            if (!$ctrolProd->abm($datosSaldo)) {
                                $exito = false;
                            }
                        }
                    }
                } else {
                    $exito=false;
                }
            } else {
                //Devuelvo cantidades al stock
                if (isset($datos['idcompra'])) {
                    $exito = true;
                    $auxDatos['idcompra'] = $datos['idcompra'];
                    $arrItems = $this->buscar($auxDatos);  ///Items de la compra idcompra


                    // //Controlo cantidades en stock
                    // foreach ($arrItems as $objCompraitem) {
                    //     $ctrolProd = new ABMProducto();
                    //     $cant = $objCompraitem->getObjproducto()->getCantstock();
                    //     if ($cant < $objCompraitem->getCantidad()) {
                    //         $exito = false;
                    //     }
                    // }

                    //Actualizo stock
                    if ($exito) {
                        foreach ($arrItems as $objCompraitem) {
                            $ctrolProd = new ABMProducto();
                            $cant = $objCompraitem->getObjproducto()->getCantstock();
                            $saldo = $cant + $objCompraitem->getCantidad();

                            $datosSaldo['accion'] = 'editar';
                            $datosSaldo['idproducto'] = $objCompraitem->getObjproducto()->getIdproducto();
                            $datosSaldo['cantstock'] = $saldo;

                            if (!$ctrolProd->abm($datosSaldo)) {
                                $exito = false;
                            }
                        }
                    }
                } else {
                    $exito=false;
                }
            }
        } else {
            $exito=false;
        }
        return $exito;
    }

    private function cargarObjeto($param)
    {
        $metodoUI = true;
        $obj = null;
        if ($metodoUI) {
            if (array_key_exists('idcompraitem', $param)) {
                $obj = new Compraitem;
                $obj->setIdcompraitem($param['idcompraitem']);

                $obj->cargar();

                if (array_key_exists('idcompraitem', $param)) {
                    $obj->setIdcompraitem($param['idcompraitem']);
                }
                if (array_key_exists('idproducto', $param)) {
                    if ($param['idproducto'] == '0') {
                        $param['idproducto'] = null;
                        $obj->setObjProducto(null);
                    } else {
                        $objProducto = new Producto();
                        $objProducto->setIdproducto($param['idproducto']);
                        $objProducto->cargar();
                        $obj->setObjProducto($objProducto);
                    }
                }
                if (array_key_exists('idcompra', $param)) {
                    if ($param['idcompra'] == '0') {
                        $param['idcompra'] = null;
                        $obj->setObjCompra(null);
                    } else {
                        $objCompra = new Compra();
                        $objCompra->setIdcompra($param['idcompra']);
                        $objCompra->cargar();
                        $obj->setObjCompra($objCompra);
                    }
                }
                if (array_key_exists('cantidad', $param)) {
                    $obj->setCantidad($param['cantidad']);
                }
            }
        } else {
            $exito = true;
            $exito = $exito and array_key_exists('idcompraitem', $param);
            $exito = $exito and array_key_exists('idproducto', $param);
            $exito = $exito and array_key_exists('idcompra', $param);
            $exito = $exito and array_key_exists('cantidad', $param);


            if ($exito) {
                $obj = new Compraitem;
                $obj->setIdcompraitem($param['idcompraitem']);
                // $obj->setIdproducto($param['idproducto']);
                // $obj->setIdcompra($param['idcompra']);
                $obj->setCantidad($param['cantidad']);
            }
        }

        return $obj;
    }

    private function cargarObjetoConClave($param)
    {
        $obj = null;

        if (isset($param['idcompraitem'])) {
            $obj = new Compraitem();
            $obj->setIdcompraitem($param['idcompraitem']);
        }
        return $obj;
    }

    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idcompraitem']))
            $resp = true;
        return $resp;
    }

    public function alta($param)
    {
        $resp = false;
        $param['idcompraitem'] = null;
        $elObjtTabla = $this->cargarObjeto($param);
        //verEstructura($elObjtTabla);
        if ($elObjtTabla != null and $elObjtTabla->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    public function baja($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elObjtTabla = $this->cargarObjetoConClave($param);
            if ($elObjtTabla != null and $elObjtTabla->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function modificacion($param)
    {
        //echo "Estoy en modificacion";
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elObjtTabla = $this->cargarObjeto($param);
            //Control de strock
            if ($elObjtTabla->getObjproducto()->getCantstock() >= $param['cantidad'] and $param['cantidad']>0) {
                if ($elObjtTabla != null and $elObjtTabla->modificar()) {
                    $resp = true;
                }
            }
        }
        return $resp;
    }

    public function buscar($param)
    {
        $arrOrden = null;
        $orderby = "";
        if (isset($param['sort'])) {
            $arrOrden['orden'] = isset($param['sort']) ? strval($param['sort']) : '';
            $arrOrden['ordentipo'] = isset($param['order']) ? strval($param['order']) : 'asc';

            $orderby = $arrOrden['orden'] . " " . $arrOrden['ordentipo'];
        }

        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['operacion_like'])) {
                $where = " false ";
                if (isset($param['txbuscar'])) {
                    $param['txbuscar'] = strval($param['txbuscar']);
                    $where .= " or idcompraitem like '%{$param['txbuscar']}%'";
                    $where .= " or idproducto like '%{$param['txbuscar']}%'";
                    $where .= " or idcompra like '%{$param['txbuscar']}%'";
                    $where .= " or cantidad like '%{$param['txbuscar']}%'";
                }
            } else {
                if (isset($param['idcompraitem'])) {
                    $where .= " and idcompraitem = '{$param['idcompraitem']}'";
                }
                if (isset($param['idproducto'])) {
                    $where .= " and idproducto = '{$param['idproducto']}'";
                }
                if (isset($param['idcompra'])) {
                    $where .= " and idcompra = '{$param['idcompra']}'";
                }
                if (isset($param['cantidad'])) {
                    $where .= " and cantidad = '{$param['cantidad']}'";
                }
            }
        }

        $obj = new Compraitem();
        $arreglo = $obj->listar($where, $orderby);
        return $arreglo;
    }
}

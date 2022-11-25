<?php
class ABMCompra
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
        if ($datos['accion'] == 'nuevo_estado') {
            if ($this->nuevo_estado($datos)) {
                $resp = true;
            }
        }
        if ($datos['accion'] == 'add_to_basket') {
            if ($this->add_to_basket($datos)) {
                $resp = true;
            }
        }
        if ($datos['accion'] == 'finalizar_pedido') {
            if ($this->finalizar_pedido($datos)) {
                $resp = true;
            }
        }

        return $resp;
    }
    public function finalizar_pedido($datos)
    {
        $exito = false;
        if (isset($datos['idcompra'])) {
            $ctrolCompraitem = new ABMCompraitem();
            $datos['dosaldostock']='menos';
            if ($ctrolCompraitem->do_stock($datos)) {
                $datos_nuevo_estado['idcompraestadotipo'] = '2';
                $datos_nuevo_estado['idcompra'] = $datos['idcompra'];
                if ($this->nuevo_estado($datos_nuevo_estado)) {
                    $exito = true;
                }
            }
        }
        return $exito;
    }

    public function nuevo_estado($datos)
    {
        //recibo por post 'idcompraestadotipo' 'idcompra'
        //$objABMCompraestado=new ABMCompraestado();

        $exitoUpdateFechaFin = false;
        $exitoNewEstado = false;
        $exito = false;
        $estadoNuevo = $datos['idcompraestadotipo'];

        if (isset($datos['idcompra']) && $datos['idcompra'] <> -1) {

            $listaObjCompraestado = $this->listar_actual_compra_estado($datos);
            if (count($listaObjCompraestado) > 0){
                $objCompraestado=$listaObjCompraestado[0];
                $estadoActual = $objCompraestado->getObjCompraestadotipo()->getIdcompraestadotipo(); //estado actual getIdcompraestadotipo()
                $auxObjCE = new ABMCompraestado();

                $datosMod['accion'] = 'editar';
                $datosMod['idcompraestado'] = $objCompraestado->getIdcompraestado();
                $datosMod['fechafin'] = hoy();
                if ($auxObjCE->abm($datosMod)) {
                    $exitoUpdateFechaFin = true;

                    $datosNuevo['accion'] = 'nuevo';
                    $datosNuevo['idcompra'] = $datos['idcompra'];
                    $datosNuevo['idcompraestadotipo'] = $datos['idcompraestadotipo'];
                    if ($auxObjCE->abm($datosNuevo)) {
                        $exitoNewEstado = true;
                    }
                }
            }
        }
        //devuelvo las cantidades al stock si pasa de pre-ordenada a cancelada
        if ($estadoActual=='2' and $estadoNuevo=='5') { 
            $ctrolCompraitem = new ABMCompraitem();
            $datos['dosaldostock']='mas';
            if ($ctrolCompraitem->do_stock($datos)) {
            }
        }
     
        $exito=$exitoUpdateFechaFin and $exitoNewEstado;
        return $exito;
    }

    public function add_to_basket($param)
    {
        //idproducto,idcompra
        $exito = false;
        $auxCompraitem = new ABMCompraitem();
        if (isset($param['idproducto']) and isset($param['idcompra'])) {

            $arrItemEnCompra = $auxCompraitem->buscar($param); //buscar el iten en el pedido, si exite no lo vuelve a agregar
            if (count($arrItemEnCompra) == 0) {
                $param['cantidad'] = '1';
                $objControl = new ABMProducto();
                $arrDatos['idproducto'] = $param['idproducto'];
                $arrLista = $objControl->buscar($arrDatos);

                $arrObjsProducto = $arrLista[1];

                if (count($arrObjsProducto) > 0) {

                    $cantidadStock = $arrObjsProducto[0]->getCantstock();

                    if ($cantidadStock > 0) {

                        $paramCompraitem['accion'] = 'nuevo';
                        $paramCompraitem['idcompra'] = $param['idcompra'];
                        $paramCompraitem['idproducto'] = $param['idproducto'];
                        $paramCompraitem['cantidad'] = $param['cantidad'];
                        if ($auxCompraitem->abm($paramCompraitem)) {
                            $exito = true;
                        }
                    }
                }
            }
        }
        return $exito;
    }

    public function listar_compra_items($param)
    {
        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['idcompra'])) {
                $where .= " and idcompra=" . $param['idcompra'];
            }
        }
        $obj = new Compraitem();
        $arreglo = $obj->listar($where);
        return $arreglo;
    }


    public function listar_actual_compra_estado($param)
    {

        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['idcompra'])) {
                $where .= " and idcompra=" . $param['idcompra'] ." and fechafin='0000-00-00 00:00:00'";
            }
        }
        $obj = new Compraestado();
        $arreglo = $obj->listar($where);
        return $arreglo;
    }


    public function listar_compra_estado($param)
    {

        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['idcompra'])) {
                $where .= " and idcompra=" . $param['idcompra'];
            }
        }
        $obj = new Compraestado();
        $arreglo = $obj->listar($where);
        return $arreglo;
    }

    /**
     * @param array
     * @return string
     */
    public function estado($param)
    {
        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['idcompra'])) {
                $where .= " and idcompra=" . $param['idcompra'] . " and fechafin='0'";
            }
        }
        $obj = new Compraestado();
        $arreglo = $obj->listar($where);
        $estado = "NO ENCONTRADO";
        if (count($arreglo) > 0) {
            foreach ($arreglo as $objCompraestado) {
                if ($objCompraestado->getFechafin() == '0000-00-00 00:00:00') {
                    $estado = $objCompraestado->getObjCompraestadotipo()->getDescripcion();
                }
            }
        }

        return $estado;
    }


    private function cargarObjeto($param)
    {
        $metodoUI = true;
        $obj = null;
        if ($metodoUI) {
            if (array_key_exists('idcompra', $param)) {
                $obj = new Compra;
                $obj->setIdcompra($param['idcompra']);

                $obj->cargar();

                if (array_key_exists('idcompra', $param)) {
                    $obj->setIdcompra($param['idcompra']);
                }
                if (array_key_exists('fecha', $param)) {
                    $obj->setFecha($param['fecha']);
                }
                if (array_key_exists('idusuario', $param)) {
                    if ($param['idusuario'] == '0') {
                        $param['idusuario'] = null;
                        $obj->setObjUsuario(null);
                    } else {
                        $objUsuario = new Usuario();
                        $objUsuario->setIdusuario($param['idusuario']);
                        $objUsuario->cargar();
                        $obj->setObjUsuario($objUsuario);
                    }
                }
            }
        } else {
            $exito = true;
            $exito = $exito and array_key_exists('idcompra', $param);
            $exito = $exito and array_key_exists('fecha', $param);
            $exito = $exito and array_key_exists('idusuario', $param);


            if ($exito) {
                $obj = new Compra;
                $obj->setIdcompra($param['idcompra']);
                $obj->setFecha($param['fecha']);
                //$obj->setIdusuario($param['idusuario']);
            }
        }

        return $obj;
    }

    private function cargarObjetoConClave($param)
    {
        $obj = null;

        if (isset($param['idcompra'])) {
            $obj = new Compra();
            $obj->setIdcompra($param['idcompra']);
        }
        return $obj;
    }

    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idcompra']))
            $resp = true;
        return $resp;
    }

    public function alta($param)
    {
        $resp = false;
        $param['idcompra'] = null;
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
            if ($elObjtTabla != null and $elObjtTabla->modificar()) {
                $resp = true;
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
                    $where .= " or idcompra like '%{$param['txbuscar']}%'";
                    $where .= " or fecha like '%{$param['txbuscar']}%'";
                    $where .= " or idusuario like '%{$param['txbuscar']}%'";
                }
            } else {
                if (isset($param['idcompra'])) {
                    $where .= " and idcompra = '{$param['idcompra']}'";
                }
                if (isset($param['fecha'])) {
                    $where .= " and fecha = '{$param['fecha']}'";
                }
                if (isset($param['idusuario'])) {
                    $where .= " and idusuario = '{$param['idusuario']}'";
                }
            }
        }

        $obj = new Compra();
        $arreglo = $obj->listar($where, $orderby);
        return $arreglo;
    }
}

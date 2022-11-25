<?php
class ABMCompraestado
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

    private function cargarObjeto($param)
    {
        $metodoUI = true;
        $obj = null;
        if ($metodoUI) {
            if (array_key_exists('idcompraestado', $param)) {
                $obj = new Compraestado;
                $obj->setIdcompraestado($param['idcompraestado']);

                $obj->cargar();

                if (array_key_exists('idcompraestado', $param)) {
                    $obj->setIdcompraestado($param['idcompraestado']);
                }
                if (array_key_exists('idcompra', $param)) {
                    $obj->setIdcompra($param['idcompra']);
                }
              
                /*Delegacion*/
                if (array_key_exists('idcompraestadotipo', $param)) {
                    if ($param['idcompraestadotipo'] == '0') {
                        $param['idcompraestadotipo'] = null;
                        $obj->setObjCompraestadotipo(null);
                    } else {
                        $objCompraestadotipo = new Compraestadotipo();
                        $objCompraestadotipo->setIdcompraestadotipo($param['idcompraestadotipo']);
                        $objCompraestadotipo->cargar();
                        $obj->setObjCompraestadotipo($objCompraestadotipo);
                    }
                }
                // if (array_key_exists('idcompra', $param)) {
                //     $obj->setIdcompra($param['idcompra']);
                // }
                // if (array_key_exists('idcompraestadotipo', $param)) {
                //     $obj->setIdcompraestadotipo($param['idcompraestadotipo']);
                // }
                /*Fin Delegacion*/
                
                if (array_key_exists('fechaini', $param)) {
                    //$obj->setFechaini($param['fechaini']);  //La fecha de inicio la establece la clase Compraestado al crear el objeto
                }
                if (array_key_exists('fechafin', $param)) {
                    $obj->setFechafin($param['fechafin']);
                }
            }
        } else {
            $exito = true;
            $exito = $exito and array_key_exists('idcompraestado', $param);
            $exito = $exito and array_key_exists('idcompra', $param);
            $exito = $exito and array_key_exists('idcompraestadotipo', $param);
            $exito = $exito and array_key_exists('fechaini', $param);
            $exito = $exito and array_key_exists('fechafin', $param);


            if ($exito) {
                $obj = new Compraestado;
                $obj->setIdcompraestado($param['idcompraestado']);
                $obj->setIdcompra($param['idcompra']);
                $obj->setIdcompraestadotipo($param['idcompraestadotipo']);
                $obj->setFechaini($param['fechaini']); //La fecha de inicio la establece la clase Compraestado al crear el objeto
                $obj->setFechafin($param['fechafin']);
            }
        }

        return $obj;
    }

    private function cargarObjetoConClave($param)
    {
        $obj = null;

        if (isset($param['idcompraestado'])) {
            $obj = new Compraestado();
            $obj->setIdcompraestado($param['idcompraestado']);
        }
        return $obj;
    }

    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idcompraestado']))
            $resp = true;
        return $resp;
    }

    public function alta($param)
    {
        $resp = false;
        $param['idcompraestado'] = null;
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
                    $where .= " or idcompraestado like '%{$param['txbuscar']}%'";
                    $where .= " or idcompra like '%{$param['txbuscar']}%'";
                    $where .= " or idcompraestadotipo like '%{$param['txbuscar']}%'";
                    $where .= " or fechaini like '%{$param['txbuscar']}%'";
                    $where .= " or fechafin like '%{$param['txbuscar']}%'";
                }
            } else {
                if (isset($param['idcompraestado'])) {
                    $where .= " and idcompraestado = '{$param['idcompraestado']}'";
                }
                if (isset($param['idcompra'])) {
                    $where .= " and idcompra = '{$param['idcompra']}'";
                }
                if (isset($param['idcompraestadotipo'])) {
                    $where .= " and idcompraestadotipo = '{$param['idcompraestadotipo']}'";
                }
                if (isset($param['fechaini'])) {
                    $where .= " and fechaini = '{$param['fechaini']}'";
                }
                if (isset($param['fechafin'])) {
                    $where .= " and fechafin = '{$param['fechafin']}'";
                }
            }
        }

        $obj = new Compraestado();
        $arreglo = $obj->listar($where, $orderby);
        return $arreglo;
    }
}

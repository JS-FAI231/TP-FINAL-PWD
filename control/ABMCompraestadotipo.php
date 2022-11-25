<?php
class ABMCompraestadotipo
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
            if (array_key_exists('idcompraestadotipo', $param)) {
                $obj = new Compraestadotipo;
                $obj->setIdcompraestadotipo($param['idcompraestadotipo']);

                $obj->cargar();

                if (array_key_exists('idcompraestadotipo', $param)) {
                    $obj->setIdcompraestadotipo($param['idcompraestadotipo']);
                }
                if (array_key_exists('descripcion', $param)) {
                    $obj->setDescripcion($param['descripcion']);
                }
                if (array_key_exists('detalle', $param)) {
                    $obj->setDetalle($param['detalle']);
                }
            }
        } else {
            $exito = true;
            $exito = $exito and array_key_exists('idcompraestadotipo', $param);
            $exito = $exito and array_key_exists('descripcion', $param);
            $exito = $exito and array_key_exists('detalle', $param);


            if ($exito) {
                $obj = new Compraestadotipo;
                $obj->setIdcompraestadotipo($param['idcompraestadotipo']);
                $obj->setDescripcion($param['descripcion']);
                $obj->setDetalle($param['detalle']);
            }
        }

        return $obj;
    }

    private function cargarObjetoConClave($param)
    {
        $obj = null;

        if (isset($param['idcompraestadotipo'])) {
            $obj = new Compraestadotipo();
            $obj->setIdcompraestadotipo($param['idcompraestadotipo']);
        }
        return $obj;
    }

    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idcompraestadotipo']))
            $resp = true;
        return $resp;
    }

    public function alta($param)
    {
        $resp = false;
        //$param['idcompraestadotipo'] = null; //Solo para campos id autoincrement
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
                    $where .= " or idcompraestadotipo like '%{$param['txbuscar']}%'";
                    $where .= " or descripcion like '%{$param['txbuscar']}%'";
                    $where .= " or detalle like '%{$param['txbuscar']}%'";
                }
            } else {
                if (isset($param['idcompraestadotipo'])) {
                    $where .= " and idcompraestadotipo = '{$param['idcompraestadotipo']}'";
                }
                if (isset($param['descripcion'])) {
                    $where .= " and descripcion = '{$param['descripcion']}'";
                }
                if (isset($param['detalle'])) {
                    $where .= " and detalle = '{$param['detalle']}'";
                }
            }
        }

        $obj = new Compraestadotipo();
        $arreglo = $obj->listar($where, $orderby);
        return $arreglo;
    }
}

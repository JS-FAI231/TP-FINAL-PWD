<?php
class ABMRol
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
            if (array_key_exists('idrol', $param)) {
                $obj = new Rol;
                $obj->setIdrol($param['idrol']);

                $obj->cargar();

                if (array_key_exists('idrol', $param)) {
                    $obj->setIdrol($param['idrol']);
                }
                if (array_key_exists('descripcion', $param)) {
                    $obj->setDescripcion($param['descripcion']);
                }
            }
        } else {
            $exito = true;
            $exito = $exito and array_key_exists('idrol', $param);
            $exito = $exito and array_key_exists('descripcion', $param);


            if ($exito) {
                $obj = new Rol;
                $obj->setIdrol($param['idrol']);
                $obj->setDescripcion($param['descripcion']);
            }
        }

        return $obj;
    }

    private function cargarObjetoConClave($param)
    {
        $obj = null;

        if (isset($param['idrol'])) {
            $obj = new Rol();
            $obj->setIdrol($param['idrol']);
        }
        return $obj;
    }

    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idrol']))
            $resp = true;
        return $resp;
    }

    public function alta($param)
    {
        $resp = false;
        $param['idrol'] = null;
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
                    $where .= " or idrol like '%{$param['txbuscar']}%'";
                    $where .= " or descripcion like '%{$param['txbuscar']}%'";
                }
            } else {
                if (isset($param['idrol'])) {
                    $where .= " and idrol = '{$param['idrol']}'";
                }
                if (isset($param['descripcion'])) {
                    $where .= " and descripcion = '{$param['descripcion']}'";
                }
            }
        }

        $obj = new Rol();
        $arreglo = $obj->listar($where, $orderby);
        return $arreglo;
    }
}

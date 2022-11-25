<?php
class ABMProducto
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
            if (array_key_exists('idproducto', $param)) {
                $obj = new Producto;
                $obj->setIdproducto($param['idproducto']);

                $obj->cargar();

                if (array_key_exists('idproducto', $param)) {
                    $obj->setIdproducto($param['idproducto']);
                }
                if (array_key_exists('nombre', $param)) {
                    $obj->setNombre($param['nombre']);
                }
                if (array_key_exists('detalle', $param)) {
                    $obj->setDetalle($param['detalle']);
                }
                if (array_key_exists('cantstock', $param)) {
                    $obj->setCantstock($param['cantstock']);
                }
                if (array_key_exists('artista', $param)) {
                    $obj->setArtista($param['artista']);
                }
                if (array_key_exists('album', $param)) {
                    $obj->setAlbum($param['album']);
                }
                if (array_key_exists('foto', $param)) {
                    $obj->setFoto($param['foto']);
                }
            }
        } else {
            $exito = true;
            $exito = $exito and array_key_exists('idproducto', $param);
            $exito = $exito and array_key_exists('nombre', $param);
            $exito = $exito and array_key_exists('detalle', $param);
            $exito = $exito and array_key_exists('cantstock', $param);
            $exito = $exito and array_key_exists('artista', $param);
            $exito = $exito and array_key_exists('album', $param);
            $exito = $exito and array_key_exists('foto', $param);


            if ($exito) {
                $obj = new Producto;
                $obj->setIdproducto($param['idproducto']);
                $obj->setNombre($param['nombre']);
                $obj->setDetalle($param['detalle']);
                $obj->setCantstock($param['cantstock']);
                $obj->setArtista($param['artista']);
                $obj->setAlbum($param['album']);
                $obj->setFoto($param['foto']);
            }
        }

        return $obj;
    }

    private function cargarObjetoConClave($param)
    {
        $obj = null;

        if (isset($param['idproducto'])) {
            $obj = new Producto();
            $obj->setIdproducto($param['idproducto']);
        }
        return $obj;
    }

    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idproducto']))
            $resp = true;
        return $resp;
    }

    public function alta($param)
    {
        $resp = false;
        $param['idproducto'] = null;
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
        $limite_de_filas = "";

        $distinct = isset($param['distinct']) ? $param['distinct'] : '';

        // $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        // $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
        if (isset(($param['page']))) {
            $page = isset($param['page']) ? intval($param['page']) : 1;
            $rows = isset($param['rows']) ? intval($param['rows']) : 20;
            $offset = ($page - 1) * $rows;
            $limite_de_filas = $offset . "," . $rows;
        }

        if (isset(($param['orden']))) {
            if ($distinct <> '') {
                $arrOrden['orden'] = $param['orden'];
                $arrOrden['ordentipo'] = 'asc';
            } else {
                $arrOrden['orden'] = isset($param['sort']) ? strval($param['sort']) : 'album';
                $arrOrden['ordentipo'] = isset($param['order']) ? strval($param['order']) : 'asc';
            }
            
        }else{
            $arrOrden['orden'] = isset($_POST['sort']) ? strval($_POST['sort']) : 'artista';
            $arrOrden['ordentipo'] = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        }
        $orderby = $arrOrden['orden'] . " " . $arrOrden['ordentipo'];

        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['idproducto'])) {
                $where .= " and idproducto = '{$param['idproducto']}'";
            }
            if (isset($param['nombre'])) {
                $where .= " and nombre = '{$param['nombre']}'";
            }
            if (isset($param['detalle'])) {
                $where .= " and detalle = '{$param['detalle']}'";
            }
            if (isset($param['cantstock'])) {
                $where .= " and cantstock = '{$param['cantstock']}'";
            }
            if (isset($param['artista'])) {
                $where .= " and artista = '{$param['artista']}'";
            }
            if (isset($param['album'])) {
                $where .= " and album = '{$param['album']}'";
            }

            if (isset($param['operacion_like'])) {
                $where .= " and ( false ";
                if (isset($param['txbuscar'])) {
                    $param['txbuscar'] = strval($param['txbuscar']);
                    $where .= " or idproducto like '%{$param['txbuscar']}%'";
                    $where .= " or nombre like '%{$param['txbuscar']}%'";
                    $where .= " or detalle like '%{$param['txbuscar']}%'";
                    $where .= " or cantstock like '%{$param['txbuscar']}%'";
                    $where .= " or artista like '%{$param['txbuscar']}%'";
                    $where .= " or album like '%{$param['txbuscar']}%'";
                    $where .= " ) ";
                }
            }
        }

        $obj = new Producto();
        $arreglo = array();
        $totalFilas = $obj->getTotalFilas($where);
        array_push($arreglo, $totalFilas);

        $arrObjs = $obj->listar($where, $orderby, $limite_de_filas, $distinct);
        array_push($arreglo, $arrObjs);

        return $arreglo;
    }

      
}

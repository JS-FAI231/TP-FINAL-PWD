<?php
class ABMUsuario
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
        /**Control de Roles */
        if ($datos['accion'] == 'borrar_rol') {
            if ($this->borrar_rol($datos)) {
                $resp = true;
            }
        }
        if ($datos['accion'] == 'nuevo_rol') {
            if ($this->alta_rol($datos)) {
                $resp = true;
            }
        }
        /**FIN Control de Roles */
        return $resp;
    }

    /**Control de Roles */
    public function listar_roles($param)
    {
        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['idusuario']))
                $where .= " and idusuario =" . $param['idusuario'];
            if (isset($param['idrol']))
                $where .= " and idrol ='" . $param['idrol'] . "'";
        }
        $obj = new Usuariorol();
        $arreglo = $obj->listar($where);
        return $arreglo;
    }

    public function borrar_rol($param)
    {
        $resp = false;
        if (isset($param['idusuario']) && isset($param['idrol'])) {
            $elObjtTabla = new Usuariorol();
            $elObjtTabla->setearConClave($param['idusuario'], $param['idrol']);
            $resp = $elObjtTabla->eliminar();
        }
        return $resp;
    }

    public function alta_rol($param)
    {
        $resp = false;
        if (isset($param['idusuario']) && isset($param['idrol'])) {
            $elObjtTabla = new Usuariorol();
            $elObjtTabla->setearConClave($param['idusuario'], $param['idrol']);
            $resp = $elObjtTabla->insertar();
        }
        return $resp;
    }
    /**FIN Control de Roles */
    private function cargarObjeto($param)
    {
        $metodoUI = true;
        $obj = null;
        if ($metodoUI) {
            if (array_key_exists('idusuario', $param)) {
                $obj = new Usuario;
                $obj->setIdusuario($param['idusuario']);

                $obj->cargar();

                if (array_key_exists('idusuario', $param)) {
                    $obj->setIdusuario($param['idusuario']);
                }
                if (array_key_exists('nombre', $param)) {
                    $obj->setNombre($param['nombre']);
                }
                if (array_key_exists('pass', $param)) {
                    $obj->setPass($param['pass']);
                }
                if (array_key_exists('mail', $param)) {
                    $obj->setMail($param['mail']);
                }
                if (array_key_exists('deshabilitado', $param)) {
                    if ($param['deshabilitado'] == '0') {
                        $param['deshabilitado'] = null;
                    } else {
                        $param['deshabilitado'] = date("Y-m-d H:i:s");
                    }
                    $obj->setDeshabilitado($param['deshabilitado']);
                } else {
                    $obj->setDeshabilitado(null);
                }
            }
        } else {
            $exito = true;
            $exito = $exito and array_key_exists('idusuario', $param);
            $exito = $exito and array_key_exists('nombre', $param);
            $exito = $exito and array_key_exists('pass', $param);
            $exito = $exito and array_key_exists('mail', $param);
            $exito = $exito and array_key_exists('deshabilitado', $param);


            if ($exito) {
                $obj = new Usuario;
                $obj->setIdusuario($param['idusuario']);
                $obj->setNombre($param['nombre']);
                $obj->setPass($param['pass']);
                $obj->setMail($param['mail']);
                $obj->setDeshabilitado($param['deshabilitado']);
            }
        }

        return $obj;
    }

    private function cargarObjetoConClave($param)
    {
        $obj = null;

        if (isset($param['idusuario'])) {
            $obj = new Usuario();
            $obj->setIdusuario($param['idusuario']);
        }
        return $obj;
    }

    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idusuario']))
            $resp = true;
        return $resp;
    }

    public function alta($param)
    {
        $resp = false;
        $param['idusuario'] = null;
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

    public function buscar($param=null,$paramWhere="")
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
                    $where .= " or idusuario like '%{$param['txbuscar']}%'";
                    $where .= " or nombre like '%{$param['txbuscar']}%'";
                    $where .= " or pass like '%{$param['txbuscar']}%'";
                    $where .= " or mail like '%{$param['txbuscar']}%'";
                    $where .= " or deshabilitado like '%{$param['txbuscar']}%'";
                }
            } else {
                if (isset($param['idusuario'])) {
                    $where .= " and idusuario = '{$param['idusuario']}'";
                }
                if (isset($param['nombre'])) {
                    $where .= " and nombre = '{$param['nombre']}'";
                }
                if (isset($param['pass'])) {
                    $where .= " and pass = '{$param['pass']}'";
                }
                if (isset($param['mail'])) {
                    $where .= " and mail = '{$param['mail']}'";
                }
                if (isset($param['deshabilitado'])) {
                    $where .= " and deshabilitado = '{$param['deshabilitado']}'";
                }
            }
        }
        if ($paramWhere!=""){
            $where .= "and ".$paramWhere;
        }

        $obj = new Usuario();
        $arreglo = $obj->listar($where, $orderby);
        return $arreglo;
    }

    public function existeMail($param){
        $resp=false;
        if (isset($param['mail'])){
            $arrBuscar['mail']=$param['mail'];
            $paramWhere=" idusuario <> '{$param['idusuario']}' ";
            $arrMail=$this->buscar($arrBuscar,$paramWhere);
            if (count($arrMail)>0){
                $resp=true;
            }
        }
        return $resp;
    }
    public function existeUsuario($param){
        $resp=false;
        if (isset($param['nombre'])){
            $arrBuscar['nombre']=$param['nombre'];
            $paramWhere=" idusuario <> '{$param['idusuario']}' ";
            $arrNombre=$this->buscar($arrBuscar,$paramWhere);
            if (count($arrNombre)>0){
                $resp=true;
            }
        }
        return $resp;
    }
}

<?php
class ABMMenu
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
            if (isset($param['idmenu']))
                $where .= " and idmenu =" . $param['idmenu'];
            if (isset($param['idrol']))
                $where .= " and idrol ='" . $param['idrol'] . "'";
        }
        $obj = new Menurol();
        $arreglo = $obj->listar($where);
        return $arreglo;
    }

    public function borrar_rol($param)
    {
        $resp = false;
        if (isset($param['idmenu']) && isset($param['idrol'])) {
            $elObjtTabla = new Menurol();
            $elObjtTabla->setearConClave($param['idmenu'], $param['idrol']);
            $resp = $elObjtTabla->eliminar();
        }
        return $resp;
    }

    public function alta_rol($param)
    {
        $resp = false;
        if (isset($param['idmenu']) && isset($param['idrol'])) {
            $elObjtTabla = new Menurol();
            $elObjtTabla->setearConClave($param['idmenu'], $param['idrol']);
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
            if (array_key_exists('idmenu', $param)) {
                $obj = new Menu;


                $obj->setIdmenu($param['idmenu']);
                $obj->cargar();

                if (array_key_exists('idmenu', $param)) {
                    $obj->setIdmenu($param['idmenu']);
                }
                if (array_key_exists('nombre', $param)) {
                    $obj->setNombre($param['nombre']);
                }
                if (array_key_exists('descripcion', $param)) {
                    $obj->setDescripcion($param['descripcion']);
                }
                if (array_key_exists('idpadre', $param)) {
                    if ($param['idpadre'] == '0') {
                        $param['idpadre'] = null;
                        $obj->setObjMenu(null);
                    } else {
                        $objMenu = new Menu();
                        $objMenu->setIdmenu($param['idpadre']);
                        $objMenu->cargar();
                        $obj->setObjMenu($objMenu);
                    }
                }
                if (array_key_exists('deshabilitado', $param)) {
                    if ($param['deshabilitado'] == '0') {
                        $param['deshabilitado'] = null;
                    } else {
                        $param['deshabilitado'] = date("Y-m-d H:i:s");
                    }
                    $obj->setDeshabilitado($param['deshabilitado']);
                } else {
                    $param['deshabilitado'] = null;
                    $obj->setDeshabilitado($param['deshabilitado']);
                }
            }
        } else {
            $exito = true;
            $exito = $exito and array_key_exists('idmenu', $param);
            $exito = $exito and array_key_exists('nombre', $param);
            $exito = $exito and array_key_exists('descripcion', $param);
            $exito = $exito and array_key_exists('idpadre', $param);
            $exito = $exito and array_key_exists('deshabilitado', $param);


            if ($exito) {
                $obj = new Menu;
                $obj->setIdmenu($param['idmenu']);
                $obj->setNombre($param['nombre']);
                $obj->setDescripcion($param['descripcion']);
                $obj->setIdpadre($param['idpadre']);
                $obj->setDeshabilitado($param['deshabilitado']);
            }
        }

        return $obj;
    }

    private function cargarObjetoConClave($param)
    {
        $obj = null;

        if (isset($param['idmenu'])) {
            $obj = new Menu();
            $obj->setIdmenu($param['idmenu']);
        }
        return $obj;
    }

    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idmenu']))
            $resp = true;
        return $resp;
    }

    public function alta($param)
    {
        $resp = false;
        $param['idmenu'] = null;
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
                    $where .= " or idmenu like '%{$param['txbuscar']}%'";
                    $where .= " or nombre like '%{$param['txbuscar']}%'";
                    $where .= " or descripcion like '%{$param['txbuscar']}%'";
                    $where .= " or idpadre like '%{$param['txbuscar']}%'";
                    $where .= " or deshabilitado like '%{$param['txbuscar']}%'";
                }
            } else {
                if (isset($param['idmenu'])) {
                    $where .= " and idmenu = '{$param['idmenu']}'";
                }
                if (isset($param['nombre'])) {
                    $where .= " and nombre = '{$param['nombre']}'";
                }
                if (isset($param['descripcion'])) {
                    $where .= " and descripcion = '{$param['descripcion']}'";
                }
                if (isset($param['idpadre'])) {
                    $where .= " and idpadre = '{$param['idpadre']}'";
                }
                if (isset($param['deshabilitado'])) {
                    $where .= " and deshabilitado = '{$param['deshabilitado']}'";
                }
            }
        }

        $obj = new Menu();
        $arreglo = $obj->listar($where, $orderby);
        return $arreglo;
    }
    
    //FUNCION PARA GENERAR MENU
    function arr_menu_usuario($idusuario){
        $abmUsuario=new ABMUsuario();
        $datos['idusuario']=$idusuario;
        $arrUsuarioRol=$abmUsuario->listar_roles($datos);
        $where=" false ";
        foreach ($arrUsuarioRol as $objUR){
            $where .= " or idrol='{$objUR->getObjrol()->getIdrol()}'";
        }
        
        $abmMenu =new ABMMenu();
        $arrMenuRol=$abmMenu->listarDistinctMenu($where);
        $arr=array(); 
        $arreglo=array();
        foreach($arrMenuRol as $objMR){
            $arr['idmenu']=$objMR->getObjmenu()->getIdmenu();
            $arr['nombre']=$objMR->getObjmenu()->getNombre();
            $arr['url']=$objMR->getObjmenu()->getDescripcion();
            if ($objMR->getObjmenu()->getObjmenu()!=null){
                $arr['padre']=$objMR->getObjmenu()->getObjmenu()->getIdmenu();
            }else{
                $arr['padre']='0';
            }
            array_push($arreglo, $arr);
        }
        return $arreglo;
    }
    private function listarDistinctMenu($where){
        $sql="select distinct idmenu from menurol where (".$where.")";
        $obj = new Menurol();
        $arreglo=$obj->listarDistinctMenu($sql);
        return $arreglo;
    }

    //Devuelve un array con los nombres de los roles de un idusuario
    public function roles($idusuario){
        $arr=array();
        $abmUsuario=new ABMUsuario();
        $datos['idusuario']=$idusuario;
        $arrUsuarioRol=$abmUsuario->listar_roles($datos);
        foreach ($arrUsuarioRol as $objUR){
            $rol = $objUR->getObjrol()->getDescripcion();
            array_push($arr,$rol);
        }
        return $arr;
    }
}

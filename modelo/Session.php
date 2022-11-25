<?php

class Session
{
    private $mensajeOperacion;


    /**CONSTRUCTOR */
    public function __construct()
    {
        $exito = false;

        if ($this->is_session_started() === FALSE) {
            if (session_start()) {
                $session_id = session_id();
                $_SESSION['idsesion'] = $session_id;
                $exito = true;
                $this->setMensajeOperacion("Se inicio la sesion exitosamente.");
            } else {
                $this->setMensajeOperacion("No se puede iniciar la sesion.");
            }
        }
        return $exito;
    }

    private function is_session_started()
    {
        if (php_sapi_name() !== 'cli') {
            if (version_compare(phpversion(), '5.4.0', '>=')) {
                return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
            } else {
                return session_id() === '' ? FALSE : TRUE;
            }
        }
        return FALSE;
    }

    public function iniciar($nombreUsuario, $psw)
    {
        $_SESSION['nombre'] = "";
        $_SESSION['idusuario'] = "";
        $_SESSION['activa'] = false;
        $_SESSION['rol'] = "";
        $_SESSION['idcompra'] = "0";
        $_SESSION['compraestado'] = "";
        $_SESSION['cantidaditems'] = 0;

        if (!$this->verificarDatosEnServidor($nombreUsuario, $psw)) {
            $this->mensajeOperacion = "ERROR AL INICIAR";
        }
    }
    private function verificarDatosEnServidor($nombreUsuario, $psw)
    {
        $exito = false;

        //creo un objeto usuario y traigo los datos a la sesion
        $auxObjUsuario = new Usuario();
        $parametro = "nombre='{$nombreUsuario}' and pass='{$psw}' and (deshabilitado='0000-00-00 00:00:00' or deshabilitado=null)";
        $exitoUsuario = false;

        $arrUsuario = $auxObjUsuario->listar($parametro);
        if (count($arrUsuario) > 0) {
            $objUsuario = $arrUsuario[0];
            $_SESSION['nombre'] = $objUsuario->getNombre();
            $_SESSION['idusuario'] = $objUsuario->getIdusuario();
            //Asigno el rol de usuario por defecto
            $datos['idusuario']=$objUsuario->getIdusuario();
            $auxABM=new ABMUsuario();
            $arrLista=$auxABM->listar_roles($datos);
            if (count($arrLista)==0){
                $datos['accion']='nuevo_rol';
                $datos['idrol']='4';
                $respuesta=$auxABM->abm($datos);
                if ($respuesta){
                    $exitoUsuario = true;
                }
            }else{
                $exitoUsuario = true;
            }
            // $_SESSION['rol'] = "";
            // $exitoUsuario = true;
        }

        if ($exitoUsuario) {
            //creo un objeto compra y traigo los datos a esta sesion

            $auxObjCompra = new Compra();
            $parametro = "INNER JOIN compraestado ON compra.idcompra=compraestado.idcompra WHERE compra.idusuario='{$_SESSION['idusuario']}' AND compraestado.fechafin='0' and compraestado.idcompraestadotipo='1'";

            $exitoCompra = false;

            $arrCompra = $auxObjCompra->listar_in_session($parametro);

            if (count($arrCompra) > 0) {
                $objCompra = $arrCompra[0];
                $_SESSION['idcompra'] = $objCompra->getIdcompra();
                $_SESSION['compraestado'] = 'iniciada';
                $_SESSION['nombre'] = $objUsuario->getNombre();
                $_SESSION['idusuario'] = $objUsuario->getIdusuario();
                $_SESSION['rol'] = "";
                //Busco la cantidad de items de esta compra
                $auxCompraitems = new ABMCompraitem();
                $param['idcompra'] = $_SESSION['idcompra'];
                $arrItems = $auxCompraitems->buscar($param);
                $_SESSION['cantidaditems'] = count($arrItems);

                $exitoCompra = true;
            } else {
                //No tiene compras iniciadas
                //entonces hago un new de Compra y un new de compraestado con el idcompraestadotipo=1
                $exitoCompra = $this->iniciarNuevoCarro();
            }
        }

        $exito = ($exitoUsuario and $exitoCompra);
        $_SESSION['activa'] = $exito;
        return $exito;
    }

    public function iniciarNuevoCarro()
    {
        $objABMCompra = new ABMCompra();
        $datos['accion'] = 'nuevo';
        $datos['idusuario'] = $_SESSION['idusuario'];
        if ($objABMCompra->abm($datos)) {
            $auxObjCompra = new Compra();
            $param = "idusuario='{$datos['idusuario']}' order by idcompra asc";
            $arrCompras = $auxObjCompra->listar($param);
            $j = count($arrCompras);
            if ($j > 0) {
                $objCompra = new Compra();
                $objCompra = $arrCompras[($j - 1)];
                $objABMCompraestado = new ABMCompraestado();
                $datos['idcompra'] = $objCompra->getIdcompra();
                $datos['idcompraestadotipo'] = '1';
                if ($objABMCompraestado->abm($datos)) {
                    $_SESSION['idcompra'] = $objCompra->getIdcompra();
                    $_SESSION['compraestado'] = 'iniciada';
                    $_SESSION['cantidaditems'] = 0;
                    $exitoNuevoCarro = true;
                }
            }
        }
        return $exitoNuevoCarro;
    }

    /**
     * Devuelve el verdadero si hay una sesión activa y falso en caso contrario
     * @return boolean
     */
    public function activa()
    {
        $exito = false;
        if (isset($_SESSION['activa'])) {
            $exito = $_SESSION['activa'];
        } else {
            $this->setMensajeOperacion("La sesion NO esta activa");
        }
        return $exito;
    }

    /**SETTERS & GETTERS */
    public function getMensajeOperacion()
    {
        return $this->mensajeOperacion;
    }

    public function setMensajeOperacion($mensajeOperacion)
    {
        $this->mensajeOperacion = $mensajeOperacion;
    }

    /**
     * Devuelve el nombre de usuario de la sesión
     * @return string 
     */
    public function getNombre()
    {
        $nombre = "";

        if ($this->activa()) {
            if (isset($_SESSION['nombre'])) {
                $nombre = $_SESSION['nombre'];
            } else {
                $this->setMensajeOperacion("No está seteado el nombre de usuario");
            }
        } else {
            $this->setMensajeOperacion("No tiene una sección activa");
        }
        return $nombre;
    }

    /**
     * Devuelve el id de usuario de la sesión
     * @return string 
     */
    public function getIdusuario()
    {
        $idusuario = "";

        if ($this->activa()) {
            if (isset($_SESSION['idusuario'])) {
                $idusuario = $_SESSION['idusuario'];
            } else {
                $this->setMensajeOperacion("No está seteado el id usuario");
            }
        } else {
            $this->setMensajeOperacion("No tiene una sección activa");
        }
        return $idusuario;
    }
    /**
     * Devuelve el id compra de la sesión
     * @return string 
     */
    public function getIdcompra()
    {
        $idusuario = "";

        if ($this->activa()) {
            if (isset($_SESSION['idcompra'])) {
                $idusuario = $_SESSION['idcompra'];
            } else {
                $this->setMensajeOperacion("No está seteado el idcompra");
            }
        } else {
            $this->setMensajeOperacion("No tiene una sección activa");
        }
        return $idusuario;
    }
    /**
     * Devuelve el estado de la compra
     * @return string 
     */
    public function getCompraestado()
    {
        $idusuario = "";

        if ($this->activa()) {
            if (isset($_SESSION['compraestado'])) {
                $idusuario = $_SESSION['compraestado'];
            } else {
                $this->setMensajeOperacion("No está seteado el compraestado");
            }
        } else {
            $this->setMensajeOperacion("No tiene una sección activa");
        }
        return $idusuario;
    }
    /**
     * Devuelve la cantidad de items de la compra
     * @return string 
     */
    public function getCantidaditems()
    {
        $cantidad = "";

        if ($this->activa()) {
            if (isset($_SESSION['cantidaditems'])) {
                $cantidad = $_SESSION['cantidaditems'];
            } else {
                $this->setMensajeOperacion("No está seteado cantidaditems");
            }
        } else {
            $this->setMensajeOperacion("No tiene una sección activa");
        }
        return $cantidad;
    }
    public function setCantidaditems($cantidad)
    {
        if ($this->activa()) {
            if (isset($_SESSION['cantidaditems'])) {
                $_SESSION['cantidaditems'] = $cantidad;
            } else {
                $this->setMensajeOperacion("No está seteado cantidaditems");
            }
        } else {
            $this->setMensajeOperacion("No tiene una sección activa");
        }
    }

    /**
     * Devuelve el Rol asignado al usuario de la sesión
     * @return string
     */
    public function getRol()
    {
        $rol = "";

        if ($this->activa()) {
            if (isset($_SESSION['rol'])) {
                $rol = $_SESSION['rol'];
            } else {
                $this->setMensajeOperacion("No está seteado el rol");
            }
        } else {
            $this->setMensajeOperacion("No tiene una sección activa");
        }
        return $rol;
    }

    public function verificarRoles(){
    //Verifico la url segun los roles asignados al menu y a este usuario
    $abmMenu = new ABMMenu();
    $arreglo = $abmMenu->arr_menu_usuario($_SESSION['idusuario']);
    $a= __FILE__;
    $arr=explode('\\',$a);
    $buscar_url=$arr[count($arr)-2];
    $exito=true;
    foreach ($arreglo as $row) {
        if (str_contains($row['url'],$buscar_url) or strtolower($buscar_url=='home')){
            $exito=true;
        }
    }
    return $exito;
    }

    /**
     * Cierra la session
     * @return boolean
     */
    public function cerrar()
    {
        $exito = false;
        if (session_destroy()) {
            $exito = true;
        } else {
            $this->setMensajeOperacion("No se puede cerrar la sesion");
        }
    }
}

<?php
class Menurol extends BaseDatos
{
    private $objmenu;
    private $objrol;
    private $mensajeoperacion;


    public function __construct()
    {
        parent::__construct();
        $this->objmenu = new Menu();
        $this->objrol = New Rol;
        $this->mensajeoperacion = '';
    }

    public function setear($objmenu, $objrol)
    {
        $this->setObjmenu($objmenu);
        $this->setObjrol($objrol);
    }

    public function setearConClave($idmenu,$idrol){
        $this->getObjmenu()->setIdMenu($idmenu);
        $this->getObjRol()->setIdrol($idrol);
    }

    public function setObjmenu($objmenu)
    {
        $this->objmenu = $objmenu;
    }
    public function setObjrol($objrol)
    {
        $this->objrol = $objrol;
    }
    public function setMensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function getObjmenu()
    {
        return $this->objmenu;
    }
    public function getObjrol()
    {
        return $this->objrol;
    }
    public function getMensajeoperacion()
    {
        return $this->mensajeoperacion;
    }

    public function cargar()
    {
        $resp = false;
        $sql = "SELECT * FROM menurol WHERE idmenu = '{$this->getObjmenu()->getIdmenu()}' AND idrol='{$this->getObjrol()->getIdrol()}'";
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $this->Registro();

                    $objMenu= new Menu();
                    $objMenu->setIdmenu($row['idmenu']);
                    $objMenu->cargar();

                    $objRol = new Rol();
                    $objRol->setIdrol($row['idrol']);
                    $objRol->cargar();

                    $this->setear($objMenu,$objRol);
                }
            }
        } else {
            $this->setmensajeoperacion("Tabla->cargar: " . $this->getError());
        }
        return $resp;
    }


    public function insertar()
    {
        $resp = false;
        $sql = "INSERT INTO menurol (idmenu,idrol) VALUES (
            '{$this->getObjmenu()->getIdmenu()}',
            '{$this->getObjrol()->getIdrol()}')";

            if ($this->Iniciar()) {
                if ($elid = $this->Ejecutar($sql)) {
                    //$this->setIdmenu($elid);
                    $resp = true;
            } else {
                $this->setmensajeoperacion("Tabla->insertar: " . $this->getError());
            }
        } else {
            $this->setmensajeoperacion("Tabla->insertar: " . $this->getError());
        }
        return $resp;
    }

    public function modificar()
    {
        $resp = false;
        return $resp;
    }

    public function eliminar()
    {
        $resp = false;
        $sql = "DELETE FROM menurol WHERE idmenu='{$this->getObjmenu()->getIdmenu()}' AND idrol='{$this->getObjrol()->getIdrol()}'";
        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("Tabla->eliminar: " . $this->getError());
            }
        } else {
            $this->setmensajeoperacion("Tabla->eliminar: " . $this->getError());
        }
        return $resp;
    }

    public function listar($parametro = "")
    {
        $arreglo = array();
        $sql = "SELECT * FROM menurol ";
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    while ($row = $this->Registro()) {
                        $obj = new Menurol();
                        $obj->getObjMenu()->setIdmenu($row['idmenu']);
                        $obj->getObjRol()->setIdrol($row['idrol']);
                        $obj->cargar();
                        array_push($arreglo, $obj);
                    }
                }
            } else {
                $this->setmensajeoperacion("Tabla->listar: " . $this->getError());
            }
        }

        return $arreglo;
    }

    
    //FUNCION PARA GENERAR MENU
    public function listarDistinctMenu($parametro = "")
    {
        $arreglo = array();
        $sql = "SELECT * FROM menurol ";
        if ($parametro != "") {
            $sql = $parametro;
        }
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    while ($row = $this->Registro()) {
                        $obj = new Menurol();
                        if(isset($row['idmenu'])){
                            $obj->getObjMenu()->setIdmenu($row['idmenu']);
                        }
                        if(isset($row['idrol'])){
                            $obj->getObjRol()->setIdrol($row['idrol']);
                        }
                        $obj->cargarDistinct();
                        array_push($arreglo, $obj);
                    }
                }
            } else {
                $this->setmensajeoperacion("Tabla->listar: " . $this->getError());
            }
        }

        return $arreglo;
    }

    private function cargarDistinct()
    {
        $resp = false;
        $sql = "SELECT * FROM menurol WHERE idmenu = '{$this->getObjmenu()->getIdmenu()}' ";
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $this->Registro();

                    $objMenu= new Menu();
                    $objMenu->setIdmenu($row['idmenu']);
                    $objMenu->cargar();

                    $objRol = new Rol();
                    // $objRol->setIdrol($row['idrol']);
                    // $objRol->cargar();

                    $this->setear($objMenu,$objRol);
                }
            }
        } else {
            $this->setmensajeoperacion("Tabla->cargar: " . $this->getError());
        }
        return $resp;
    }
}

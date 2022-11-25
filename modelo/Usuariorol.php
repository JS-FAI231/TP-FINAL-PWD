<?php
class Usuariorol extends BaseDatos
{
    private $objusuario;
    private $objrol;
    private $mensajeoperacion;


    public function __construct()
    {
        parent::__construct();
        $this->objusuario = new Usuario();
        $this->objrol = new Rol();
        $this->mensajeoperacion = '';
    }

    public function setear($objusuario, $objrol)
    {
        $this->setObjusuario($objusuario);
        $this->setObjrol($objrol);
    }

    public function setearConClave($idusuario,$idrol){
        $this->getObjusuario()->setIdusuario($idusuario);
        $this->getObjrol()->setIdrol($idrol);
    }

    public function setObjusuario($objusuario)
    {
        $this->objusuario = $objusuario;
    }
    public function setObjrol($objrol)
    {
        $this->objrol = $objrol;
    }
    public function setMensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function getObjusuario()
    {
        return $this->objusuario;
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
        $sql = "SELECT * FROM usuariorol WHERE idusuario = '{$this->getObjusuario()->getIdusuario()}' AND idrol= '{$this->getObjrol()->getIdrol()}'";
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $this->Registro();

                    $objUsuario= new Usuario();
                    $objUsuario->setIdusuario($row['idusuario']);
                    $objUsuario->cargar();

                    $objRol = new Rol();
                    $objRol->setIdrol($row['idrol']);
                    $objRol->cargar();

                    $this->setear($objUsuario,$objRol);
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
        $sql = "INSERT INTO usuariorol (idusuario, idrol) 
        VALUES ('{$this->getObjusuario()->getIdusuario()}','{$this->getObjrol()->getIdrol()}');";

        if ($this->Iniciar()) {
            if ($elid = $this->Ejecutar($sql)) {
                //$this->setIdusuario($elid);
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
        $sql = "DELETE FROM usuariorol WHERE idusuario='{$this->getObjusuario()->getIdusuario()}' AND idrol='{$this->getObjrol()->getIdrol()}'";
        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                return true;
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
        $sql = "SELECT * FROM usuariorol ";
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    while ($row = $this->Registro()) {
                        $obj = new Usuariorol();
                        $obj->getObjusuario()->setIdusuario($row['idusuario']);
                        $obj->getObjrol()->setIdrol($row['idrol']);

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

}

<?php
class Compra extends BaseDatos
{
    private $idcompra;
    private $fecha;
    //private $idusuario;
    private $objUsuario;
    private $mensajeoperacion;


    public function __construct()
    {
        parent::__construct();
        $this->idcompra = '';
        $this->fecha = date("Y-m-d H:i:s");
        //$this->idusuario = '';
        $this->objUsuario = null;
        $this->mensajeoperacion = '';
    }

    public function setear($idcompra, $fecha, $objUsuario)
    {
        $this->setIdcompra($idcompra);
        $this->setFecha($fecha);
        $this->setObjUsuario($objUsuario);
    }

    public function setIdcompra($idcompra)
    {
        $this->idcompra = $idcompra;
    }
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }
    // public function setIdusuario($idusuario)
    // {
    //     $this->idusuario = $idusuario;
    // }
    public function setObjUsuario($objUsuario)
    {
        $this->objUsuario = $objUsuario;
    }
    public function setMensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function getIdcompra()
    {
        return $this->idcompra;
    }
    public function getFecha()
    {
        return $this->fecha;
    }
    // public function getIdusuario()
    // {
    //     return $this->idusuario;
    // }
    public function getObjUsuario()
    {
        return $this->objUsuario;
    }
    public function getMensajeoperacion()
    {
        return $this->mensajeoperacion;
    }

    public function cargar()
    {
        $resp = false;
        $sql = "SELECT * FROM compra WHERE idcompra = '{$this->getIdcompra()}'";

        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $this->Registro();
                    /*Delegacion*/
                    $objUsuario=null;
                    if ($row['idusuario'] != null){
                        $objUsuario=new Usuario();
                        $objUsuario->setIdusuario($row['idusuario']);
                        $objUsuario->cargar();
                    }
                    /*Fin Delegacion*/
                    $this->setear(
                        $row['idcompra'],
                        $row['fecha'],
                        $objUsuario
                    );
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
        $sql = "INSERT INTO compra (idcompra,fecha,idusuario) 
        VALUES ('{$this->getIdcompra()}','{$this->getFecha()}',";
        if ($this->getObjUsuario()!=null){
            $sql .= "'{$this->getObjUsuario()->getIdusuario()}');";
        }else{
            $sql .= "null );";
        }

        if ($this->Iniciar()) {
            if ($elid = $this->Ejecutar($sql)) {
                $this->setIdcompra($elid);
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
        $sql = "UPDATE compra 
        SET fecha='{$this->getFecha()}',";
        if ($this->getObjUsuario()!=null){
            $sql .= "idusuario='{$this->getObjUsuario()->getIdusuario()}');";
        }else{
            $sql .= "idusuario=null );";
        }
        $sql .= "WHERE idcompra='{$this->getIdcompra()}'";

        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("Tabla->modificar: " . $this->getError());
            }
        } else {
            $this->setmensajeoperacion("Especie->modificar: " . $this->getError());
        }
        return $resp;
    }

    public function eliminar()
    {
        $resp = false;
        $sql = "DELETE FROM compra WHERE idcompra='{$this->getIdcompra()}'";

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

    public function listar($parametro = "", $orden = "")
    {
        $arreglo = array();
        $sql = "SELECT * FROM compra ";

        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        if ($orden != "") {
            $sql .= "ORDER BY " . $orden;
        }
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    while ($row = $this->Registro()) {
                        $obj = new Compra();

                        /*Delegacion*/
                        $objUsuario=null;
                        if ($row['idusuario'] != null){
                            $objUsuario=new Usuario();
                            $objUsuario->setIdusuario($row['idusuario']);
                            $objUsuario->cargar();
                        }
                        /*Fin Delegacion*/

                        $obj->setear(
                            $row['idcompra'],
                            $row['fecha'],
                            $objUsuario
                        );
                        array_push($arreglo, $obj);
                    }
                }
            } else {
                $this->setmensajeoperacion("Tabla->listar: " . $this->getError());
            }
        }

        return $arreglo;
    }
    public function listar_in_session($parametro = "")
    {
        $arreglo = array();
        $sql = "SELECT * FROM compra ";

        if ($parametro != "") {
            $sql .= $parametro;
        }
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    while ($row = $this->Registro()) {
                        $obj = new Compra();

                        /*Delegacion*/
                        $objUsuario=null;
                        if ($row['idusuario'] != null){
                            $objUsuario=new Usuario();
                            $objUsuario->setIdusuario($row['idusuario']);
                            $objUsuario->cargar();
                        }
                        /*Fin Delegacion*/

                        $obj->setear(
                            $row['idcompra'],
                            $row['fecha'],
                            $objUsuario
                        );
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

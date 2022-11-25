<?php
class Compraestado extends BaseDatos
{
    private $idcompraestado;
    private $idcompra;
    private $idcompraestadotipo;
    private $objCompraestadotipo;
    private $fechaini;
    private $fechafin;
    private $mensajeoperacion;


    public function __construct()
    {
        parent::__construct();
        $this->idcompraestado = '';
        $this->idcompra = '';
        $this->idcompraestadotipo = '';
        $this->objCompraestadotipo = null;
        $this->fechaini = date("Y-m-d H:i:s");
        $this->fechafin = '';
        $this->mensajeoperacion = '';
    }

    public function setear($idcompraestado, $idcompra, $objCompraestadotipo, $fechaini, $fechafin)
    {
        $this->setIdcompraestado($idcompraestado);
        $this->setIdcompra($idcompra);
        //$this->setIdcompraestadotipo($idcompraestadotipo);
        $this->setObjCompraestadotipo($objCompraestadotipo);
        $this->setFechaini($fechaini);
        $this->setFechafin($fechafin);
    }

    public function setIdcompraestado($idcompraestado)
    {
        $this->idcompraestado = $idcompraestado;
    }
    public function setIdcompra($idcompra)
    {
        $this->idcompra = $idcompra;
    }
    public function setIdcompraestadotipo($idcompraestadotipo)
    {
        $this->idcompraestadotipo = $idcompraestadotipo;
    }
    public function setObjCompraestadotipo($objCompraestadotipo)
    {
        $this->objCompraestadotipo = $objCompraestadotipo;
    }
    public function setFechaini($fechaini)
    {
        $this->fechaini = $fechaini;
    }
    public function setFechafin($fechafin)
    {
        $this->fechafin = $fechafin;
    }
    public function setMensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }


    public function getIdcompraestado()
    {
        return $this->idcompraestado;
    }
    public function getIdcompra()
    {
        return $this->idcompra;
    }
    public function getIdcompraestadotipo()
    {
        return $this->idcompraestadotipo;
    }
    public function getObjCompraestadotipo()
    {
        return $this->objCompraestadotipo;
    }
    public function getFechaini()
    {
        return $this->fechaini;
    }
    public function getFechafin()
    {
        return $this->fechafin;
    }
    public function getMensajeoperacion()
    {
        return $this->mensajeoperacion;
    }

    public function cargar()
    {
        $resp = false;
        $sql = "SELECT * FROM compraestado WHERE idcompraestado = " . $this->getIdcompraestado();
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $this->Registro();
                    /*Delegacion*/
                    $objCompraestadotipo = null;
                    if ($row['idcompraestadotipo'] != null) {
                        $objCompraestadotipo = new Compraestadotipo();
                        $objCompraestadotipo->setIdcompraestadotipo($row['idcompraestadotipo']);
                        $objCompraestadotipo->cargar();
                    }
                    /*Fin Delegacion*/
                    $this->setear(
                        $row['idcompraestado'],
                        $row['idcompra'],
                        $objCompraestadotipo,
                        $row['fechaini'],
                        $row['fechafin']
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
        $sql = "INSERT INTO compraestado (idcompraestado,idcompra,idcompraestadotipo,fechaini,fechafin) 
        VALUES ('{$this->getIdcompraestado()}',
        '{$this->getIdcompra()}',";
        if ($this->getObjCompraestadotipo() != null) {
            $sql .= "'{$this->getObjCompraestadotipo()->getIdcompraestadotipo()}',";
        } else {
            $sql .= "null,";
        }
        $sql .= "'{$this->getFechaini()}',
                 '{$this->getFechafin()}');";

        if ($this->Iniciar()) {
            if ($elid = $this->Ejecutar($sql)) {
                $this->setIdcompraestado($elid);
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
        $sql = "UPDATE compraestado 
        SET idcompra='{$this->getIdcompra()}',";
        if ($this->getObjCompraestadotipo() != null) {
            $sql .= "idcompraestadotipo='{$this->getObjCompraestadotipo()->getIdcompraestadotipo()}',";
        } else {
            $sql .= "null,";
        }
        $sql .= "fechaini='{$this->getFechaini()}',
        fechafin='{$this->getFechafin()}' WHERE idcompraestado='{$this->getIdcompraestado()}'";

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
        $sql = "DELETE FROM compraestado WHERE idcompraestado='{$this->getIdcompraestado()}'";
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
        $sql = "SELECT * FROM compraestado ";
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    while ($row = $this->Registro()) {
                        $obj = new Compraestado();
                        /*Delegacion*/
                        $objCompraestadotipo = null;
                        if ($row['idcompraestadotipo'] != null) {
                            $objCompraestadotipo = new Compraestadotipo();
                            $objCompraestadotipo->setIdcompraestadotipo($row['idcompraestadotipo']);
                            $objCompraestadotipo->cargar();
                        }
                        /*Fin Delegacion*/
                        $obj->setear(
                            $row['idcompraestado'],
                            $row['idcompra'],
                            $objCompraestadotipo,
                            $row['fechaini'],
                            $row['fechafin']
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

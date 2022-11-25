<?php
class Compraestadotipo extends BaseDatos
{
    private $idcompraestadotipo;
    private $descripcion;
    private $detalle;
    private $mensajeoperacion;


    public function __construct()
    {
        parent::__construct();
        $this->idcompraestadotipo = '';
        $this->descripcion = '';
        $this->detalle = '';
        $this->mensajeoperacion = '';
    }

    public function setear($idcompraestadotipo, $descripcion, $detalle)
    {
        $this->setIdcompraestadotipo($idcompraestadotipo);
        $this->setDescripcion($descripcion);
        $this->setDetalle($detalle);
    }

    public function setIdcompraestadotipo($idcompraestadotipo)
    {
        $this->idcompraestadotipo = $idcompraestadotipo;
    }
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;
    }
    public function setMensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function getIdcompraestadotipo()
    {
        return $this->idcompraestadotipo;
    }
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    public function getDetalle()
    {
        return $this->detalle;
    }
    public function getMensajeoperacion()
    {
        return $this->mensajeoperacion;
    }

    public function cargar()
    {
        $resp = false;
        $sql = "SELECT * FROM compraestadotipo WHERE idcompraestadotipo = '{$this->getIdcompraestadotipo()}'";

        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $this->Registro();
                    $this->setear(
                        $row['idcompraestadotipo'],
                        $row['descripcion'],
                        $row['detalle']
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
        $sql = "INSERT INTO compraestadotipo 
(idcompraestadotipo,
descripcion,
detalle) 
VALUES ('{$this->getIdcompraestadotipo()}',
'{$this->getDescripcion()}',
'{$this->getDetalle()}');";

        if ($this->Iniciar()) {
            if ($elid = $this->Ejecutar($sql)) {
                $this->setIdcompraestadotipo($elid);
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
        $sql = "UPDATE compraestadotipo 
SET descripcion='{$this->getDescripcion()}',
detalle='{$this->getDetalle()}' 
WHERE idcompraestadotipo='{$this->getIdcompraestadotipo()}'";

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
        $sql = "DELETE FROM compraestadotipo WHERE idcompraestadotipo='{$this->getIdcompraestadotipo()}'";

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
        $sql = "SELECT * FROM compraestadotipo ";

        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    while ($row = $this->Registro()) {
                        $obj = new Compraestadotipo();
                        $obj->setear(
                            $row['idcompraestadotipo'],
                            $row['descripcion'],
                            $row['detalle']
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

<?php
class Compraitem extends BaseDatos
{
    private $idcompraitem;
    //private $idproducto;
    private $objProducto;
    //private $idcompra;
    private $objCompra;
    private $cantidad;

    private $mensajeoperacion;


    public function __construct()
    {
        parent::__construct();
        $this->idcompraitem = '';
        //$this->idproducto = '';
        $this->objProducto = null;
        //$this->idcompra = '';
        $this->objCompra = null;
        $this->cantidad = '';
        $this->mensajeoperacion = '';
    }

    public function setear($idcompraitem, $objProducto, $objCompra, $cantidad)
    {
        $this->setIdcompraitem($idcompraitem);
        $this->setObjProducto($objProducto);
        $this->setObjCompra($objCompra);
        $this->setCantidad($cantidad);
    }

    public function setIdcompraitem($idcompraitem)
    {
        $this->idcompraitem = $idcompraitem;
    }
    // public function setIdproducto($idproducto)
    // {
    //     $this->idproducto = $idproducto;
    // }
    public function setObjProducto($objProducto)
    {
        $this->objProducto = $objProducto;
    }
    // public function setIdcompra($idcompra)
    // {
    //     $this->idcompra = $idcompra;
    // }
    public function setObjCompra($objCompra)
    {
        $this->objCompra = $objCompra;
    }
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    }
    public function setMensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function getIdcompraitem()
    {
        return $this->idcompraitem;
    }
    // public function getIdproducto()
    // {
    //     return $this->idproducto;
    // }
    public function getObjProducto()
    {
        return $this->objProducto;
    }
    // public function getIdcompra()
    // {
    //     return $this->idcompra;
    // }
    public function getObjCompra()
    {
        return $this->objCompra;
    }
    public function getCantidad()
    {
        return $this->cantidad;
    }
    public function getMensajeoperacion()
    {
        return $this->mensajeoperacion;
    }

    public function cargar()
    {
        $resp = false;
        $sql = "SELECT * FROM compraitem WHERE idcompraitem = '{$this->getIdcompraitem()}'";

        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $this->Registro();
                    /*Delegacion*/
                    if ($row['idproducto'] != null) {
                        $objProducto = new Producto();
                        $objProducto->setIdproducto($row['idproducto']);
                        $objProducto->cargar();
                    }
                    if ($row['idcompra'] != null) {
                        $objCompra = new Compra();
                        $objCompra->setIdcompra($row['idcompra']);
                        $objCompra->cargar();
                    }
                    /*Fin Delegacion*/
                    $this->setear(
                        $row['idcompraitem'],
                        $objProducto,
                        $objCompra,
                        $row['cantidad']
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
        $sql = "INSERT INTO compraitem (idcompraitem,idproducto,idcompra,cantidad) 
        VALUES ('{$this->getIdcompraitem()}',";
        if ($this->getObjProducto() != null) {
            $sql .= "'{$this->getObjProducto()->getIdproducto()}',";
        }else{
            $sql .= "null,";
        }
        if($this->getObjCompra() != null){
            $sql .= "'{$this->getObjCompra()->getIdcompra()}',";
        }else{
            $sql .= "null,";
        } 
        $sql .="'{$this->getCantidad()}');";

        if ($this->Iniciar()) {
            if ($elid = $this->Ejecutar($sql)) {
                $this->setIdcompraitem($elid);
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
        $sql = "UPDATE compraitem 
        SET ";
        if ($this->getObjProducto() != null) {
            $sql .= "idproducto='{$this->getObjProducto()->getIdproducto()}',";
        }else{
            $sql .= "idproducto=null,";
        }
        if($this->getObjCompra() != null){
            $sql .= "idcompra='{$this->getObjCompra()->getIdcompra()}',";
        }else{
            $sql .= "idcompra=null,";
        } 
        $sql .="cantidad='{$this->getCantidad()}' 
        WHERE idcompraitem='{$this->getIdcompraitem()}'";

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
        $sql = "DELETE FROM compraitem WHERE idcompraitem='{$this->getIdcompraitem()}'";

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
        $sql = "SELECT * FROM compraitem ";

        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    while ($row = $this->Registro()) {
                        $obj = new Compraitem();
                        /*Delegacion*/
                        if ($row['idproducto'] != null) {
                            $objProducto = new Producto();
                            $objProducto->setIdproducto($row['idproducto']);
                            $objProducto->cargar();
                        }
                        if ($row['idcompra'] != null) {
                            $objCompra = new Compra();
                            $objCompra->setIdcompra($row['idcompra']);
                            $objCompra->cargar();
                        }
                        /*Fin Delegacion*/
                        $obj->setear(
                            $row['idcompraitem'],
                            $objProducto,
                            $objCompra,
                            $row['cantidad']
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

<?php
class Menu extends BaseDatos
{
    private $idmenu;
    private $nombre;
    private $descripcion;
    private $idpadre;
    private $objMenu;
    private $deshabilitado;
    private $mensajeoperacion;


    public function __construct()
    {
        parent::__construct();
        $this->idmenu = '';
        $this->nombre = '';
        $this->descripcion = '';
        $this->idpadre = '';
        $this->objMenu = null;
        $this->deshabilitado = '';
        $this->mensajeoperacion = '';
    }

    public function setear($idmenu, $nombre, $descripcion, $objMenu, $deshabilitado)
    {
        $this->setIdmenu($idmenu);
        $this->setNombre($nombre);
        $this->setDescripcion($descripcion);
        $this->setObjMenu($objMenu);
        $this->setDeshabilitado($deshabilitado);
    }

    public function setIdmenu($idmenu)
    {
        $this->idmenu = $idmenu;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }
    public function setIdpadre($idpadre)
    {
        $this->idpadre = $idpadre;
    }
    public function setDeshabilitado($deshabilitado)
    {
        $this->deshabilitado = $deshabilitado;
    }
    public function setMensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }
    public function setObjMenu($objMenu)
    {
        $this->objMenu = $objMenu;
    }

    public function getIdmenu()
    {
        return $this->idmenu;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    public function getIdpadre()
    {
        return $this->idpadre;
    }
    public function getDeshabilitado()
    {
        return $this->deshabilitado;
    }
    public function getMensajeoperacion()
    {
        return $this->mensajeoperacion;
    }
    public function getObjMenu()
    {
        return $this->objMenu;
    }

    public function cargar()
    {
        $resp = false;
        $sql = "SELECT * FROM menu WHERE idmenu = '{$this->getIdmenu()}'";
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $this->Registro();
                    /*Delegacion*/
                    $objMenuPadre = null;
                    if ($row['idpadre'] != null or $row['idpadre'] != '') {
                        $objMenuPadre = new Menu();
                        $objMenuPadre->setIdmenu($row['idpadre']);
                        $objMenuPadre->cargar();
                    }
                    /*Fin Delegacion*/
                    $this->setear(
                        $row['idmenu'],
                        $row['nombre'],
                        $row['descripcion'],
                        $objMenuPadre,
                        $row['deshabilitado']
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

        $sql = "INSERT INTO menu (nombre,descripcion,idpadre,deshabilitado) 
        VALUES ('{$this->getNombre()}',
        '{$this->getDescripcion()}',";

        if ($this->getObjMenu() != null) {
            $sql .= "'{$this->getObjMenu()->getIdMenu()}',";
        } else {
            $sql .= "null,";
        }
        $sql .= "'{$this->getDeshabilitado()}')";

        if ($this->Iniciar()) {
            if ($elid = $this->Ejecutar($sql)) {
                $this->setIdmenu($elid);
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
        $sql = "UPDATE menu 
                SET nombre='{$this->getNombre()}',
                descripcion='{$this->getDescripcion()}',";

        if ($this->getObjMenu() != null) {
            $sql .= "idpadre='{$this->getObjMenu()->getIdMenu()}',";
        } else {
            $sql .= "idpadre=null,";
        }
        $sql .= "deshabilitado='{$this->getDeshabilitado()}' 
                WHERE idmenu='{$this->getIdmenu()}'";

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
        $sql = "DELETE FROM menu WHERE idmenu='{$this->getIdmenu()}'";
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
        $sql = "SELECT * FROM menu ";
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    while ($row = $this->Registro()) {
                        $obj = new Menu();
                        /*Delegacion*/
                        $objMenuPadre = null;
                        if ($row['idpadre'] != null) {
                            $objMenuPadre = new Menu();
                            $objMenuPadre->setIdmenu($row['idpadre']);
                            $objMenuPadre->cargar();
                        }
                        /*Fin Delegacion*/
                        $obj->setear(
                            $row['idmenu'],
                            $row['nombre'],
                            $row['descripcion'],
                            $objMenuPadre,
                            $row['deshabilitado']
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

    // public function buscar($parametro)
    // {
    //     $consulta = "SELECT * FROM auto WHERE $parametro";
    //     $resp = false;
    //     if ($this->Iniciar()) {
    //         if ($this->Ejecutar($consulta)) {
    //             if ($row = $this->Registro()) {
    //                 $this->setear(
    //                     $row['idmenu'],
    //                     $row['nombre'],
    //                     $row['descripcion'],
    //                     $row['idpadre'],
    //                     $row['deshabilitado']
    //                 );

    //                 /* EMEMPLO DELEGACION 
    //                 $objPersona=new Persona;
    //                 if ($objPersona->buscar($this->getDniDuenio())){
    //                     $this->setObjPersona($objPersona);
    //                 }
    //                 FIN EJEMPLO DELEGACION */

    //                 $resp = true;
    //             }
    //         } else {
    //             $this->setMensajeoperacion($this->getError());
    //         }
    //     } else {
    //         $this->setMensajeoperacion($this->getError());
    //     }
    //     return $resp;
    // }
}

<?php
class Producto extends BaseDatos
{
    private $idproducto;
    private $nombre;
    private $detalle;
    private $cantstock;
    private $artista;
    private $album;
    private $foto;
    private $mensajeoperacion;


    public function __construct()
    {
        parent::__construct();
        $this->idproducto = '';
        $this->nombre = '';
        $this->detalle = '';
        $this->cantstock = '';
        $this->artista = '';
        $this->album = '';
        $this->foto = '';
        $this->mensajeoperacion = '';
    }

    public function setear($idproducto, $nombre, $detalle, $cantstock, $artista, $album, $foto)
    {
        $this->setIdproducto($idproducto);
        $this->setNombre($nombre);
        $this->setDetalle($detalle);
        $this->setCantstock($cantstock);
        $this->setArtista($artista);
        $this->setAlbum($album);
        $this->setFoto($foto);
    }

    public function setIdproducto($idproducto)
    {
        $this->idproducto = $idproducto;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;
    }
    public function setCantstock($cantstock)
    {
        $this->cantstock = $cantstock;
    }
    public function setArtista($artista)
    {
        $this->artista = $artista;
    }
    public function setAlbum($album)
    {
        $this->album = $album;
    }
    public function setFoto($foto)
    {
        $this->foto = $foto;
    }
    public function setMensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function getIdproducto()
    {
        return $this->idproducto;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getDetalle()
    {
        return $this->detalle;
    }
    public function getCantstock()
    {
        return $this->cantstock;
    }
    public function getArtista()
    {
        return $this->artista;
    }
    public function getAlbum()
    {
        return $this->album;
    }
    public function getFoto()
    {
        return $this->foto;
    }
    public function getMensajeoperacion()
    {
        return $this->mensajeoperacion;
    }

    public function cargar()
    {
        $resp = false;
        $sql = "SELECT * FROM producto WHERE idproducto = '{$this->getIdproducto()}'";

        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $this->Registro();
                    $this->setear(
                        $row['idproducto'],
                        $row['nombre'],
                        $row['detalle'],
                        $row['cantstock'],
                        $row['artista'],
                        $row['album'],
                        $row['foto']
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
        $sql = "INSERT INTO producto 
(idproducto,
nombre,
detalle,
cantstock,
artista,
album,
foto) 
VALUES ('{$this->getIdproducto()}',
'{$this->getNombre()}',
'{$this->getDetalle()}',
'{$this->getCantstock()}',
'{$this->getArtista()}',
'{$this->getAlbum()}',
'{$this->getFoto()}');";

        if ($this->Iniciar()) {
            if ($elid = $this->Ejecutar($sql)) {
                $this->setIdproducto($elid);
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
        $sql = "UPDATE producto 
SET nombre='{$this->getNombre()}',
detalle='{$this->getDetalle()}',
cantstock='{$this->getCantstock()}',
artista='{$this->getArtista()}',
album='{$this->getAlbum()}',
foto='{$this->getFoto()}' 
WHERE idproducto='{$this->getIdproducto()}'";

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
        $sql = "DELETE FROM producto WHERE idproducto='{$this->getIdproducto()}'";

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

    public function listar($parametro = "",$orden="",$limite_de_filas="",$distinct="")
    {
        $arreglo = array();
        if($distinct!=""){
            $sql = "SELECT DISTINCT ".$distinct." FROM producto ";
        }else{
            $sql = "SELECT * FROM producto ";
        }

        if ($parametro != "") {
            $sql .= " WHERE " . $parametro;
        }
        if($orden!=""){
            $sql.=" ORDER BY ".$orden;
        }
        if($limite_de_filas!=""){
            $sql.=" LIMIT ".$limite_de_filas;  //ejemplo $limite de filas="0,5"
        }
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    while ($row = $this->Registro()) {
                        $obj = new Producto();
                        $obj->setear(
                            array_key_exists('idproducto',$row) ? $row['idproducto'] : '',
                            array_key_exists('nombre',$row) ? $row['nombre'] : '',
                            array_key_exists('detalle',$row) ? $row['detalle'] : '',
                            array_key_exists('cantstock',$row) ? $row['cantstock'] : '',
                            array_key_exists('artista',$row) ? $row['artista'] : '',
                            array_key_exists('album',$row) ? $row['album'] : '',
                            array_key_exists('foto',$row) ? $row['foto'] : ''
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
    public function getTotalFilas($where = ""){
        $cantidad=0;
        $sql = "SELECT count(*) as cantidad FROM producto ";
        if ($where != "") {
            $sql .= " WHERE " . $where;
        }
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    while ($row = $this->Registro()) {
                        $cantidad=$row['cantidad'];
                    }
                }
            } else {
                $this->setmensajeoperacion("Tabla->getTotalFilas: " . $this->getError());
            }
        }
        return $cantidad;
    }

    public function importar($sql)
    {
        $resp = false;

        if ($this->Iniciar()) {
            try{
                $this->Ejecutar($sql);
            }catch(Exception $e){
                $resp = false;
                $this->setmensajeoperacion("Tabla->insertar: " . $this->getError());
            }
        } else {
            $this->setmensajeoperacion("Tabla->insertar: " . $this->getError());
        }
        return $resp;
    }
}

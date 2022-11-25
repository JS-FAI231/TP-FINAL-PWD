<?php
class Usuario extends BaseDatos
{
    private $idusuario;
    private $nombre;
    private $pass;
    private $mail;
    private $deshabilitado;
    private $mensajeoperacion;


    public function __construct()
    {
        parent::__construct();
        $this->idusuario = '';
        $this->nombre = '';
        $this->pass = '';
        $this->mail = '';
        $this->deshabilitado = '';
        $this->mensajeoperacion = '';
    }

    public function setear($idusuario, $nombre, $pass, $mail, $deshabilitado)
    {
        $this->setIdusuario($idusuario);
        $this->setNombre($nombre);
        $this->setPass($pass);
        $this->setMail($mail);
        $this->setDeshabilitado($deshabilitado);
    }

    public function setIdusuario($idusuario)
    {
        $this->idusuario = $idusuario;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setPass($pass)
    {
        $this->pass = $pass;
    }
    public function setMail($mail)
    {
        $this->mail = $mail;
    }
    public function setDeshabilitado($deshabilitado)
    {
        $this->deshabilitado = $deshabilitado;
    }
    public function setMensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function getIdusuario()
    {
        return $this->idusuario;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getPass()
    {
        return $this->pass;
    }
    public function getMail()
    {
        return $this->mail;
    }
    public function getDeshabilitado()
    {
        return $this->deshabilitado;
    }
    public function getMensajeoperacion()
    {
        return $this->mensajeoperacion;
    }

    public function cargar()
    {
        $resp = false;
        $sql = "SELECT * FROM usuario WHERE idusuario = '{$this->getIdusuario()}'";
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $this->Registro();
                    $this->setear(
                        $row['idusuario'],
                        $row['nombre'],
                        $row['pass'],
                        $row['mail'],
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
        $sql = "INSERT INTO usuario 
        (idusuario,
        nombre,
        pass,
        mail,
        deshabilitado) VALUES 
        ('{$this->getIdusuario()}',
        '{$this->getNombre()}',
        '{$this->getPass()}',
        '{$this->getMail()}',
        '{$this->getDeshabilitado()}');";

        if ($this->Iniciar()) {
            if ($elid = $this->Ejecutar($sql)) {
                $this->setIdusuario($elid);
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
        $sql = "UPDATE usuario 
        SET nombre='{$this->getNombre()}',
        pass='{$this->getPass()}',
        mail='{$this->getMail()}',
        deshabilitado='{$this->getDeshabilitado()}' 
        WHERE idusuario='{$this->getIdusuario()}'";

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
        $sql = "DELETE FROM usuario WHERE idusuario='{$this->getIdusuario()}'";
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
        $sql = "SELECT * FROM usuario ";
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
                        $obj = new Usuario();
                        $obj->setear(
                            $row['idusuario'],
                            $row['nombre'],
                            $row['pass'],
                            $row['mail'],
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
}

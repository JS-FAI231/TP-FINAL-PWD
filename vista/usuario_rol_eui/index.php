<?php
include_once "../../configuracion.php";
include_once("../estructura/encabezado.php");

?>

<html>

<head>
    <meta charset="UTF-8">
    <title>Usuarios-Rol</title>

    <link rel="stylesheet" type="text/css" href="../jquery-easyui-1.10.8/themes/black/easyui.css">
    <link rel="stylesheet" type="text/css" href="../jquery-easyui-1.10.8/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="../jquery-easyui-1.10.8/themes/color.css">

    <script type="text/javascript" src="../jquery-easyui-1.10.8/jquery.min.js"></script>
    <script type="text/javascript" src="../jquery-easyui-1.10.8/jquery.easyui.min.js"></script>

    <script type="text/javascript" src="../jquery-easyui-1.10.8/datagrid-detailview.js"></script>

</head>

<body>
    <div id="tienda_bg" class="container-fluid p-5 my-1">
        <div class="row">
            <div class="col-sm-12">
                
                <h2><img src=".\..\img\vinyl_transp.png" style="width:auto;height:80px"> Asignacion de Roles a Usuarios</h2>
                <p></p>
                <br>
                <table id="dg" style="width:100%;height:450px" url="listar.php" title="Asignar Roles" singleselect="true" fitcolumns="true" toolbar="#toolbar" pagination="true" rownumbers="true">
                    <thead>
                        <tr>
                            <th field="idusuario" width="5%" sortable="true">ID</th>
                            <th field="nombre" width="20%" sortable="true">Nombre</th>
                            <th field="pass" width="20" sortable="true" hidden>Clave</th>
                            <th field="mail" width="40" sortable="true">e-Mail</th>
                            <th field="deshabilitado" width="15%" sortable="true">Deshabilitado</th>
                        </tr>
                    </thead>
                </table>
                <div id="toolbar" style="padding:3px">
                    <div class="row">
                        <div class="col-sm-8">

                            <?php
                            $objABMRol = new ABMRol();
                            $listaRol = $objABMRol->buscar(null);
                            //Defino los botones para cada Rol
                            if (count($listaRol) > 0) {
                                foreach ($listaRol as $objRol) { ?>
                                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addrol(<?php echo $objRol->getIdrol(); ?>)"><?php echo $objRol->getDescripcion(); ?> </a>
                            <?php
                                }
                            }
                            ?>

                        </div>
                        <div class="col-sm-4">
                            <input id="nombre" style="line-height:26px;border:1px solid #ccc">
                            <a href="#" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="doSearch()">Buscar</a>
                        </div>
                    </div>
                </div>
                <div id="dlg" class="easyui-dialog" style="width:600px" data-options="closed:true,border:'thin',buttons:'#dlg-buttons'">
                    <form id="fm" method="post" novalidate style="margin:0;padding:20px 50px">
                        <h3>Asignar Rol</h3>
                        <div style="margin-bottom:10px">
                            <input name="rol" id="rol" class="easyui-textbox" required="true" label="Rol:" style="width:100%">
                        </div>
                    </form>
                </div>
                <div id="dlg-buttons">
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="grabar()" style="width:90px">Aceptar</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancelar</a>
                </div>

            </div>
        </div>
    </div>

</body>

<script type="text/javascript">
    function doSearch() {
        if ($('#nombre').val() != '') {
            $('#dg').datagrid('load', {
                txbuscar: $('#nombre').val(),
                operacion_like: true
            });
        } else {
            $('#dg').datagrid('load', {});
        }
    }

    function addrol(idrol) {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            $.messager.confirm('Confirm', 'Seguro que desea asignar este rol?', function(r) {
                if (r) {

                    $.post('./accion.php?accion=nuevo_rol&idrol=' + idrol + '&idusuario=' + row.idusuario, {
                            idusuario: row.id
                        },
                        function(result) {
                            //alert("Volvio Servidor");
                            if (result.respuesta) {
                                $.messager.show({
                                    title: 'Exito ',
                                    msg: result.errorMsg
                                });

                                $('#dg').datagrid('reload'); // reload the  data
                            } else {
                                $.messager.show({ // show error message
                                    title: 'Error',
                                    msg: result.errorMsg
                                });
                            }
                        }, 'json');
                }
            });
        }
    }

    function deleterol(idusuario, idrol) {
        //alert('idusuario '+ idusuario+' idrol '+idrol);
        $.messager.confirm('Confirm', 'Seguro que desea elminar este rol?', function(r) {
            if (r) {
                $.post('./accion.php?accion=borrar_rol&idrol=' + idrol + '&idusuario=' + idusuario, '',
                    function(result) {
                        //alert("Volvio Servidor");
                        if (result.respuesta) {
                            $.messager.show({
                                title: 'Exito ',
                                msg: result.errorMsg
                            });
                            $('#dg').datagrid('reload'); // reload the  data
                        } else {
                            $.messager.show({ // show error message
                                title: 'Error',
                                msg: result.errorMsg
                            });
                        }
                    }, 'json');
            }
        });
    }


    $(function() {
        $('#dg').datagrid({
            view: detailview,
            detailFormatter: function(index, row) {
                return '<div style="padding:2px;position:relative;">' +
                    '<table class="ddv">' +
                    '</table>' +
                    '</div>';
            },
            onExpandRow: function(index, row) {
                var idusuario = row.idusuario;
                var ddv = $(this).datagrid('getRowDetail', index).find('table.ddv');

                ddv.datagrid({
                    url: 'listar_detalle.php?idusuario=' + row.idusuario,
                    fitColumns: true,
                    singleSelect: true,
                    rownumbers: true,
                    loadMsg: '',
                    height: 'auto',
                    columns: [
                        [{
                                field: 'idrol',
                                title: 'ID',
                                width: 50
                            },
                            {
                                field: 'nombre',
                                title: 'Nombre del Rol',
                                width: 200
                            },
                            {
                                field: 'accion',
                                title: 'Accion',
                                width: 80,
                                align: 'center',
                                formatter: function(value, row, index) {
                                    var c = '<button type="button" class="btn btn-outline-warning btn-sm" onclick="deleterol(' + idusuario + ',' + row.idrol + ')">Eliminar</button>';
                                    return c;
                                }
                            }
                        ]
                    ],
                    onResize: function() {
                        $('#dg').datagrid('fixDetailRowHeight', index);
                    },
                    onLoadSuccess: function() {
                        setTimeout(function() {
                            $('#dg').datagrid('fixDetailRowHeight', index);
                        }, 0);
                    }

                });
                $('#dg').datagrid('fixDetailRowHeight', index);
            }
        });
    });

    $(function(){
        $("button").click(
            function(){
                $("p").hide();
            }
        );
    });
</script>

<style type="text/css">
    body {
        background-color: darkgrey;
    }

    #tienda_bg {
        background-color:crimson;
        color:azure;
    }
</style>

</html>
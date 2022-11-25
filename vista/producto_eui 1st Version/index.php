<?php
include_once "../../configuracion.php";
include_once("../estructura/encabezado.php");

$objControl = new ABMProducto();
$titulo = "<h2>Informacion de las productos</h2>";
$subtitulo = "<p>Podra realizar las siguientes acciones.</p>";

?>

<html>

<head>
    <meta charset="UTF-8">
    <title>ABM Productos</title>

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

                <h2><img src=".\..\img\vinyl_transp.png" style="width:auto;height:50px"> ABM Productos</h2>


                <table id="dg" title="Administrador de Productos" class="easyui-datagrid" style="width:100%;height:600px" toolbar="#toolbar" pagination="true" rownumbers="true" fitColumns="true" singleSelect="true" sortName="artista" sortOrder="asc" url="listar.php">
                    <thead>
                        <tr>
                            <th field="idproducto" width="5%" sortable="true">ID</th>
                            <th field="artista" width="20%" sortable="true">Artista</th>
                            <th field="album" width="65%" sortable="true">Album</th>
                            <th field="cantstock" width="10%" sortable="true">Cant.Stock</th>
                            <!-- <th field="foto",width="20%">Foto</th> -->
                        </tr>
                    </thead>
                </table>
                <div id="toolbar" style="padding:3px">
                    <div class="row">
                        <div class="col-sm-8">
                            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="nuevo()">Nuevo Producto</a>
                            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editar()">Editar Producto</a>
                            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="eliminar()">Baja Producto</a>
                        </div>
                        <div class="col-sm-4">
                            <input id="nombre" style="line-height:26px;border:1px solid #ccc">
                            <a href="#" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="doSearch()">Buscar</a>
                        </div>
                    </div>
                </div>

                <div id="dlg" class="easyui-dialog" style="width:600px" data-options="closed:true,border:'thin',buttons:'#dlg-buttons'">

                    <form id="fm" method="post" novalidate style="margin:0;padding:20px 50px">
                        <h3>Producto (Informacion)</h3>
                        <div style="margin-bottom:10px">
                            <input name="artista" id="artista" class="easyui-textbox" required="true" label="Nombre:" style="width:100%">
                        </div>
                        <div style="margin-bottom:10px">
                            <input name="album" id="album" class="easyui-textbox" required="true" label="Detalle:" style="width:100%">
                        </div>
                        <div style="margin-bottom:10px">
                            <input name="cantstock" id="cantstock" class="easyui-textbox" required="true" label="Stock:" style="width:100%">
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
    var url;

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

    function nuevo() {
        $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Nuevo Usuario');
        $('#fm').form('clear');
        url = './accion.php?accion=nuevo';
    }

    function editar() {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Editar Usuario');
            $('#fm').form('load', row);
            url = 'accion.php?accion=editar&idproducto=' + row.idproducto;
        }
    }

    function eliminar() {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            $.messager.confirm('Confirm', 'Seguro que desea eliminar el Producto?', function(r) {
                if (r) {
                    $.post('./accion.php?accion=borrar&idproducto=' + row.idproducto, {
                            idproducto: row.id
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

    function grabar() {
        //alert(" Accion "+url);
        $('#fm').form('submit', {
            url: url,
            onSubmit: function() {
                return $(this).form('validate');
            },
            success: function(result) {
                var result = eval('(' + result + ')');

                //alert("Volvio Servidor");
                if (result.respuesta) {
                    $.messager.show({
                        title: 'Exito ',
                        msg: result.errorMsg
                    });
                    $('#dlg').dialog('close'); // close the dialog
                    $('#dg').datagrid('reload'); // reload 

                } else {
                    $.messager.show({
                        title: 'Error',
                        msg: result.errorMsg
                    });
                }
            }
        });
    }
</script>

<style type="text/css">
    #tienda_bg {
        background-color: crimson;
        color: white;
    }
</style>

</html>
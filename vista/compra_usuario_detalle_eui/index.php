<?php
include_once "../../configuracion.php";
include_once("../estructura/encabezado.php");

?>

<html>

<head>
    <meta charset="UTF-8">
    <title>Tus Compras</title>

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

                <h2><img src=".\..\img\vinyl_transp.png" style="width:auto;height:80px"> Tus Compras</h2>


                <table id="dg" style="width:100%;height:640px" url="listar.php?idusuario=<?php echo $idusuario;?>" title="Tus Compras Realizadas" singleselect="true" fitcolumns="true" toolbar="#toolbar" pagination="true" rownumbers="false">
                    <thead>
                        <tr>
                            <th field="idcompra" width="20" sortable="true">ID Compra</th>
                            <th field="fecha" width="50" sortable="true">Fecha</th>
                            <th field="estado" width="50">Estado</th>
                            <th field="idusuario" width="10" sortable="true">ID Us.</th>
                            <th field="us_nombre" width="50">Usuario</th>
                        </tr>
                    </thead>
                </table>
                <div id="toolbar" style="padding:3px">
                    <div class="row">
                        <div class="col-sm-8">

                        </div>
                        <div class="col-sm-4">
                            <input id="nombre" style="line-height:26px;border:1px solid #ccc">
                            <a href="#" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="doSearch()">Buscar</a>
                        </div>
                    </div>
                </div>
                <div id="dlg" class="easyui-dialog" style="width:600px" data-options="closed:true,border:'thin',buttons:'#dlg-buttons'">
                    <form id="fm" method="post" novalidate style="margin:0;padding:20px 50px">
                        <h3>Acciones</h3>
                        <div style="margin-bottom:10px">
                            <input name="estado" id="estado" class="easyui-textbox" required="true" label="Estado:" style="width:100%">
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
        alert ('Valor: '+typeof($('#nombre').val()));
        if ($('#nombre').val() != '') {
            $('#dg').datagrid('load', {
                txbuscar: $('#nombre').val(),
                idusuario: <?php echo $idusuario?>,
                operacion_like: true
            });
        } else {
            $('#dg').datagrid('load', {});
        }
    }

    function addestado(idcompraestadotipo) {
        // var row = $('#dg').datagrid('getSelected');
        // if (row) {
        //     $.messager.confirm('Confirm', 'Seguro que desea asignar este estado?', function(r) {
        //         if (r) {

        //             $.post('./accion.php?accion=nuevo_estado&idcompraestadotipo=' + idcompraestadotipo +
        //                 '&idcompra=' + row.idcompra, {
        //                     idcompra: row.id
        //                 },
        //                 function(result) {
        //                     //alert("Volvio Servidor");
        //                     if (result.respuesta) {
        //                         $.messager.show({
        //                             title: 'Exito ',
        //                             msg: result.errorMsg
        //                         });

        //                         $('#dg').datagrid('reload'); // reload the  data
        //                     } else {
        //                         $.messager.show({ // show error message
        //                             title: 'Error',
        //                             msg: result.errorMsg
        //                         });
        //                     }
        //                 }, 'json');
        //         }
        //     });
        // }
    }

    function deleteestado(idcompra, idcompraestadotipo) {

        // $.messager.confirm('Confirm', 'Seguro que desea elminar este estado?', function(r) {
        //     if (r) {
        //         $.post('./accion.php?accion=borrar_estado&idcompraestadotipo=' + idcompraestadotipo + '&idcompra=' + idcompra, '',
        //             function(result) {
        //                 //alert("Volvio Servidor");
        //                 if (result.respuesta) {
        //                     $.messager.show({
        //                         title: 'Exito ',
        //                         msg: result.errorMsg
        //                     });
        //                     $('#dg').datagrid('reload'); // reload the  data
        //                 } else {
        //                     $.messager.show({ // show error message
        //                         title: 'Error',
        //                         msg: result.errorMsg
        //                     });
        //                 }
        //             }, 'json');
        //     }
        // });
    }


    $(function() {
        $('#dg').datagrid({
            view: detailview,
            detailFormatter: function(index, row) {
                return '<div style="padding:2px;position:relative;">' +
                    '<table id="dgchild" class="ddv">' +
                    '</table>' +
                    '</div>';
            },
            onExpandRow: function(index, row) {
                var idcompra = row.idcompra;
                var ddv = $(this).datagrid('getRowDetail', index).find('table.ddv');

                ddv.datagrid({
                    url: 'listar_detalle.php?idcompra=' + row.idcompra,
                    fitColumns: true,
                    singleSelect: true,
                    rownumbers: true,
                    loadMsg: '',
                    height: 'auto',
                    columns: [
                        [{
                                field: 'idproducto',
                                title: 'Codigo',
                                width: 5
                            },
                            {
                                field: 'foto',
                                title: 'Cover',
                                width: 9,
                                formatter: function(value, row, index) {
                                    var img = value.replace("F:/", "./../img/");
                                    //var c = '<button type="button" class="btn btn-outline-warning btn-sm" >Eliminar</button>';
                                    var d = '<img src="' + img + '" style="width:100px;height:100px;float:left">';
                                    return d;
                                }
                            },
                            {
                                field: 'artista',
                                title: 'Artista',
                                width: 20
                            },
                            {
                                field: 'album',
                                title: 'Album',
                                width: 55
                            },
                            {
                                field: 'idcompraitem',
                                title: 'ID',
                                width: 10,
                                hidden: 'true'
                            },

                            {
                                field: 'idcompra',
                                title: 'ID Compra',
                                width: 10,
                                hidden: 'true'
                            },
                            {
                                field: 'cantidad',
                                title: 'Cantidad',
                                width: 10
                            }
                            // ,
                            // {
                            //     field: 'accion',
                            //     title: 'Accion',
                            //     width: 80,
                            //     align: 'center',
                            //     formatter: function(value, row, index) {
                            //         var c = '<button type="button" class="btn btn-outline-warning btn-sm" onclick="deleteestado(' + idcompra + ',' + row.idcompraestadotipo + ')">Finalizar</button>';
                            //         return c;
                            //     }
                            // }
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
                    //
                });
                $('#dg').datagrid('fixDetailRowHeight', index);
            }
        });
    });
</script>

<style type="text/css">
    body {
        background-color: darkgrey;
    }

    #tienda_bg {
        background-color:#DEAF21;
        color: azure;
    }
</style>

</html>
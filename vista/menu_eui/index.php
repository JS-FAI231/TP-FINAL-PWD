<?php
include_once "../../configuracion.php";
include_once("../estructura/encabezado.php");

$objControl = new ABMMenu();

// //Datos para el combo idpadre * INI *
// $List_Menu = $objControl->buscar(null);

// $combo = '<select class="easyui-combobox" id="idpadre" name="idpadre" label="Submenu de:" labelPosition="left" style="width:100%;">
//             <option value="0"></option>';
// foreach ($List_Menu as $objMenu) {
//     $combo .= '<option value="' . $objMenu->getIdmenu() . '">' . $objMenu->getIdmenu() . ' -> ' . $objMenu->getNombre() . '</option>';
// }
// $combo .= '</select>';
// //Datos para el combo * FIN *

?>

<html>

<head>
    <meta charset="UTF-8">
    <title>ABM Menu</title>

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
                <h2><img src=".\..\img\vinyl_transp.png" style="width:auto;height:80px"> ABM Menu</h2>
                <p>Podra realizar las siguientes acciones.</p>
                <table id="dg" title="Administrador de items Menu" class="easyui-datagrid" style="width:100%;height:500px" toolbar="#toolbar" pagination="true" rownumbers="true" fitColumns="true" singleSelect="true" sortName="idmenu" sortOrder="asc" url="listar.php">
                    <thead>
                        <tr>
                            <th field="idmenu" width="5%" sortable="true">ID</th>
                            <th field="nombre" width="30%" sortable="true">Nombre</th>
                            <th field="descripcion" width="25%" sortable="true">Descripcion</th>
                            <th field="idpadre" width="5%" sortable="true">ID Padre:</th>
                            <th field="nombrepadre" width="20%" sortable="true">Nombre Padre:</th>
                            <th field="deshabilitado" width="15%" sortable="true">Deshabilitado</th>
                        </tr>
                    </thead>
                </table>
                <div id="toolbar" style="padding:3px">
                    <div class="row">
                        <div class="col-sm-8">
                            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="nuevo()">Nuevo Menu</a>
                            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editar()">Editar Menu</a>
                            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="eliminar()">Baja Menu</a>
                        </div>
                        <div class="col-sm-4">
                            <input id="nombre" style="line-height:26px;border:1px solid #ccc">
                            <a href="#" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="doSearch()">Buscar</a>
                        </div>
                    </div>

                    <!-- formulario modal -->
                    <div id="dlg" class="easyui-dialog" style="width:600px" data-options="closed:true,border:'thin',buttons:'#dlg-buttons'">

                        <form id="fm" method="post" novalidate style="margin:0;padding:5% 10%">
                            <h3>Menu (Informacion)</h3>
                            <div style="margin-bottom:10px">
                                <input name="nombre" id="nombre" class="easyui-textbox" required="true" label="Nombre:" style="width:100%">
                            </div>
                            <div style="margin-bottom:10px">
                                <input name="descripcion" id="descripcion" class="easyui-textbox" required="true" label="Descripcion:" style="width:100%">
                            </div>
                            <div style="margin-bottom:10px">
                                <?php
                                //Datos para el combo idpadre * INI *
                                $List_Menu = $objControl->buscar(null);
                                ?>

                                <select class="easyui-combobox" id="idpadre" name="idpadre" label="Submenu de:" labelPosition="left" style="width:100%;">
                                <option value="0"></option>';
                                <?php
                                foreach ($List_Menu as $objMenu) {
                                ?>
                                    <option value="<?php echo $objMenu->getIdmenu()?>"><?php echo ($objMenu->getIdmenu().' -> '.$objMenu->getNombre())?></option>;
                                <?php
                                }
                                ?>
                                </select>
                                <?php
                                //Datos para el combo * FIN *
                                //echo $combo;
                                ?>
                            </div>
                            <div style="margin-bottom:10px">
                                <input class="easyui-checkbox" id="deshabilitado" name="deshabilitado" value="deshabillitado" checked="true" label="Des-Habilitar:">
                            </div>
                        </form>
                        <!-- fin formulario modal -->
                    </div>
                    <div id="dlg-buttons">
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="grabar()" style="width:90px">Aceptar</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancelar</a>
                    </div>

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
        $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Nuevo Menu');
        $('#fm').form('clear');
        url = './accion.php?accion=nuevo';
    }

    function editar() {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Editar Menu');
            $('#fm').form('load', row);
            url = 'accion.php?accion=editar&idmenu=' + row.idmenu;
        }
    }

    function eliminar() {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            $.messager.confirm('Confirm', 'Seguro que desea eliminar el rol?', function(r) {
                if (r) {
                    $.post('./accion.php?accion=borrar&idmenu=' + row.idmenu, {
                            idmenu: row.id
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
    body {
        background-color: darkgrey;
    }

    #tienda_bg {
        background-color: crimson;
        color: azure;
    }
</style>

</html>
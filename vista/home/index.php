<?php
include_once("../../configuracion.php");
$datos = data_submitted_request();
$objSession=new Session();
if (!isset($datos['idus'])){
    $datos['idus']='';
}
if ($objSession->activa() and $datos['idus']==$objSession->getIdusuario()){
    include_once("../estructura/encabezado.php");
}else{
    include_once("../estructura/encabezado_publico.php");
}



if (isset($datos['inicarro']) and $datos['inicarro'] == '1') {
    $objSession->iniciarNuevoCarro();
}

//Obtengo los datos para el Combo con Artistas (fuente select ditinct artistas from productos)
$objControl = new ABMProducto();
$datos['distinct'] = 'artista';
$datos['orden'] = 'artista';
$arrLista = $objControl->buscar($datos);   //buscar(datos) devuelve un array con 2 elementos, el total de filas del where y los resultados where limit
$arrObjsProducto = $arrLista[1];    //recupero el array con los objsProducto que viene en la posicion 1;

//Obtengo datos de mensajes
if (isset($datos['msg'])){
    $mensaje=$datos['msg'];
}else{
    $mensaje="";
}
?>

<html>

<head>
    <!-- <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> -->
    <title>Tienda de Discos</title>

    <link rel="stylesheet" type="text/css" href="../jquery-easyui-1.10.8/themes/black/easyui.css">
    <link rel="stylesheet" type="text/css" href="../jquery-easyui-1.10.8/themes/icon.css">
    <script type="text/javascript" src="../jquery-easyui-1.10.8/jquery.min.js"></script>
    <script type="text/javascript" src="../jquery-easyui-1.10.8/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="../jquery-easyui-1.10.8/datagrid-detailview.js"></script>

</head>

<body>
    <!-- <a id="top"></a> Esta etiqueta pasa al script del encabezado-->
    <div id="tienda_bg" class="container-fluid p-5 my-1">
        <div class="row">
            <div class="col-sm-12">
                <h2><img src=".\..\img\vinyl_transp.png" style="width:auto;height:80px"> Tienda de Discos</h2>
                <p>12 singles, CD Singles, CD, LP Vinilos y + <?php echo $mensaje;?></p>

                <table id="tt" style="width:98%;height:1920px" title="Discos" singleSelect="true" fitColumns="true" url="listar.php" toolbar="#toolbar" pagination="true" remoteSort="true" sortOrder="asc" sortName="artista">
                    <thead>
                        <tr>
                            <th field="idproducto" width="80" sortable="true">Item ID</th>
                            <th field="artista" width="120" sortable="true">Artista</th>
                            <th field="album" width="80" sortable="true">Album</th>
                            <th field="cantstock" width="250" sortable="true">Cant Disp</th>
                            <th field="precio" width="60" sortable="true">Precio</th>
                        </tr>
                    </thead>
                </table>
                <div id="toolbar" style="padding:3px">
                    <!-- TOOLBAR -->
                    <div class="row">
                        <div class="col-sm-1">
                        </div>
                        <div class="col-sm-7">
                            <!-- Combo Artistas -->
                            <select class="easyui-combobox" id="cbo_artistas" name="cbo_artistas" label="Artistas:" labelPosition="left" style="width:100%;">
                                <option value="0"></option>;
                                <?php
                                // <!-- Llenado del combo artistas -->
                                foreach ($arrObjsProducto as $objProducto) {
                                ?>
                                    <option value="<?php echo $objProducto->getArtista() ?>"><?php echo $objProducto->getArtista() ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <!-- Fin Combo Artistas -->
                        </div>
                        <div class="col-sm-4">
                            <input id="nombre" style="line-height:26px;border:1px solid #ccc">
                            <a href="#" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="doSearch()">Buscar</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

<script>
    function doAddBasket(idproducto, idcompra) {

        //alert('agregando '+ idproducto +' al idcompra '+idcompra);
        var row = $('#tt').datagrid('getSelected');
        if (row) {
            $.messager.confirm('Confirm', 'Seguro que desea agregar al carro?', function(r) {
                if (r) {
                    $.post('./accion.php?accion=add_to_basket&idproducto=' + idproducto + '&idcompra=' + idcompra, {
                            idproducto: row.id
                        },
                        function(result) {
                            //alert("Volvio Servidor");
                            if (result.respuesta) {
                                $.messager.show({
                                    title: 'Exito ',
                                    msg: result.errorMsg
                                });
                                //location.href = "#top";
                                // $('#dg').datagrid('reload'); // reload the  data
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

    function doSearch() {
        //Nota, el combo vacio viene con '0'
        var combo = $('#cbo_artistas').val();
        if (combo == '0') {
            combo = '';
        }
        var texto = $('#nombre').val();

        if (combo == '' && texto == '') {
            //los 2 vacios
            $('#tt').datagrid('load', {});
        } else {
            if (combo != '' && texto == '') {
                //combo lleno y texto vacios
                $('#tt').datagrid('load', {
                    artista: $('#cbo_artistas').val()
                });
            } else {
                if (combo == '' && texto != '') {
                    //combo vacio y texto lleno 
                    $('#tt').datagrid('load', {
                        txbuscar: $('#nombre').val(),
                        operacion_like: true
                    });
                } else {
                    //los 2 llenos
                    $('#tt').datagrid('load', {
                        artista: $('#cbo_artistas').val(),
                        txbuscar: $('#nombre').val(),
                        operacion_like: true
                    });
                }
            }
        }
    }

    var cardview = $.extend({}, $.fn.datagrid.defaults.view, {
        renderRow: function(target, fields, frozen, rowIndex, rowData) {
            var cc = [];
            cc.push('<td colspan=' + fields.length + ' style="padding:10px 30px;border:0;">');
            if (!frozen && rowData.idproducto) {
                var img = rowData.foto;
                //img = img.replace("F:/Cdms/CDS/", "./../img/");
                img = img.replace("F:/", "./../img/");
                cc.push('<div style="float:left;margin-left:150px;width:90%">');
                cc.push('<img src="' + img + '" style="width:150px;height:150px;float:left">');

                var tabla = document.getElementById("tt");

                cc.push('<div style="float:left;margin-left:80px">');
                for (var i = 0; i < fields.length; i++) {
                    var copts = $(target).datagrid('getColumnOption', fields[i]);
                    cc.push('<span class="c-label">' + copts.title + ':</span> ' + rowData[fields[i]] + '<br>');
                }
                if (rowData.cantstock > 0) {
                    <?php
                    if (!$objSession->activa()) {?> 
                        cc.push('<br><button type="button" class="btn btn-outline-warning btn-sm" onmouseup="doAddBasket(' + rowData.idproducto + ',0)">Comprar</button>');
                    <?php
                    }else{?>
                        cc.push('<br><button type="button" class="btn btn-outline-warning btn-sm" onmouseup="doAddBasket(' + rowData.idproducto + ',' + <?php echo $objSession->getIdcompra(); ?> + ')">Comprar</button>');
                    <?php
                    }
                    ?>
        
                } else {
                    cc.push('<br><button type="button" class="btn btn-danger btn-sm" disabled>Sin Stock</button>');
                }
                cc.push('</div>');
                cc.push('</div>');
            }
            cc.push('</td>');
            return cc.join('');
        }
    });

    $(function() {
        $('#tt').datagrid({
            view: cardview
        });
    });

    $(function() {
        $('#tt').datagrid({
            onLoadSuccess: function() {
                location.href = "#top";
            }
        });
    });

    $(function(){
			var pager = $('#tt').datagrid('getPager');	// get the pager of datagrid
			pager.pagination({
                layout:['list','sep','first','last','links','refresh','info'],
                pageList: [10,20,50,100],
                showPageList:true,
				// showPageList:false,
				// buttons:[{
				// 	iconCls:'icon-search',
				// 	handler:function(){
				// 		alert('search');
				// 	}
				// },{
				// 	iconCls:'icon-add',
				// 	handler:function(){
				// 		alert('add');
				// 	}
				// },{
				// 	iconCls:'icon-edit',
				// 	handler:function(){
				// 		alert('edit');
				// 	}
				// }],
				onBeforeRefresh:function(){
					//alert('before refresh');
					return true;
				}
			});
		});
</script>

<style type="text/css">
    .c-label {
        display: inline-block;
        width: 100px;
    }

    body {
        background-color: darkgrey;
    }

    #tienda_bg {
        background-color: #3F9FF2;
        color: azure;
    }
</style>

</html>
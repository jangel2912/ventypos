<p><input type="file" name="xlfile" id="xlf" /> ... or click here to select a file</p>

<form id="importarP" ><!--style="display: none"-->
    <fieldset>
        <table class="userTable">
            <tbody>
                <tr>
                    <td>
                        <div id="restrictedUsers" class="campos">Categoria<br>&nbsp;<input type="hidden" name="categoria"></div>
                        <div id="restrictedUsers" class="campos">Código<br>&nbsp;<input type="hidden" name="codigo"></div>
                        <div id="restrictedUsers" class="campos">Nombre<br>&nbsp;<input type="hidden" name="nombre"></div>
                        <div id="restrictedUsers" class="campos">Precio Compra<br>&nbsp;<input type="hidden" name="precio_compra"></div>
                        <div id="restrictedUsers" class="campos">Precio Venta<br>&nbsp;<input type="hidden" name="precio_venta"></div>
                        <div id="restrictedUsers" class="campos">Descripción<br>&nbsp;<input type="hidden" name="descripcion"></div>
                        <div id="restrictedUsers" class="campos">Impuesto<br>&nbsp;<input type="hidden" name="impuesto"></div>
                        <div id="restrictedUsers" class="campos">Unidades<br>&nbsp;<input type="hidden" name="unidades"></div>
                        <div id="restrictedUsers" class="campos">Cantidad<br>&nbsp;<input type="hidden" name="cantidad"></div>
                        <div id="restrictedUsers" class="campos">Stock Minimo<br>&nbsp;<input type="hidden" name="stock_minimo"></div>
                        <div id="restrictedUsers" class="campos">Almacen<br>&nbsp;<input type="hidden" name="almacen"></div>
                    </td>
                    <td id="unassignedUsers" class="campos">
                        <a id="unassignedUsers" class="columna" href="1" draggable="true" style="cursor: move;">Campo 1</a>
                        <a id="unassignedUsers" class="columna" href="2" draggable="true" style="cursor: move;">Campo 2</a>
                        <a id="unassignedUsers" class="columna" href="3" draggable="true" style="cursor: move;">Campo 3</a>
                        <a id="unassignedUsers" class="columna" href="4" draggable="true" style="cursor: move;">Campo 4</a>
                        <a id="unassignedUsers" class="columna" href="5" draggable="true" style="cursor: move;">Campo 5</a>
                        <a id="unassignedUsers" class="columna" href="6" draggable="true" style="cursor: move;">Campo 6</a>
                        <a id="unassignedUsers" class="columna" href="7" draggable="true" style="cursor: move;">Campo 7</a>
                        <a id="unassignedUsers" class="columna" href="8" draggable="true" style="cursor: move;">Campo 8</a>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td id="unassignedUsersHelp">Drag a user from this list to another list to 
                                            change the user's permissions.</td>
                    <td id="restrictedUsersHelp">Dragging user here will give this user restricted
                                                    permissions</td>
                    <td id="powerUsersHelp">Dragging a user here will give this user
						power user access.</td>
                </tr>
            </tfoot>
        </table>
    </fieldset>
</form>
<!-- uncomment the next line here and in xlsxworker.js for encoding support -->
<!--<script src="dist/cpexcel.js"></script>-->
<script src="<?php echo base_url('public/js-xlsx-master/shim.js') ?>"></script> 
<script src="<?php echo base_url('public/js-xlsx-master/jszip.js') ?>"></script>
<script src="<?php echo base_url('public/js-xlsx-master/xlsx.js') ?>"></script>
<!-- uncomment the next line here and in xlsxworker.js for ODS support -->
<script src="<?php echo base_url('public/js-xlsx-master/ods.js') ?>"></script>
<script src="<?php echo base_url('public/js/EventHelpers.js') ?>"></script>
<script src="<?php echo base_url('public/js/DragDropHelpers.js') ?>"></script>
<script src="<?php echo base_url('public/js/importar.js') ?>"></script>
<style>
body {
	font-family: "Arial", sans-serif;	
}

table.userTable {
	
	width: 40em;
	margin: 0 auto;	
	border-collapse: collapse;	
	border: solid 1px black;
}

table.userTable thead {
	
}

table.userTable th {

	width: 33%;
	background: #333333;
	color: white;	
}


table.userTable tbody  td {
	border: solid 1px black;
	padding: 1em;	
}


table.userTable tfoot td {
	border: none;
	visibility: hidden;	
}

table.userTable tfoot td {
	border: solid 1px white;
		
}

table.userTable tfoot td.showHelp {
	border: solid 1px white;
	
	visibility: visible;	
}

.userList {
	width: 10em;
	height: 10em;
	overflow: auto;
	border: solid 1px black;
}

table.userTable td a {
	display: block;
	width: 10em;
	height: 1.4em;
	border: solid 1px #999999;
	text-decoration: none;
	text-align: center;
	margin-bottom: .3em;
	background-color: white;
	cursor: move;
}

.draggedUser {
	zoom: 1;
	opacity: .2;
	-moz-opacity: .2;
	filter: alpha(opacity=20)
}

#unassignedUsers:hover {
	background-color: #ffcccc;	
}

#restrictedUsers:hover {
	background-color: #ffffcc;
}

#powerUsers:hover {
	background-color: #ccffcc;
}

.campos{
	width: 10em;
	overflow-y: hidden;
	overflow-x: hidden; 
	border: solid 1px black;
}
[draggable=true] {
  -khtml-user-drag: element;
  -webkit-user-drag: element;
  -khtml-user-select: none;
  -webkit-user-select: none;
}
</style>
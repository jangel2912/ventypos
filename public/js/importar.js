/*----Arrastar y soltar */
var importarForm = new function () {
	
	var me = this;
	var mouseX, mouseY;
	var elementNodes, currentlyDraggedNode;
	
	me.init = function () {
            if (EventHelpers.hasPageLoadHappened(arguments)) {
                return;
            }
            elementNodes = $('[draggable=true]');
            $.each(elementNodes,function(i,e){
                EventHelpers.addEvent(elementNodes[i], 'dragstart', dragStartEvent);
                EventHelpers.addEvent(elementNodes[i], 'dragend', dragEndEvent);
            });
            /*for (var i=0; i<elementNodes.length; i++) {
                EventHelpers.addEvent(elementNodes[i], 'dragstart', dragStartEvent);
                EventHelpers.addEvent(elementNodes[i], 'dragend', dragEndEvent);
            }*/
           
            elementListNodes = $('.campos');
            for (var i=0; i<elementListNodes.length; i++) {
                var elementListNode = elementListNodes[i];
                EventHelpers.addEvent(elementListNode, 'dragover', dragOverListEvent);
                EventHelpers.addEvent(elementListNode, 'dragleave', dragLeaveListEvent);
                EventHelpers.addEvent(elementListNode, 'drop', dropListEvent);	
            }	
	}
	
	function dragStartEvent(e) {
            console.log("aqui");
            e.dataTransfer.setData("Text", "draggedUser: " + this.innerHTML);
            currentlyDraggedNode = this;				
            currentlyDraggedNode.className = 'draggedUser';
	}
	
	
	function dragEndEvent(e) {	
            console.log("aqui2");
            currentlyDraggedNode.className = '';
	}
	
	
	function dragLeaveListEvent(e){
            console.log("aqui3");
            setHelpVisibility(this, false);
	}
	
	function dropListEvent(e) {
            console.log(currentlyDraggedNode);
            //console.log(this);
            /*
             * To ensure that what we are dropping here is from this page
             */
            if(e.srcElement.id == "restrictedUsers" || e.srcElement.id == "unassignedUsers")
            {
                console.log("<<<>>>");
                console.dir(currentlyDraggedNode);
                currentlyDraggedNode.parentNode.removeChild(currentlyDraggedNode);
                this.appendChild(currentlyDraggedNode);
                //setHelpVisibility(this, false);
                dragEndEvent(e);
            }else
            {
                //console.log("no");
                //console.log(e.srcElement.id);
            }
	}
	
	function dragOverListEvent(e) {	
            console.log("aqui5");
            setHelpVisibility(this, true);
            EventHelpers.preventDefault(e);
	}
	
	function setHelpVisibility(node, isVisible) {
            var helpNodeId = node.id + "Help";
            var helpNode = document.getElementById(helpNodeId);
    	
            if (isVisible) {
                    helpNode.className =  'showHelp';
            } else {
                    helpNode.className =  '';
            }
	}
}

DragDropHelpers.fixVisualCues=true;
EventHelpers.addPageLoadEvent('importarForm.init');
    
/*----lectura del archivo excel---*/
function handleFile(e) {
    var files = e.target.files;
    var i,f;
    if(files.length != 0)
    {
        for (i = 0, f = files[i]; i != files.length; ++i)
        {
            var reader = new FileReader();
            reader.onload = function(e) {
                var data = e.target.result;

                /* if binary string, read with type 'binary' */
                var workbook = XLSX.read(data, {type: 'binary'}),
                    columnasLectura = workbook.Strings,
                    columnas = [];

                $.each(columnasLectura,function(i,e){
                    columnas[i] = e['t'];
                });

                if(columnas.lenght != 0)
                {
                    mostrarColumnas(columnas);
                }
                //console.log(columnas);
            };
            //lee el archivo
            reader.readAsBinaryString(f);
        }
    }else
    {
        $('#importarP').hide();
        $('td.campos').find('a').remove();
    }
    importarForm.init;
    
}

function mostrarColumnas(columnas)
{
    $('#importarP').show();
    $('td.campos').find('a').remove();
    $.each(columnas,function(i,e){
       $('td.campos').append('<a id="unassignedUsers" class="columna" href="'+i+'" draggable="true" style="cursor: move;">'+e+'</a>');
    });
}
var xlf = document.getElementById('xlf');
xlf.addEventListener('change', handleFile, false);
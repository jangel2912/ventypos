<style>
    .fuelux .repeater-list-heading{
        z-index:2 !important;
    }
    .panel .panel-body{
        height: 80vh !important;
    }
</style>
<div class="header-content">
    <h2><i class="ico-box"></i>Productos <span>productos &amp; modificaciones</span></h2>
    <div class="breadcrumb-wrapper hidden-xs">
        <span>Estas aqui:</span>
        <ol class="breadcrumb">
            <li class="active">Productos Restaurante</li>
        </ol>
    </div>
</div>
<div class="body-content">
    <div class="row">
        <br>
        <div class="col-md-12">
            <?php if($this->session->flashdata('message') != ''): ?>
            <div class="alert alert-success">
                    <?php echo $this->session->flashdata('message'); ?>                   
            </div>
            <?php endif; ?>
        </div>
        <div class="col-md-12">
            <div class="panel rounded shadow no-overflow">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title">Lista de Productos</h3>
                    </div>
                    <div class="pull-right">
                        <a href="<?php echo site_url("ProductoRestaurant/createProduct");?>"  type="button" class="btn btn-default">Agregar nuevo</a>                        
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    <div class="fuelux">
                    <div class="repeater" id="myRepeater" data-staticheight="400" data-currentview="list" data-viewtype="list">
                            <div class="repeater-header">
                                <div class="repeater-header-left">
                                    <div class="repeater-search">
                                        <div class="search disabled input-group">
                                            <input type="search" class="form-control" placeholder="Buscar">
                                            <span class="input-group-btn">
                                                <button class="btn btn-success btntabla" type="button">
                                                    <span class="glyphicon glyphicon-search"></span>
                                                    <span class="sr-only">Search</span>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="repeater-header-right">
                                <div class="btn-group selectlist repeater-filters" data-resize="auto">                                    
                                    <button type="button" class="btn btn-success dropdown-toggle vertodo" data-toggle="dropdown">
                                        <span class="selected-label">Todos</span>
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Filters</span>
                                    </button>
                                    <ul id="test" class="dropdown-menu vertodo" role="menu">
                                            <li data-value="all" data-selected="true" class="text-left"><a href="#">Todos</a></li>
                                        <?php foreach($data['categoria'] as $categoria): ?>
                                            <li data-value="<?php echo $categoria->id; ?>" data-selected="true" class="text-left">
                                                <a href="#"><?php echo $categoria->nombre; ?></a>
                                            </li>
                                        <?php endforeach; ?>                                        
                                    </ul>
                                    <input class="hidden hidden-field" name="filterSelection" readonly="readonly" aria-hidden="true" type="text">
                                </div>                                
                            </div>
                            </div>
                            <div class="repeater-viewport">
                                <div class="repeater-canvas"></div>
                                <div class="loader repeater-loader"></div>
                            </div>
                            <div class="repeater-footer">
                                <div class="repeater-footer-left">
                                    <div class="repeater-itemization">
                                        <span><span class="repeater-start"></span> - <span class="repeater-end"></span> de <span class="repeater-count"></span> Productos</span>
                                        <div class="btn-group selectlist dropup" data-resize="auto">                                          
                                            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                                <span class="selected-label"></span>
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Filters</span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li data-value="10" data-selected="true"><a href="#">10</a></li>
                                                <li data-value="25"><a href="#">25</a></li>
                                                <li data-value="50"><a href="#">50</a></li>                                 
                                            </ul>
                                            
                                            <input class="hidden hidden-field" name="itemsPerPage" readonly="readonly" aria-hidden="true" type="text">                                          
                                        </div>
                                    </div>
                                </div>
                                <div class="repeater-footer-right">
                                    <div class="repeater-pagination">
                                        <button type="button" class="btn btn-success btn-sm repeater-prev">
                                            <span class="glyphicon glyphicon-chevron-left"></span>
                                            <span class="sr-only">Previous Page</span>
                                        </button>
                                        <label class="page-label" id="myPageLabel">Página</label>
                                        <div class="repeater-primaryPaging active">
                                            <div class="input-group input-append dropdown combobox dropup">
                                                <input type="text" class="form-control" aria-labelledby="myPageLabel" style="max-width: 60px;">                                                                                               
                                                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" style="min-height: 30px;">
                                                    <span class="selected-label"></span>
                                                    <span class="caret"></span>
                                                    <span class="sr-only">Toggle Filters</span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right" role="menu">                                                                                       
                                                </ul>                                                
                                            </div>
                                        </div>
                                        <input type="text" class="form-control repeater-secondaryPaging" aria-labelledby="myPageLabel">
                                        <span>de <span class="repeater-pages"></span></span>
                                        <button type="button" class="btn btn-success btn-sm repeater-next">
                                            <span class="glyphicon glyphicon-chevron-right"></span>
                                            <span class="sr-only">Next Page</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>        
        </div>   
    </div> 
</div>
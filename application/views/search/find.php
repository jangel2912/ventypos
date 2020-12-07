<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("search_result", "Resultado de la b&uacute;squeda");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_search_list', "Listado de resultados");?></h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <?php
                $message = $this->session->flashdata('message');
                if(!empty($message)):?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>
            <div class="head blue">
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('sima_search_list', "Listado de resultados");?></h2>
            </div>
                <div class="data-fluid">
                    <?php foreach ($data as $value) :?>
                        <p>
                            <?php echo $value['contents'];?>
                            <a href="<?php echo site_url("{$value["type"]}/detalles/{$value["contents_id"]}")?>">Ver m&aacute;s</a>
                        </p>
                    <?php endforeach;?>
                </div>
            </div>
            
        </div>
    </div>
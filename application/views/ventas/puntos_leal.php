<?php
    $resultPermisos = getPermisos();
    $permisos = $resultPermisos["permisos"];
    $isAdmin = $resultPermisos["admin"];
?>

<div>
    <iframe src="<?php echo base_url(); ?>index.php/quickservice/iframe" frameborder="0"></iframe>
</div>
<style>
    iframe {
        width: 100%;
        height: 100vh;
    }

    ..page-content {
        padding: 0 !important;
    }

    #v2Cont {
        border: none;
        border-radius: 0;
        background-color: #fff;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        padding: 0;
        margin: 0;
        height: 100vh;
    }

    .wrapper {
        margin: 0;
    }
</style>
$(document).ready(function () {
    $(".social a").removeClass("glyphicon glyphicon-play-circle"); 
    var urlvimeo = $("#cartoonVideovimeo").attr('src');  

/****vimeo ****/
    $("#myModalvideovimeo").on('hide.bs.modal', function () {
        $("#cartoonVideovimeo").attr('src', '');
    });
    $("#myModalvideovimeo").on('show.bs.modal', function () {        
        urlvimeo = urlvimeo.split('?');
        urlvimeo = urlvimeo[0] + '?autoplay=1&;' + urlvimeo[1];       
        $("#cartoonVideovimeo").attr('src', urlvimeo);
    });
});
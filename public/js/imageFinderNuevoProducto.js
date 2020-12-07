$(document).ready(function(){
    $(".selectFromGallery.imagen-principal").imageFinder({
        modalId: "imagen-principal",
        inputHiddenName: "imagenPrincipalHiddenInput"
    });

    $(".selectFromGallery.imagen-1").imageFinder({
        modalId: "imagen-1",
        inputHiddenName: "imagen1HiddenInput"
    });

    $(".selectFromGallery.imagen-2").imageFinder({
        modalId: "imagen-2",
        inputHiddenName: "imagen2HiddenInput"
    });

    $(".selectFromGallery.imagen-3").imageFinder({
        modalId: "imagen-3",
        inputHiddenName: "imagen3HiddenInput"
    });

    $(".selectFromGallery.imagen-4").imageFinder({
        modalId: "imagen-4",
        inputHiddenName: "imagen4HiddenInput"
    });

    $(".selectFromGallery.imagen-5").imageFinder({
        modalId: "imagen-5",
        inputHiddenName: "imagen5HiddenInput"
    });

    $("body").on('click', ".upload-first-image", function() {
        var productId = $(this).data("product-id");
        $(this).imageProductFinder({
            modalId: "product-images",
            reloadUrl: "/index.php/productos",
            productId: productId
        });
    });
});
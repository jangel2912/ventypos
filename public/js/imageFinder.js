(function ($) {
    $.fn.imageFinder = function( options ) {
        var settings = $.extend({
            url: "/index.php/images/load_user_images",
            imagesLoaded: false,
            imageData: [],
            imageFinderModalClass: "myImageFinderModal",
            modalId: "",
            modalTitle: "Seleccione Imágen",
            inputHiddenName: ""
        }, options );

        
        $(this).click(function(){
            
            if(!settings.imagesLoaded) {
                $.ajax({
                    url: settings.url,
                    dataType: "json",
                    error: function( jqXHR, textStatus, errorThrown) {
                        alert("Error Code: " + textStatus + ". Error Message: " + errorThrown);
                    },
                    success: function(data) {
                        settings.imagesLoaded = true;
                        settings.imageData = data;
                        loadModalWithImages();
                    }
                });
            } else {
                loadModalWithImages();
            }

            if($("input[name='" + settings.inputHiddenName + "']").length === 0) {
                $("<input name='" + settings.inputHiddenName + "' type='hidden' value='' />").insertAfter(this);
            }

        });

        $("body").on("click", "#" + settings.modalId + " a.thumbnail img", function() {
            $("input[name='" + settings.inputHiddenName + "']").val($(this).prop('alt'));
            
            if($("#containerImage" + settings.modalId).length > 0) {
                $("#containerImage" + settings.modalId).remove();
            }

            if($(".legacy-" + settings.modalId).length > 0) {
                $(".legacy-" + settings.modalId).remove();
            }
            
            var imageContainer = "<div id='containerImage" + settings.modalId + "' class='imageFinderSelectedImageContainer'>";
                    imageContainer += '<img src="' + $(this).prop('src') + '" alt="' + $(this).prop('alt') +'" height="100px" width="100px"/>';
                    imageContainer += "<div class='imageFinderTitle'>" + $(this).prop('alt') + "</div>";
                    imageContainer += "<a class='text-danger removeSelectecImage' href='javascript:void(false);'><i class='icon icon-trash'></i></a>";
                imageContainer += "<div>";
            $(imageContainer).insertAfter("input[name='" + settings.inputHiddenName + "']");
        });

        $("body").on("click", "#containerImage" + settings.modalId + " a.removeSelectecImage", function() {
            $("input[name='" + settings.inputHiddenName + "']").val("");
            $( "#containerImage" + settings.modalId).remove();
        });

        function loadProductsOnSearch() {
            var imagesLinks = "";
            $.each(settings.imageData, function(index, element) {
                var localImage = "<div class='col-xs-6 col-md-3 local-images' data-imagename='" + element.image_name + "'>";
                        localImage += "<a class='thumbnail' href='javascript:void(false);'>";
                            localImage += "<img src='" + element.image_url + "' alt='" + element.image_name + "'>";
                        localImage += "</a>";
                        localImage += "<span class='image-title'>" + element.image_name + "</span>";
                    localImage += "</div>";
                imagesLinks += localImage;
            });

            $(".imageFinder" + settings.modalId).html(imagesLinks);
        }

        function loadModalWithImages() {

            var imagesLinks = "";
            $.each(settings.imageData, function(index, element) {
                var localImage = "<div class='col-xs-6 col-md-3 local-images' data-imagename='" + element.image_name + "'>";
                        localImage += "<a class='thumbnail' href='javascript:void(false);'>";
                            localImage += "<img src='" + element.image_url + "' alt='" + element.image_name + "'>";
                        localImage += "</a>";
                        localImage += "<span class='image-title'>" + element.image_name + "</span>";
                    localImage += "</div>";
                imagesLinks += localImage;
            });

            var htmlModal = '<div id="' + settings.modalId + '" class="modal fade" tabindex="-1" role="dialog">';
                    htmlModal += '<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">';
                        htmlModal += '<div class="modal-content">';
                            htmlModal += '<div class="modal-header">';
                                htmlModal += '<button type="button" class="close modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>';
                                htmlModal += '<h4 class="modal-title">&nbsp;&nbsp;' + settings.modalTitle + '</h4>';
                            htmlModal += '</div>';
                            htmlModal += '<div class="modal-body">';
                                htmlModal += "<div style='overflow-y: hidden; height: calc(100vh - 15rem);'>";
                                    htmlModal += "<div class='px-2' style='overflow-y: auto; height: 100%;'>";
                                        htmlModal += '<div class="image-search-container"><input autocomplete="off" placeholder="Buscar Imágen" class="form-control" type="text" id="search-input' + settings.modalId + '"><br/></div>';
                                        htmlModal += '<div class="row imageFinder imageFinder' + settings.modalId + '">';
                                            htmlModal += imagesLinks;
                                        htmlModal += '</div>';
                                    htmlModal += '</div>';
                                htmlModal += '</div>';
                            htmlModal += '</div>';
                        htmlModal += '</div>';
                    htmlModal += '</div>';
                htmlModal += '</div>';
            
            $("." + settings.imageFinderModalClass).html(htmlModal);
            $("#" + settings.modalId).modal();
            $("#" + settings.modalId).modal('show');
        }

        $("body").on("keyup", "#search-input" + settings.modalId, function() {
            var searchValue = $(this).val();
            //if(searchValue.length > 2){
                $.ajax({
                    url: settings.url,
                    dataType: "json",
                    error: function( jqXHR, textStatus, errorThrown) {
                        alert("Error Code: " + textStatus + ". Error Message: " + errorThrown);
                    },
                    method: "POST",
                    data: {
                        search: searchValue
                    },
                    success: function(data) {
                        settings.imagesLoaded = true;
                        settings.imageData = data;
                        loadProductsOnSearch();
                    }
                });
            //}
        });

        return this;
    };

    $.fn.imageProductFinder = function( options ) {
        var settings = $.extend({
            url: "/index.php/images/load_user_images",
            urlUpdateProductImage: "/index.php/images/update_product_image",
            imagesLoaded: false,
            imageData: [],
            imageFinderModalClass: "myImageFinderModal",
            modalId: "",
            modalTitle: "Seleccione Imágen",
            inputHiddenName: "",
            productId: "",
            reloadUrl: ""
        }, options );

        if(!settings.imagesLoaded) {
            $.ajax({
                url: settings.url,
                dataType: "json",
                error: function( jqXHR, textStatus, errorThrown) {
                    alert("Error Code: " + textStatus + ". Error Message: " + errorThrown);
                },
                success: function(data) {
                    settings.imagesLoaded = true;
                    settings.imageData = data;
                    loadModalWithImages();
                }
            });
        } else {
            loadModalWithImages();
        }

        $("body").on("click", "#" + settings.modalId + " a.thumbnail img", function() {
            var image = $(this).prop('alt');
            $.ajax({
                url: settings.urlUpdateProductImage,
                method: 'POST',
                data: {
                    product_id: settings.productId,
                    image: image
                },
                success: function(data) {
                    if(data.success == true){
                        location.href = settings.reloadUrl;
                    }
                },
                error: function(){
                    alert("No se ha podido actualizar. Intente más tarde.");
                }
            });
        });

        function loadModalWithImages() {

            var imagesLinks = "";

            $.each(settings.imageData, function(index, element) {
                var localImage = "<div class='col-xs-6 col-md-3 local-images' data-imagename='" + element.image_name + "'>";
                        localImage += "<a class='thumbnail' href='javascript:void(false);'>";
                            localImage += "<img src='" + element.image_url + "' alt='" + element.image_name + "'>";
                        localImage += "</a>";
                        localImage += "<span class='image-title'>" + element.image_name + "</span>";
                    localImage += "</div>";
                imagesLinks += localImage;
            });

            var htmlModal = '<div id="' + settings.modalId + '" class="modal fade" tabindex="-1" role="dialog">';
                    htmlModal += '<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">';
                        htmlModal += '<div class="modal-content">';
                            htmlModal += '<div class="modal-header">';
                                htmlModal += '<button type="button" class="close modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>';
                                htmlModal += '<h4 class="modal-title">&nbsp;&nbsp;' + settings.modalTitle + '</h4>';
                            htmlModal += '</div>';
                            htmlModal += '<div class="modal-body">';
                                htmlModal += '<div style="overflow-y: hidden; height: calc(100vh - 15rem);">';
                                    htmlModal += '<div class="px-2" style="overflow-y: auto; height: 100%;">';
                                            htmlModal += '<div class="image-search-container"><input autocomplete="off" placeholder="Buscar Imágen" class="form-control" type="text" id="search-input' + settings.modalId + '"><br/></div>';
                                            htmlModal += '<div class="row imageFinder imageFinder' + settings.modalId + '">';
                                                htmlModal += imagesLinks;
                                            htmlModal += '</div>';
                                        htmlModal += '</div>';
                                    htmlModal += '</div>';
                            htmlModal += '</div>';
                        htmlModal += '</div>';
                    htmlModal += '</div>';
                htmlModal += '</div>';
            
            $("." + settings.imageFinderModalClass).html(htmlModal);
            $("#" + settings.modalId).modal();
            $("#" + settings.modalId).modal('show');
        }

        $("body").on("keyup", "#search-input" + settings.modalId, function() {
            var searchValue = $(this).val();
            //if(searchValue.length > 2){
                $.ajax({
                    url: settings.url,
                    dataType: "json",
                    error: function( jqXHR, textStatus, errorThrown) {
                        alert("Error Code: " + textStatus + ". Error Message: " + errorThrown);
                    },
                    method: "POST",
                    data: {
                        search: searchValue
                    },
                    success: function(data) {
                        settings.imagesLoaded = true;
                        settings.imageData = data;
                        loadProductsOnSearch();
                    }
                });
            //}
        });

        function loadProductsOnSearch() {
            var imagesLinks = "";
            $.each(settings.imageData, function(index, element) {
                var localImage = "<div class='col-xs-6 col-md-3 local-images' data-imagename='" + element.image_name + "'>";
                        localImage += "<a class='thumbnail' href='javascript:void(false);'>";
                            localImage += "<img src='" + element.image_url + "' alt='" + element.image_name + "'>";
                        localImage += "</a>";
                        localImage += "<span>" + element.image_name + "</span>";
                    localImage += "</div>";
                imagesLinks += localImage;
            });

            $(".imageFinder" + settings.modalId).html(imagesLinks);
        }

        return this;
    };
}(jQuery));
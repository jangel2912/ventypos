    </div>

    </div>

    </div>

     <?php get_js("<script type='text/javascript' src='$1'></script>");?>

 <script>


        $("#btnUi1,#btnUi2").click(function(){
            
            $.ajax({
                type: "POST",
                url: '<?php echo site_url("frontend/setUi/v2"); ?>',
                success: function (response) {                
                    location.reload();
                },
                error: function (xhr, textStatus, errorThrown) {
                    alert(textStatus + " : " + errorThrown);
                }
            });            
            
        });    
 </script>

    <script type="text/javascript">

        $(document).ready(function(){

            $('#change_languaje').change(function(){

                $.ajax({

                        'url': '<?php echo site_url("auth/change_languaje")?>',

                        data: {'languaje' :$('#change_languaje').val()},

                        dataType: 'json',

                        type: "POST",

                        async: false,

                        success: function(data){

                              location.href = "<?php echo current_url();?>";

                        }

                    });

                

            });

        });

    </script>

<script>
document.getElementById('admin_shop').addEventListener('click', function(e) {
    e.preventDefault();

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {

        if (this.readyState == 4 && this.status == 200) {
            console.log('-----------------------------------');
            console.log(this.responseText);
            console.log('-----------------------------------');

            localStorage.setItem("data", this.responseText);
            
            var popUp = window.open('http://admintienda.vendty.com/admin/crosslogin', '_blank');
            if (popUp == null || typeof(popUp) == 'undefined') {
                console.log('-----------------------------------');
                console.log('Se bloqueo el popup');
                console.log('-----------------------------------');
                document.getElementById('validate-popup').style.display = 'block';
            } 
        }
    };

    xhttp.open("GET", '<?php echo site_url('tienda/crossDomain');?>', true);
    xhttp.send();
});

    
</script>  

</body>

</html>


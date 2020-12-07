<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>
   
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {

        if (this.readyState == 4 && this.status == 200) {
            localStorage.setItem("data", this.responseText);
            setTimeout(() => {
                location.href="http://admintienda.vendty.com/admin/crosslogin";
            }, 1000);
        }
    };

    xhttp.open("GET", '<?php echo site_url('tienda/crossDomain');?>', true);
    xhttp.send();

</script>
</html>
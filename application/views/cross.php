<!doctype html>
<head>
  <title>Cross Storage Hub</title>
</head>
<body>
  <script type="text/javascript" src="<?php echo base_url().'public/js/hub.js'; ?>"></script>
  <script type='text/javascript' src='<?php echo base_url(); ?>public/js/plugins/jquery/jquery-1.9.1.min.js'></script>
  <script>
    var permissions = {
      'Access-Control-Allow-Origin':  '*',
      'Access-Control-Allow-Methods': 'GET,PUT,POST,DELETE',
      'Access-Control-Allow-Headers': 'X-Requested-With',
      'Content-Security-Policy':      "default-src 'unsafe-inline' *",
      'X-Content-Security-Policy':    "default-src 'unsafe-inline' *",
      'X-WebKit-CSP':                 "default-src 'unsafe-inline' *",
    };

    // Limit requests to any client running on .localhost:300x
    CrossStorageHub.init([{
      permissions: permissions, 
      origin: /.*admintienda\.vendty\.com$/,
      //origin: /192.168.0.11:4200$/, 
      allow: ['get', 'set', 'del', 'getKeys', 'clear']
    }]);

    $(document).ready(function(){
      var url_cross = "<?php echo site_url('tienda/crossDomain');?>";
      $.get(url_cross,function(data){
        localStorage.setItem("data",data);
      })  
    })
  </script>
</body>
</html>

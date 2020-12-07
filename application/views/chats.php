<script>
    window.onload = function () {
        var data = localStorage.getItem('api_auth');
        var apiAuth = JSON.parse(data);

        window.intercomSettings = {
            app_id: "uujoat67",
            email: "<?php echo $this->session->userdata('email'); ?>",
            user_hash: "<?php echo hash_hmac('sha256', $this->session->userdata('email'), 'CBs9NOXwqrj_OASr9K3Se2OpPEdEmPHTDunTsH6Y'); ?>",
            id_licencia: apiAuth.user.db_config.license.idlicencias_empresa,
            nombre_empresa: apiAuth.business_name,
            plan_empresa: apiAuth.license.plan ? apiAuth.license.plan.nombre_plan : 'PLAN DESCONOCIDO',
            tipo_empresa: apiAuth.type_business,
            vence_empresa: apiAuth.license.expired_at,
        };

        setTimeout(function() {
            (function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',w.intercomSettings);}else{var d=document;var i=function(){i.c(arguments);};i.q=[];i.c=function(args){i.q.push(args);};w.Intercom=i;var l=function(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/uujoat67';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);};if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()
        }, 1000)
    }
    
</script>
<script>
    
</script>

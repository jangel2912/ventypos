<?php extract($data);?>
<h1>Crear grupo</h1>

<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open("backend/usuarios/create_group");?>

      <p>
            <?php echo "Nombre del Grupo";?> <br />
            <?php echo form_input($group_name);?>
      </p>

      <p>
            <?php echo "Descripci&oacute;n";?> <br />
            <?php echo form_input($description);?>
      </p>

      <p><?php echo form_submit('submit', "Enviar");?></p>

<?php echo form_close();?>
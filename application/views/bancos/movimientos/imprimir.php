<html>
    <head></head>
    <body>  

            <table width="650px" align="center" style="border: 1px solid #000000; border-bottom: 1px solid #000000; ">
                <tr>
                    <td width="27%"  align="center" style=" font-size: 11px"><br><br>
                    Fecha creación <?= $data["movimiento"]->fecha_creacion;?> <br>
                    Referencia <?= $data["movimiento"]->referencia;?> <br>
                    </td>

                    <td width="19%"  align="left"><br><br></td>
                    <td style="border-left: 1px solid #000000; font-size:11px" width="39%" align="left"><B><br><br>
                        COMPROBANTE DE MOVIMIENTO BANCARIO<br>
                        &nbsp; NO. <?= $data["movimiento"]->id;?> </B>
                    </td>
                </tr>
			</table>

            <table width="650px" align="center" style="border: 1px solid #000000; border-top: 0; border-bottom: 1px solid #000000; ">
                <tr>
                    <td width="27%"  align="center" style=" font-size: 11px"><br><br>
                    Fecha creación <?= $data["movimiento"]->fecha_creacion;?> <br>
                    Referencia <?= $data["movimiento"]->referencia;?> <br>
                    </td>

                    <td width="19%"  align="left"><br><br></td>
                    <td style="border-left: 1px solid #000000; font-size:11px" width="39%" align="left"><B><br><br>
                        COMPROBANTE DE MOVIMIENTO BANCARIO<br>
                        &nbsp; NO. <?= $data["movimiento"]->id;?> </B>
                    </td>
                </tr>
			</table>


            
    </body>
    <script>
        window.print();
    </script>
</html>
$(function(){
    activar_opcion();

    $("#editreg, #editreg, #savereg, #cancelreg, #deletereg, #exportexcel, #searchreg").addClass("oculto");

    $("#newreg").on("click", function (e) {
        e.preventDefault();
        var row = "";

        $.get("https://dni.optimizeperu.com/api/tipo-cambio", {data:0},
            function (data, textStatus, jqXHR) {
                
                var tabla = $("#tcambio tbody tr");

                var d = new Date();
                var dia = d.getDate(),
                    mes = d.getMonth()+1,
                    anio = d.getFullYear();
                
                var strDate =  $.strPad(dia,2) + "/" + $.strPad(mes,2) + "/" + anio;

                //verifica si ya se hizo el tipo de cambio
                if (!checkExistTable(tabla,strDate,0)){
                    row = "<tr>"+
                            "<td class='centro con_borde'>"+ strDate +"</td>"+
                            "<td class='pl20 con_borde'>DOLAR AMERICANO</td>"+
                            "<td class='centro'>--</td>"+
                            "<td class='centro con_borde'>"+ data.cambio_actual.compra +"</td>"+
                            "<td class='centro con_borde'>"+ data.cambio_actual.venta +"</td>"+
                          "</tr>"; 

                $("#tcambio tbody").append(row);

                $.post(RUTA+"tcambio/regTipoCambio", 
                    {fecha: strDate,
                     moneda: 2,
                     compra: data.cambio_actual.compra,
                     venta: data.cambio_actual.venta
                    },
                    function (data, textStatus, jqXHR) {

                    },
                    "text"
                );
                }else{
                    mostrarMensaje("msj_info","Ya se registro el tipo cambio");
                }
                
            },
            "json"
        );
        return false;
    });
})
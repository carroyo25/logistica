$(function(){
    
    abrirVentanaEspera();

    $(document).ready(function(){
        activar_opcion();

        cerrarVentanaEspera();
    });

    
    $("#tabla_pedidos tbody").on("click","a", function (e) {
        e.preventDefault();

        abrirVentanaEspera();

        $.post(RUTA+"ingresoalmacen/guiaId", {idx:$(this).attr("href")},
            function (data, textStatus, jqXHR) {
                $("#nroguia").val(data[0].guia);
                $("#nrosalida").val(data[0].ncodmov);
                $("#fechadoc").val(data[0].ffechdoc);
                $("#fechatras").val(data[0].ffechtrasl);
                $("#proyecto").val(data[0].cdescos);
                $("#solicita").val(data[0].solicita);
                $("#almacen").val(data[0].cdesalm.toUpperCase());
                $("#fecped").val(data[0].fechapedido);
                $("#fecord").val(data[0].fechaorden);
                $("#espec").val(data[0].espec);
                $("#estadoc").val(data[0].estadoc);
                $("#items").val(data[0].items);
                $("#envio").val(data[0].envio);
                $("#bultos").val(data[0].nbultos.toFixed(2));
                $("#peso").val(parseInt(data[0].npesotot).toFixed(2));
                $("#nroped").val(data[0].pedido);
                $("#nrord").val(data[0].orden);
                $("#espec").val(data[0].mdetalle);
                $("#estadoc").val(data[0].estado);
                $("#cod_despacho").val(data[0].id_regalm);
                $("#cod_ingreso").val(data[0].idref_abas);
                
                $.post(RUTA+"ingresoalmacen/detallesDepacho", {idx: data[0].id_regalm},
                    function (data, textStatus, jqXHR) {
                        $("#detalle_despacho tbody")
                            .empty()
                            .append(data);

                        $("#items").val(($("#detalle_despacho tbody tr").length));

                        $("#modalProcess").fadeIn();
                        cerrarVentanaEspera();
                    },
                    "text"
                );
            },
            "json"
        );
        

        return false
    });

    $("#detalle_despacho tbody").on("click","a", function (e) {
        e.preventDefault();

        $.post(RUTA+"ingresoalmacen/series", {id:$(this).data("idprod"),ing:$("#cod_ingreso").val()},
            function (data, textStatus, jqXHR) {
                $("#detalle_series tbody")
                .empty()
                .append(data);

                $("#modalSerie").fadeIn();
            },
            "text"
        );
        
        return false;
    });
})
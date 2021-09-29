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
                $("#alm_destino").val(data[0].ncodalm2);
                $("#cod_guia").val(data[0].id_refmov);
                
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

    $("#btnConfirmSerial").click(function (e) { 
        e.preventDefault();
        
        return false;
    });
    
    $("#btnCancelSerial").click(function (e) { 
        e.preventDefault();
        
        $("#modalSerie").fadeOut();

        return false;
    });

    $("#grabarDoc").click(function (e) { 
        e.preventDefault();

        $("#cod_ingreso").val();

        let details = JSON.stringify(detallesTabla());

        $.post(RUTA+"ingresoalmacen/ingreso", {idx:$("#cod_ingreso").val(),
                                                detalles:details,
                                                almacen:$("#alm_destino").val(),
                                                guia:$("#cod_guia").val()},
            function (data, textStatus, jqXHR) {
                
            },
            "text"
        );

        return false;
    });
})

function detallesTabla() {
    DETALLES = [];

    var TABLA = $("#detalle_despacho tbody > tr");

    TABLA.each(function(){
        var NIDDETA     = $(this).find('td').eq(1).children().attr("href"),
            IDPROD      = $(this).find('td').eq(1).children().data("idprod"),
            FACTOR      = $(this).find('td').eq(1).children().data("factor"),
            UNID        = $(this).find('td').eq(1).children().data("unidad"),
            CANTIDAD    = $(this).find('td').eq(6).text(),
            OBSERVAC    = $(this).find('td').eq(7).children().val(),

            item = {};

            if (NIDDETA !== ''){
                item["niddeta"]     = NIDDETA;
                item["idprod"]      = IDPROD;
                item["factor"]      = FACTOR;
                item["unid"]        = UNID;
                item["cantidad"]    = CANTIDAD;
                item["observ"]      = OBSERVAC;
            }

            DETALLES.push(item);
    })

    return DETALLES;
}
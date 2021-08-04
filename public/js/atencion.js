$(function(){
    activar_opcion();

    $("#tabla_pedidos tbody").on("click","a", function (e) {
        e.preventDefault();

        //$("#modalProcess").fadeIn();
        $.post(RUTA+"atencion/consultaDocumentoId", {cod:$(this).attr("href")},
            function (data, textStatus, jqXHR) {
                $("#cod_pedido").val(data.id_regmov);
                $("#cod_proy").val(data.ncodpry);
                $("#cod_cost").val(data.ncodcos);
                $("#cod_area").val(data.ncodarea);
                $("#cod_transporte").val(data.ctiptransp);
                $("#cod_estdoc").val(data.nEstadoDoc);
                $("#cod_registro").val(data.nEstadoReg);
                $("#cod_solicitante").val(data.ncodper);
                $("#cod_tipo").val(data.ctipmov);
                $("#estado").val(data.nflgactivo);
                $("#atencion").val(data.nNivAten);
                $("#numero").val(data.cnumero);
                $("#fecha").val(data.ffechadoc);
                $("#usuario").val(data.id_cuser);
                $("#proyecto").val(data.ccodpry + ' ' + data.cdespry);
                $("#area").val(data.ccodarea + ' ' + data.cdesarea);
                $("#costos").val(data.ccodcos + ' ' + data.cdescos);
                $("#transporte").val(data.cod_transporte + ' ' + data.transporte);
                $("#concepto").val(data.cconcepto);
                $("#solicitante").val(data.dni + ' ' + data.apellidos + ',' + data.nombres);
                $("#registro").val(data.estado);
                $("#documento").val(data.estado);
                $("#fechaven").val(data.ffechaven);
                $("#tipo")
                    .val( data.ctipmov == "B" ? "01 BIENES" : "02 SERVICIOS")
                    .addClass("desactivado");
                $("#espec_items").val(data.mdetalle);

                $("#registro")
                            .removeClass("proceso","aprobado")
                            .addClass(data.estado.toLowerCase());

                $.post(RUTA+"atencion/consultarDetalles", {cod:data.id_regmov,tipo:data.ctipmov},
                    function (data, textStatus, jqXHR) {
                        $("#detalle_pedido tbody")
                        .empty()
                        .append(data);
                    },
                    "text"
                );

                $(".seleccion").fadeOut();
                $("#modalProcess").fadeIn();

            },
            "json"
        );

        return false;
    });

    $("#detalle_pedido tbody").on("click","a", function (e) {
        e.preventDefault();

        if ($(this).data("toption") == "serie"){
            $("#modalSerie").fadeIn();
        }else{
            $.post(RUTA+"atencion/almacenUsuario",{cod:$(this).attr("href")},
                function (data, textStatus, jqXHR) {
                    $("#detalle_existencia tbody")
                        .empty()
                        .append(data);
                    $("#modalAlmacen").fadeIn();
                },
                "text"
            );
        }

        return false;
    });

    $(".buttonClose").on("click", function (e) {
        e.preventDefault();

        $(this).parent().fadeOut();

        return false;
    });

    $("#cerrarAlmacen").on("click", function (e) {
        e.preventDefault();

        $("#modalAlmacen").fadeOut();

        return false;
    });

    $("#saveItem").on("click", function (e) {
        e.preventDefault();

        $("#dialogConfirm").fadeIn();

        return false;
    });

    $("#btnNo").on("click", function (e) {
        e.preventDefault();

        $("#dialogConfirm").fadeOut();

        return false;
    });

    $("#btnYes").on("click", function (e) {
        e.preventDefault();

        grabarDetalles();

        $.post(RUTA+"atencion/grabaAtencion", {idx:$("#cod_pedido").val(),
                                                detalles:JSON.stringify(DETALLES)},
            function (data, textStatus, jqXHR) {
                $("#dialogConfirm").fadeOut();
            },
            "text"
        );
        return false;
    });
})

function grabarDetalles(){
    if ($("#detalle_pedido tbody tr").length > 0){
        DETALLES = [];
        
        let TABLA = $("#detalle_pedido tbody > tr");
        
        TABLA.each(function(){
            var ITEM = $(this).find('td').eq(0).children().data('iddet'),
                CANT = $(this).find('td').eq(5).text(),
                ALM  = $(this).find('td').eq(6).children().val(),
                OBS  = $(this).find('td').eq(9).children().val(),

            item={};

            if (ITEM !== ''){
                item['item'] = ITEM;
                item['cant'] = CANT;
                item['alm'] = ALM;
                item['obs'] = OBS;
            }

            DETALLES.push(item);
        })

        return DETALLES;
    }
}
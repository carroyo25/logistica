$(function(){
    activar_opcion();

    $("#tabla_pedidos tbody").on("click","a", function (e) {
        e.preventDefault();

        $("#modalProcess").fadeIn();

        $.post(RUTA+"adjudicacion/regById", { data:$(this).attr("href") },
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

                $(".bubleInfo").text(data.atachs);
                $("#tipo")
                    .val( data.ctipmov == "B" ? "01 BIENES" : "02 SERVICIOS")
                    .addClass("desactivado");
                $("#espec_items").val(data.mdetalle);
                
                $("#registro, #documento")
                    .removeClass('proceso')
                    .addClass('aprobado');

                $.post(RUTA +"adjudicacion/proformas", {cod:$("#cod_pedido").val()},
                    function (data, textStatus, jqXHR) {
                        console.log(data);
                        $("#detalle_pedido")
                        .empty()
                        .append(data);
                    },
                    "text"
                );
            },
            "json"
        );

    return false;
    });

    $("#closeModalProcess").on("click", function (e) {
        e.preventDefault()
       
        $("#modalProcess").fadeOut()

        return false;
    });
})



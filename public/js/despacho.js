var accion          = "n";

$(function(){
    activar_opcion();

    $("#newreg").on("click", function (e) {
        e.preventDefault();

        $("#modalProcess").fadeIn();
        $(".process_header, .details_item").removeClass("desactivado");
        $(".sides_process div").removeClass("no_modificar");
        $("#formProcess")[0].reset();

        $("#registro, #documento").val("EN PROCESO");
        $("#detalle_pedido tbody").empty();
        $("#saveItem span").removeClass('parpadea');
        $("#tipo").removeClass("desactivado");
        $(".seleccion").fadeOut();
        $("#registro, #documento")
            .removeClass('aprobado','emitido')
            .addClass('proceso');

        /*
        $.post(RUTA+"pedidos/newRequest", {data:0},
            function (data, textStatus, jqXHR) {
                $("#numero").val(data.numero);
                $("#cod_pedido").val(data.codigo);
            },
            "json"
        );*/

        accion = "n";

        return false;
    });

    $("#tabla_pedidos tbody").on("click","a", function (e) {
        e.preventDefault();

        $("#modalProcess").fadeIn();

        return false;
    });

    $("#closeModalProcess").on("click", function (e) {
        e.preventDefault()
       
        $("#modalProcess").fadeOut();
    
        return false;
    });

    //importar notas de ingreso
    $("#importarIngreso").on("click",function(e){
        e.preventDefault();

        $("#modalNotas").fadeIn();
        $("#waitmodal").fadeIn();

        $.post(RUTA+"despacho/notas",
            function (data, textStatus, jqXHR) {
                $("#waitmodal").fadeOut();
                $("#listaIngresos tbody")
                    .empty()
                    .append(data);
            },
            "text"
        );
        
        return false;
    })

    $("#closeModalNotas").on("click", function (e) {
        e.preventDefault()
       
        $("#modalNotas").fadeOut();
    
        return false;
    });

    //SELECCIONAR LA NOTA DE INGRESO
    $("#listaIngresos tbody").on("click","a", function (e) {
        e.preventDefault();
        
        $("#modalNotas").fadeIn();
        $.post(RUTA+"despacho/notaId",{idx: $(this).attr("href")},
            function (data, textStatus, jqXHR) {
                $("#id_ingreso").val(data[0].id_regalm);
                $("#id_salid").val(data[1].idreg);
                $("#id_entidad").val(data[0].id_centi);
                $("#cod_almacen").val(data[0].ncodalm1);
                $("#cod_movimento").val();
                $("#cod_autoriza").val(data[0].id_userAprob);
                $("#cod_proyecto").val(data[0].ncodpry);
                $("#cod_area").val(data[0].ncodarea);
                $("#cod_costos").val(data[0].ncodcos);
                $("#order_file").val(data[0].cdocPDF);
                $("#cargo_almacen").val(data[0].dcargo);
                $("#idorden").val(data[0].idref_abas);
                $("#idpedido").val(data[0].idref_pedi);
                $("#estado").val(1);
                $("#nrosalida").val(data[1].nrodoc);
                $("#movalma").val(data[1].nromov);
                $("#proyecto").val(data[0].proyecto);
                $("#solicita").val(data[0].solicita);
                $("#aprueba").val(data[0].aprueba);
                $("#almacen").val(data[0].almacen);
                $("#nroped").val(data[0].nroped);
                $("#fecped").val(data[0].fechaPedido);
                $("#nrord").val(data[0].nrorden);
                $("#fecord").val(data[0].fechaOrden);
                $("#espec").val(data[0].cconcepto);

                $.post(RUTA+"despacho/detallesIngresosId",{idx:data[0].id_regalm},
                    function (data, textStatus, jqXHR) {
                        $("#detalle_despacho tbody")
                            .empty()
                            .append(data);
                        $("#modalNotas").fadeOut();
                    },
                    "text"
                );
            },
            "json"
        );
        
        return false;
    });

    //acciones en la tabla de detalles
    $("#detalle_despacho tbody").on("click","a", function (e) {
        e.preventDefault();

        return false;
    });

    //mostrar los datos para la guia de remision 
    $("#guiaDetail").on("click", function (e) {
        e.preventDefault();
        
        /*if ($("#id_ingreso").val().length == 0){
            mostrarMensaje("msj_error","Seleccione una nota de ingreso");
            return false;
        }else if ($("#cod_movimento").val().length == 0){
            mostrarMensaje("msj_error","Seleccione el tipo de movimiento");
            return false;
        }*/

        $("#modalGuia").fadeIn();

        return false;
    });

    //lista de motivos de movimientos
    $("#tipomov").focus(function (e) { 
        e.preventDefault();
        
        if (accion == "n") {
            $("#cod_movimento").val("");
            $(this).select();
            $(".seleccion").fadeOut();

            $(this).next(".seleccion").slideDown();
        }

        return false;
    });

    $("#listaMotivo").on("click","a", function (e) {
        e.preventDefault();

        $("#cod_movimento").val($(this).attr("href"));
        $("#tipomov").val($(this).text());

        $(this).parent().parent().parent().slideUp();
        $("#saveDoc span").addClass('parpadea');

        return false;
    });

    //lista de motivos de movimientos
    $("#mottrans").focus(function (e) { 
        e.preventDefault();
        
        if (accion == "n") {
            $("#cot_motivo").val("");
            $(this).select();
            $(".seleccion").fadeOut();

            $(this).next(".seleccion").slideDown();
        }

        return false;
    });

    $("#listaMotivosGuia").on("click","a", function (e) {
        e.preventDefault();

        $("#cot_motivo").val($(this).attr("href"));
        $("#mottrans").val($(this).text());

        $(this).parent().parent().parent().slideUp();

        return false;
    });
})
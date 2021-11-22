$(function(){
    abrirVentanaEspera();

    var accion = "n";

    $(document).ready(function(){
        activar_opcion();

        cerrarVentanaEspera();
    });

    $("#newreg").click(function (e) { 
        e.preventDefault();
        
        $("#modalProcess").fadeIn();
        $(".process_header1ec").removeClass("desactivado");

        accion = "n";

        return false
    });

    //lista de Almacenes
    $("#almacen").focus(function (e) { 
        e.preventDefault();
        
        $("#cod_almacen").val("");
        $(this).select();
        $(".seleccion").fadeOut();

        $(this).next(".seleccion").slideDown();
        
        return false;
    });

    $("#tabla_pedidos tbody").on("click","a", function (e) {
        e.preventDefault();

        abrirVentanaEspera();

        $.post(RUTA+"salidaalmacen/salidaPorId", {id:$(this).attr("href")},
            function (data, textStatus, jqXHR) {
                $("#cod_almacen").val(data[0].id_alm);
                $("#cod_personal").val(data[0].id_solic);
                $("#cod_salida").val(data[0].idreg)
                $("#nrosalida").val( $.strPad(data[0].n_salida,4));
                $("#fechadoc").val(data[0].femic);
                $("#fechaEntrega").val(data[0].fentrega);
                $("#almacen").val(data[0].cdesalm);
                $("#solicitante").val(data[0].solcitante);
                $("#cargo").val(data[0].dcargo);
                $("#nrodocumento").val(data[0].dni);

                accion = "u";

                $.post(RUTA+"salidaalmacen/detallesId",{id:data[0].idreg},
                    function (data, textStatus, jqXHR) {
                        $("#detalle_despacho tbody")
                        .empty()
                        .append(data);
                        cerrarVentanaEspera();  
                    },
                    "text"
                );
            },
            "json"
        );

        $(".process_header1ec").addClass("desactivado");

        $("#modalProcess").fadeIn();

        return false;
    });

    $("#listaAlmacen").on("click","a", function (e) {
        e.preventDefault();

        $("#cod_almacen").val($(this).attr("href"));
        $("#almacen").val($(this).text());

        $(this).parent().parent().parent().slideUp();

        return false;
    });

    $("#nrodocumento").on("keyup", function(e) {
        if(e.which == 13 && $(this).val().length > 2) {
            abrirVentanaEspera();
            $.post(RUTA+"salidaalmacen/solicitante", {doc:$(this).val()},
                function (data, textStatus, jqXHR) {
                    if (data){
                        $("#cod_personal").val(data[0].internal);
                        $("#solicitante").val(data[0].nombres);
                        $("#cargo").val(data[0].dcargo);
                        
                    }else{
                        mostrarMensaje("msj_error","No se encontro la persona solcitante");
                        $("#cod_personal, #solicitante, #cargo, #nrodocumento").val("");
                    }
                    
                    cerrarVentanaEspera();  
                },
                "json"
            );
        }
    });

    $("#callItems").click(function (e) { 
        e.preventDefault();
        
        if ($("#cod_almacen").val() == "") {
            mostrarMensaje("msj_error","Seleccione el almacen de salida");
            return false;
        }else if($("#cod_personal").val() == ""){
            mostrarMensaje("msj_error","Rellene el N°. Documento");
            $("#nrodocumento").focus();
            return false;
        } 

        $.post(RUTA+"salidaalmacen/productosPorAlmacen", {cod:$("#cod_almacen").val()},
            function (data, textStatus, jqXHR) {
                $("#tableItems tbody")
                    .empty()
                    .append(data);
            },
            "text"
        );

        $.post(RUTA+"salidaalmacen/inicialesItems",
            function (data, textStatus, jqXHR) {
                $(".pagination ul")
                .empty()
                .append(data);
            },
            "text"
        );

        $("#modalItems").fadeIn();

        return false;
    });

    $(".pagination").on("click","a",function(e){
        e.preventDefault();

        abrirVentanaEspera();

        $.post(RUTA+"salidaalmacen/itemPorInicial", {letra:$(this).attr("href"),almacen:$("#cod_almacen").val()},
            function (data, textStatus, jqXHR) {
                $("#tableItems tbody")
                    .empty()
                    .append(data);
                
                cerrarVentanaEspera();
            },
            "text"
        )

        return false;
    })

    //filtrar los items
    $("#inputSearchItems").on("keyup", function(e) {
        if(e.which == 13 && $(this).val().length > 2) {
            abrirVentanaEspera();
            $.post(RUTA+"salidaalmacen/itemPorPalabra", {palabra:$(this).val(),almacen:$("#cod_almacen").val()},
                function (data, textStatus, jqXHR) {
                    $("#tableItems tbody")
                        .empty()
                        .append(data);
                        cerrarVentanaEspera();  
                },
                "text"
            );
        }
    });

    $("#tableItems tbody").on("click","tr", function (e) {
        e.preventDefault();
        let item        = $("#detalle_despacho tbody tr").length + 1;
        let codigo      = $(this).children('td:eq(0)').text();
        let descripcion = $(this).children('td:eq(1)').text();
        let unidad      = $(this).children('td:eq(5)').text();
        let cantidad    = 1;
        let serie       = $(this).children('td:eq(4)').text();
        let niddeta     = $(this).data("niddeta");
        let idprod      = $(this).data("idprod");
        let factor      = $(this).data("factor");
        let nunidad     = $(this).data("unidad");
        let existe      = $(this).data("existe");

        let fila = '<tr class="lh1_2rem">'+
                        '<td class="con_borde centro" data-grabado="0"><a href=""><i class="fas fa-trash-alt"></i></a></td>'+
                        '<td class="con_borde centro" data-niddeta="'+niddeta+'" data-idprod="'+idprod+'" data-factor="'+factor+'" data-unidad="'+nunidad+'" data-existe="'+existe+'">'+$.strPad(item,3)+'</td>'+
                        '<td class="con_borde centro">'+codigo+'</td>'+
                        '<td class="con_borde pl10">'+ descripcion +'</td>'+
                        '<td class="con_borde centro">'+unidad+'</td>'+
                        '<td class="con_borde"><input type="text" value="'+cantidad+'" class="drch pr10"></td>'+
                        '<td class="con_borde pl10">'+serie+'</td>'+
                    '</tr>';

        $("#detalle_despacho").append(fila);
        $(this).remove();

        $("#modalItems").fadeOut();

        return false;
    });

    $("#grabarDoc").click(function (e) { 
        e.preventDefault();

        if ($("#cod_almacen").val() == "") {
            mostrarMensaje("msj_error","Seleccione el almacen de salida")
            return false;
        }else if($("#cod_personal").val() == ""){
            mostrarMensaje("msj_error","Rellene el N°. Documento");
            $("#nrodocumento").focus();
            return false;
        }else if ( $("#detalle_despacho tbody tr").length == 0){
            mostrarMensaje("msj_error","No selecciono ningun Item");
            return false;
        }

        var result = {};
        let detalles = JSON.stringify(detallesTabla());

        if (accion == "n") {
            $.each($("#formProcess").serializeArray(),function(){
                result[this.name] = this.value;
            });
    
            $.ajax({
                type: "POST",
                url: RUTA+"salidaalmacen/registroSalida",
                data: {
                    cabecera:result,
                    detalles
                },
                dataType: "text",
                beforeSend: function(){
                    abrirVentanaEspera();
                },
                success: function (response) {
                    cerrarVentanaEspera();
                    $("#formProcess")[0].reset();
                    $("#detalle_despacho tbody").empty();
                    $("#modalProcess").fadeOut();
                }
            });
        }else{
            $.post(RUTA+"salidaalmacen/actualizaSalida", {detalles,id:$("#cod_salida").val()},
                function (data, textStatus, jqXHR) {
                    
                },
                "text"
            );
        }

        

        return false;
    });

    $("#closeModalItems").click(function (e) { 
        e.preventDefault();

        $("#modalItems").fadeOut();

        return false;
    });

    $("#closeModalProcess").click(function (e) { 
        e.preventDefault();
        
        $("#formProcess")[0].reset();
        $("#detalle_despacho tbody").empty();
        $("#modalProcess").fadeOut();

        return false;
    });
})

function detallesTabla() {
    DETALLES = [];

    var TABLA = $("#detalle_despacho tbody > tr");

    TABLA.each(function(){
        var NIDDETA     = $(this).find('td').eq(1).data("niddeta"),
            IDPROD      = $(this).find('td').eq(1).data("idprod"),
            FACTOR      = $(this).find('td').eq(1).data("factor"),
            UNIDAD      = $(this).find('td').eq(1).data("unidad"),
            EXISTE      = $(this).find('td').eq(1).data("existe"),
            CANTIDAD    = $(this).find('td').eq(5).children().val(),
            SERIE       = $(this).find('td').eq(6).text() != "" ? $(this).find('td').eq(6).text() : ""
            GRABADO     = $(this).find('td').eq(0).data("grabado"),

            item = {};

            if (NIDDETA !== undefined){
                item["niddeta"]     = NIDDETA;
                item["idprod"]      = IDPROD;
                item["factor"]      = FACTOR;
                item["unidad"]      = UNIDAD;
                item["cantidad"]    = CANTIDAD;
                item["serie"]       = SERIE;
                item['existe']      = EXISTE;

                DETALLES.push(item);
            }

            
    })

    return DETALLES;
}
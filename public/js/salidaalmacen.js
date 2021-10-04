$(function(){
    abrirVentanaEspera();

    $(document).ready(function(){
        activar_opcion();

        cerrarVentanaEspera();
    });

    $("#newreg").click(function (e) { 
        e.preventDefault();
        
        $("#modalProcess").fadeIn();

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

    $("#listaAlmacen").on("click","a", function (e) {
        e.preventDefault();

        $("#cod_almacen").val($(this).attr("href"));
        $("#almacen").val($(this).text());

        /*$.post(RUTA+"ingresos/nroingreso", {data:$("#cod_almacen").val()},
            function (data, textStatus, jqXHR) {
                $("#nro_ingreso").val(data.guia_nmr);
                $("#movalmacen").val(data.mov_nmr);
            },
            "json"
        );*/

        $(this).parent().parent().parent().slideUp();
        //$("#saveDoc span").addClass('parpadea');

        return false;
    });

    $("#nrodocumento").on("keyup", function(e) {
        if(e.which == 13 && $(this).val().length > 2) {
            abrirVentanaEspera();
            $.post(RUTA+"salidaalmacen/solicitante", {doc:$(this).val()},
                function (data, textStatus, jqXHR) {
                    $("#cod_personal").val(data[0].internal);
                    $("#solicitante").val(data[0].nombres);
                    $("#cargo").val(data[0].dcargo);
                    cerrarVentanaEspera();  
                },
                "json"
            );
        }
    });

    $("#callItems").click(function (e) { 
        e.preventDefault();
        
        /*if ($("#cod_almacen").val() == "") {
            mostrarMensaje("msj_error","Seleccione el almacen de salida")
            return false;
        }else if($("#cod_personal").val() == ""){
            mostrarMensaje("msj_error","Rellene el N°. Documento");
            $("#nrodocumento").focus();
            return false;
        } */ 

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
        let item = $("#detalle_despacho tbody tr").length + 1;
        let codigo = $(this).children('td:eq(0)').text();
        let descripcion = $(this).children('td:eq(1)').text();
        let unidad = $(this).children('td:eq(5)').text();
        let cantidad = 1;
        let serie = $(this).children('td:eq(4)').text();
        let niddeta = $(this).data("niddeta")

        let fila = '<tr class="lh1_2rem">'+
                        '<td class="con_borde centro"><a href=""><i class="fas fa-trash-alt"></i></a></td>'+
                        '<td class="con_borde centro" data-niddeta="'+niddeta+'">'+$.strPad(item,3)+'</td>'+
                        '<td class="con_borde centro">'+codigo+'</td>'+
                        '<td class="con_borde pl10">'+ descripcion +'</td>'+
                        '<td class="con_borde centro">'+unidad+'</td>'+
                        '<td class="con_borde"><input type="text" value="'+cantidad+'" class="drch pr10"></td>'+
                        '<td class="con_borde pl10">'+serie+'</td>'+
                    '</tr>';

        $("#detalle_despacho").append(fila);

        $("#modalItems").fadeOut();

        return false;
    });
})
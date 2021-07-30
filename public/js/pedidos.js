    var accion          = "n";
    var item        = 0;
    var operacion   = 0;
    var DATA        = [];
    var tipo        = 0;
    var grupo       = 0;
    var clase       = 0;
    var familia     = 0;
    var codigo      = 0;
    var unidad      = "";
    var contador    = 0;
    var atachs      = 0;
    var FILES       = [];
    var nItemdel    = 0;
    var changeState = 0;
    var vistaItem   = 0;
    var diff        = 0;


$(function() { 
    activar_opcion();

    $("#editreg, #editreg, #savereg, #cancelreg, #deletereg, #exportexcel").addClass("oculto");


    //listar el pedido para editar
    $("#tabla_pedidos tbody").on("click",'a',function(e){
        e.preventDefault();
        accion = "u";
        
        switch ($(this).data("poption")) {
            case "editar":
                $("#modalProcess").fadeIn();
                $.post(RUTA+"pedidos/updRequest", {cod:$(this).attr("href")},
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

                        $.post(RUTA+"pedidos/updItems", {cod:data.id_regmov,tipo:data.ctipmov},
                            function (data, textStatus, jqXHR) {
                                $("#detalle_pedido tbody")
                                .empty()
                                .append(data);
                            },
                            "text"
                        );

                        $("#registro")
                            .removeClass("proceso","aprobado")
                            .addClass(data.estado.toLowerCase());

                        if ( data.nEstadoDoc  == 1 ) {
                            $(".sides_process div").removeClass('no_modificar');
                        }else {
                            $(".sides_process div").addClass('no_modificar');
                        }

                        $(".seleccion").fadeOut();


                    },
                    "json"
                );
                break;
        }

        return false;
    })
    //agregar pedidos
    $("#newreg").on("click", function (e) {
        e.preventDefault();

        $("#modalProcess").fadeIn();
        $(".process_header, .details_item").removeClass("desactivado");
        $(".sides_process div").removeClass("no_modificar");
        $("#formProcess")[0].reset();
        $("#cod_pedido,#cod_proy,#cod_cost,#cod_area,#cod_transporte,#cod_solicitante,#cod_tipo").val("");
        $("#registro, #documento").val("EN PROCESO");
        $("#detalle_pedido tbody").empty();
        $("#saveItem span").removeClass('parpadea');
        $("#tipo").removeClass("desactivado");
        $(".seleccion").fadeOut();
        $("#registro, #documento")
            .removeClass('aprobado','consulta')
            .addClass('proceso');

        vistaItem = 0;

        $.post(RUTA+"pedidos/newRequest", {data:0},
            function (data, textStatus, jqXHR) {
                $("#numero").val(data.numero);
                $("#cod_pedido").val(data.codigo);
            },
            "json"
        );

        accion = "n";

        return false;
    });

    $("#cancelItem").on("click", function (e) {
        e.preventDefault();

        $("#modalProcess").fadeOut();
        $("#formProcess")[0].reset();
        $("#detalle_pedido tbody, #tableAdjuntos tbody").empty();
        $("#fileAtachs")[0].reset();
        $("#saveItem span").removeClass('parpadea');
        
        return false;
    });

    $("#preview").on("click", function (e) {
        e.preventDefault();
        
        if ( $("#detalle_pedido tbody tr").length > 0) {
            dataPreview();
            $("#modalPreview").fadeIn();
        }else {
            mostrarMensaje("msj_error","No se puede generar vista previa, faltan datos");
        }
        
        return false;
    });

    //cerrar el modal de busqueda
    $("#closeModalProcess").on("click", function (e) {
        e.preventDefault()

        if ( $("#saveItem span").hasClass('parpadea') ) {
            $("#dialogConfirm").fadeIn();
        }
        else{
            $("#modalProcess").fadeOut();
            getListRequest();
        }
        
        return false;
    });

    $("#btnYes").on("click", function (e) {
        e.preventDefault();

        $("#modalProcess").fadeOut();
        $("#dialogConfirm").fadeOut();
        $("#formProcess")[0].reset();
        $("#detalle_pedido tbody").empty();
        $("#saveItem span").removeClass('parpadea');

        return false;
    });

    $("#btnNo").on("click", function (e) {
        e.preventDefault();

        $("#dialogConfirm").fadeOut();

        return false;
    });

    $("#closeModalItems").on("click", function (e) {
        e.preventDefault()

        $("#modalItems").fadeOut();
        
        return false;
    });

    $("#closeModalPreview").on("click", function (e) {
        e.preventDefault()
        $(".insidePreview object").attr("data","");
        $("#modalPreview").fadeOut();
        
        return false;
    });

    //desplegar las listas
    //activar el cuadro de proyectos
    $("#proyecto").focus(function (e) { 
        e.preventDefault();
        
        $("#cod_proy").val("");
        $(this).select();
        $(".seleccion").fadeOut();

        $(this).next(".seleccion").slideDown();

        return false;
    });

    //seleccion de las lista proyectos
    $("#listaProyectos").on("click","a", function (e) {
        e.preventDefault();

        $("#cod_proy").val($(this).attr("href"));
        $("#proyecto").val($(this).text());

        $(this).parent().parent().parent().slideUp();
        $("#saveItem span").addClass('parpadea');

        return false;
    });

     //activar el cuadro de proyectos
    $("#costos").focus(function (e) { 
        e.preventDefault();
        
        $("#cod_cost").val("");
        $(this).select();
        $(".seleccion").fadeOut();

        $(this).next(".seleccion").slideDown();

        return false;
    });

    //seleccion de las lista proyectos
    $("#listaCostos").on("click","a", function (e) {
        e.preventDefault();

        $("#cod_cost").val($(this).attr("href"));
        $("#costos").val($(this).text());

        $(this).parent().parent().parent().slideUp();
        $("#saveItem span").addClass('parpadea');


        return false;
    });

    //LISTA DESPLEGABLE PARA AREAS
    //activar el cuadro de areas
    $("#area").focus(function (e) { 
        e.preventDefault();
        
        $("#cod_area").val("");
        $(this).select();
        $(".seleccion").fadeOut();

        $(this).next(".seleccion").slideDown();

        return false;
    });

    //seleccion de la lista areas
    $("#listaAreas").on("click","a", function (e) {
        e.preventDefault();

        $("#cod_area").val($(this).attr("href"));
        $("#area").val($(this).text());

        $(this).parent().parent().parent().slideUp();
        $("#saveItem span").addClass('parpadea');

        return false;
    });

    $("#area").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#listaAreas li a").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    /******************** */
    //activar el transporte
    $("#transporte").focus(function (e) { 
        e.preventDefault();
        
        $("#cod_transporte").val("");
        $(this).select();
        $(".seleccion").fadeOut();

        $(this).next(".seleccion").slideDown();

        return false;
    });

    //seleccion de la lista areas
    $("#listaTransporte").on("click","a", function (e) {
        e.preventDefault();

        $("#cod_transporte").val($(this).attr("href"));
        $("#transporte").val($(this).text());

        $(this).parent().parent().parent().slideUp();
        $("#saveItem span").addClass('parpadea');

        return false;
    });

    ///LISTA DESPLEGABLE PARA SOLICITANTE
    //activar el solicitante

    //seleccion de la lista solcitantes
    $("#listaSolicitante").on("click","a", function (e) {
        e.preventDefault();

        $("#cod_solicitante").val($(this).attr("href"));
        $("#solicitante").val($(this).text());

        $(this).parent().parent().parent().slideUp();
        $("#saveItem span").addClass('parpadea');

        return false;
    });

    //filtrado en la lista de solicitante
    $("#solicitante").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#listaSolicitante li a").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    //activar llenar los datos del solicitante
    $("#solicitante").focus(function (e) { 
        e.preventDefault();
        
        $(this)
        .select()
        .parent().children("img").css("display","block");

        $(".seleccion").fadeOut();

        $.post(RUTA+"pedidos/solicitantes", {data:0},
            function (data, textStatus, jqXHR) {
                $("#solicitante").parent().children("img").css("display","none");

                $("#listaSolicitante")
                .empty()
                .append(data)
                .end();

                $("#listaSolicitante").parent().slideDown();
            },
            "text"
        );

        return false;
    });

    //********************** */

    //seleccionar el tipo
    $("#tipo").focus(function (e) { 
        e.preventDefault();
        
        $("#cod_tipo").val("");
        $(this).select();
        $(".seleccion").fadeOut();

        $(this).next(".seleccion").slideDown();
        

        return false;
    });

    //seleccion de la lista areas
    $("#listaTipo").on("click","a", function (e) {
        e.preventDefault();

        $("#cod_tipo").val( $(this).attr("href") == 39 ? "B":"S" );
        $("#tipo").val($(this).text());

        $("#tipo").addClass("desactivado");

        $(this).parent().parent().parent().slideUp();        
        $("#saveItem span").addClass('parpadea');

        return false;
    });

    //agregar item al cuadro
    $("#addItem").on("click", function (e) {
        e.preventDefault();

        if ( $("#tipo").val().length <= 0 ) {
            mostrarMensaje("msj_error","Seleccione el tipo");
            $("#tipo").focus();
            
            return false;
        }

        operacion = $("#tipo").val() == '01 BIENES' ? 1 : 2;

        $("#waitmodal").fadeIn();

        $.post(RUTA+"pedidos/pageInitials", {data:0},
            function (data, textStatus, jqXHR) {
                $(".pagination ul")
                .empty()
                .append(data);
            },
            "text"
        );

        var letrainicial = operacion == 1 ? "1":"S";

        $.post(RUTA+"pedidos/ItemsByLetter", {tipo:operacion,letra:letrainicial},
                function (data, textStatus, jqXHR) {
                    $("#tableItems tbody")
                        .empty()
                        .append(data);
                    
                    $("#waitmodal").fadeOut();
                    
                },
                "text"
        )
        
        $("#waitmodal").fadeOut();
        $("#modalItems").fadeIn();

        return false;
    });

    $("#imporItems").on("click", function (e) {
        e.preventDefault();

        if ( $("#tipo").val().length <= 0 ) {
            mostrarMensaje("msj_error","Seleccione el tipo");
            $("#tipo").focus();
            
            return false;
        }
        
        $("#modalImport").fadeIn();

        return false;
    });

    $("#inputSearchItems").on("keypress", function (e) {
        //e.preventDefault()
        if(e.which == 13 && $(this).val().length > 2) {
            $("#waitmodal").fadeIn();
            $.post(RUTA+"pedidos/ItemsByWord", {palabra:$(this).val(),tipo:operacion},
                function (data, textStatus, jqXHR) {
                    $("#tableItems tbody")
                        .empty()
                        .append(data);
                    $("#waitmodal").fadeOut();  
                },
                "text"
            );
        }
    });

    $(".pagination").on("click","a",function(e){
        e.preventDefault();

        $("#waitmodal").fadeIn();

        $.post(RUTA+"pedidos/ItemsByLetter", {tipo:operacion,letra:$(this).attr("href")},
                function (data, textStatus, jqXHR) {
                    $("#tableItems tbody")
                        .empty()
                        .append(data);
                    
                    $("#waitmodal").fadeOut();
                },
                "text"
        )

        return false;
    })

    //selecciona item de la lista
    $("#tableItems").on("click","tr", function (e) {
        e.preventDefault();

        var codigo = $(this).children('td:eq(0)').text();
        var indice = $(this).children('td:eq(0)').data('idprod');
        var descripcion = $(this).children('td:eq(1)').text() + " " + $(this).children('td:eq(2)').text() + " " + $(this).children('td:eq(3)').text();
        var unidad = $(this).children('td:eq(5)').text();
        var factor = $(this).children('td:eq(6)').text();
        var ncodmed = $(this).children('td:eq(0)').data('unidad')
        
        tipo = operacion == 1 ? "BIEN" : "SERV";
        
        item = $("#detalle_pedido tbody tr").length + 1;

        var exist = DATA.includes(indice);

        if ( !exist ) {
            var row = '<tr class="lh1_2rem">'+
                    '<td class="con_borde centro"><a href="#" data-topcion="edit"><i class="far fa-edit"></i></a></td>'+
                    '<td class="con_borde centro"><a href="#" data-topcion="delete"><i class="fas fa-eraser"></i></a></td>'+
                    '<td class="con_borde drch pr20">'+ $.strPad(item,3) +'</td>'+
                    '<td class="con_borde centro" data-indice="'+ indice +'" data-unidad="'+ ncodmed +'">'+ codigo +'</td>'+
                    '<td class="con_borde pl10">'+ descripcion +'</td>'+
                    '<td class="con_borde centro">'+ unidad +'</td>'+
                    '<td class="con_borde" contenteditable="true"><input type="number" class="drch"></td>'+
                    '<td class="con_borde"></td>'+
                    '<td class="con_borde"></td>'+
                    '<td class="con_borde centro" contenteditable="true"><input type="checkbox" class="drch"></td>'+
                    '<td class="con_borde centro">'+ tipo +'</td>'+
                    '<td class="con_borde oculto">'+ factor +'</td>'+
                '</tr>'
        
            $("#detalle_pedido tbody").append(row);
            DATA.push(indice);

            //mostrarMensaje("msj_info","Item añadido");
            $("#saveItem span").addClass('parpadea');

        }else{
            mostrarMensaje("msj_error","El Item ya fue seleccionado");
        }
        
        return false;
    });

    //acciones de la tabla de detalles
    $("#detalle_pedido tbody").on("click",'a', function (e) {
        e.preventDefault();

        if ($(this).data("topcion") == "delete")
            $(this).parent().parent().remove();

        fill3Tables($("#detalle_pedido tbody > tr"),2);

        return false;
    });

    //añadir item catalogo
    $("#newItem").on("click", function (e) {
        e.preventDefault();

        $(".addItemWindow").css('right',"0");
        return false;
    });

    //verificar las fechas de vencimiento
    $("#fechaven").change(function (e) { 
        e.preventDefault();

        var entrega = new Date($(this).val()).getTime();
        var actual  = new Date($("#fecha").val()).getTime();

        diff = (entrega - actual)/(1000*60*60*24);

        if (diff < 1 ) {
            mostrarMensaje("msj_error","Verifique la fecha de limite del pedido");
            return false;
        }
        
        return false;
    });

     //subir archivo excel
    $("#importForm").on("submit", function (e) {
        e.preventDefault()

        operacion = $("#tipo").val() == '01 BIENES' ? 1 : 2;
        
        $("#operacion").val(operacion);

        $.ajax({
            // URL to move the uploaded image file to server
            url: RUTA + 'pedidos/getImportFile',
            // Request type
            type: "POST", 
            // To send the full form data
            data: new FormData( this ),
            contentType:false,      
            processData:false,     
            // UI response after the file upload  
            success: function(data)
            {
                $("#detalle_pedido tbody")
                .empty()
                .append(data);

                $("#modalImport").fadeOut();
            }
        });
        
        return false;
    });

    //aceptar para subir los datos del archivo
    $("#btnConfirmImport").on("click", function (e) {
        e.preventDefault();

        $("#importForm").trigger("submit");

        return false;
    });

    $("#btnCancelImport").on("click", function (e) {
        e.preventDefault();

        $("#importForm")[0].reset();
        $("#modalImport").fadeOut();
       
        return false;
    });

    //grabar Pedido 
    $("#saveItem").on("click", function (e) {
        e.preventDefault();

        if ($("#cod_proy").val().length <= 0) {
            mostrarMensaje("msj_error","Seleccione el proyecto");
            return false;
        }else if($("#cod_area").val().length <= 0){
            mostrarMensaje("msj_error","Seleccione el área");
            return false;
        }else if($("#cod_cost").val().length <= 0){
            mostrarMensaje("msj_error","Seleccione el centro de costos");
            return false;
        }else if($("#cod_transporte").val().length <= 0){
            mostrarMensaje("msj_error","Seleccione el tipo de transporte");
            return false;
        }else if($("#concepto").val().length <= 0){
            mostrarMensaje("msj_error","Rellene el concepto");
            return false;
        }else if($("#cod_solicitante").val().length <= 0){
            mostrarMensaje("msj_error","Seleccione el solicitante");
            return false;
        }else if($("#cod_tipo").val().length <= 0){
            mostrarMensaje("msj_error","Seleccione el tipo de pedido");
            return false;
        }else if($("#detalle_pedido tbody tr").length <= 0){
            mostrarMensaje("msj_error","Añada productos al pedido");
            return false;
        }else if(diff < 2) {
            mostrarMensaje("msj_error","Verifique la fecha de limite del pedido");
            return false;
        }


        var str = $("#formProcess").serialize();
        
        if (accion == "n") {
            $("#waitmodal").fadeIn();
            $.post(RUTA+"pedidos/registro", str,
                function (data, textStatus, jqXHR) {
                    //registra los detalles
                    registerItemsTable($("#cod_pedido").val());
                    //envia los adjuntos
                    if (atachs > 0) {
                        $("#fileAtachs").trigger("submit");
                    }

                    $("#waitmodal").fadeOut();
                    $("#saveItem span").removeClass('parpadea');
                    getListRequest();
                    mostrarMensaje("msj_correcto","Pedido registrado");
                },
                "text"
            );
        }else {
            if ($("#saveItem span").hasClass('parpadea')){
                $.post(RUTA+"pedidos/actualiza", str,
                function (data, textStatus, jqXHR) {
                    //registra los detalles
                    registerItemsTable($("#cod_pedido").val());
                    //envia los adjuntos
                    if (atachs > 0) {
                        $("#fileAtachs").trigger("submit");
                    }

                    $("#waitmodal").fadeOut();
                    $("#saveItem span").removeClass('parpadea');
                    getListRequest();
                    mostrarMensaje("msj_correcto","Pedido actualizado");
                },
                "text"
            );
            }
            else{
                mostrarMensaje("msj_info","No se hizo ninguna modificación");
            }
        }
        return false;
    });

     //enviar emails
    $("#sendItem").on("click", function (e) {
        e.preventDefault();

        $("#dialogSend").fadeIn();
        
        return false;
    });

    //llamar lista de correos
    $("#callEmailList").on("click", function (e) {
        e.preventDefault();

        $(".listEmails").css('right',"0");

        $.post(RUTA+"pedidos/correos", {data:0},
            function (data, textStatus, jqXHR) {
                $("#corporativos tbody")
                .empty()
                .append(data);
            },
            "text"
        );

        return false;
    });

    //pasar la lista de correos
    $("#corporativos tbody").on("click","tr", function (e) {
        e.preventDefault();

        contador++;

        var row = '<tr class="con_borde">'+
                    '<td class="con_borde pl20">'+$(this).data("correo")+'</td>'+
                    '<td class="con_borde centro"><a href="#"><i class="fas fa-trash-alt"></i></a></td>'+
                '</tr>';

        if (contador < 10) {
            $("#listMailToSend tbody").append(row);
        }else{
            mostrarMensaje("msj_info","Maximo 10 correos");
        }        

        return false;
    });

    //borrar los correos
    $("#listMailToSend tbody").on("click","tr", function (e) {
        e.preventDefault();

        $(this).remove();

        return false;
    });

    //cerrar la lista de emails
    $(".listEmails").on("click","button", function (e) {
        e.preventDefault();

        $(".listEmails").css('right',"-100%");

        return false;
    });

    //grabar los pedidos
    $("#btnSendConfirm").on("click", function (e) {
        e.preventDefault();
        
        $(".listEmails").css('right',"-100%");
        if ($("#saveItem span").hasClass('parpadea')){
            mostrarMensaje("msj_error","Debe grabar el pedido");
            return false;
        }else if($("#listMailToSend tbody tr").length <= 0){
            mostrarMensaje("msj_error","No ha seleccionado un correo válido");
            return false;
        }else if ($("#mail_mssg").val() <= 0 ){
            mostrarMensaje("msj_error","Escriba el mensaje para el envio");
            return false;
        }

        getMails($("#cod_pedido").val(),$("#cod_tipo").val());
        
        return false;
    });

    //cancelar los pedidos
    $("#btnSendCancel").on("click", function (e) {
        e.preventDefault();
        $("#listMailToSend tbody").empty();
        $(".listEmails").css('right',"-100%");
        $("#dialogSend").fadeOut();
        return false;
    });

    //PROCESO PARA ADJUTAR ARCHVOS
    //subir archivos
    $("#upAttach").on("click", function (e) {
        e.preventDefault();
        
        $("#modalAtach").fadeIn();

        return false;
    });

    $("#pickFiles").on("click", function (e) {
        e.preventDefault();

        $("#uploadAtach").trigger("click");
        
        return false;
    });

    //mostrar los archivos adjuntos
    $("#uploadAtach").on("change", function (e) {
        e.preventDefault();

        var fp = $("#uploadAtach");
        var lg = fp[0].files.length; // get length
        var items = fp[0].files;
        var fragment = "";

        if (lg > 0) {
            for (var i = 0; i < lg; i++) {
                var fileName = items[i].name; // get file name
                var fileSize = items[i].size; // get file size 
    
                // append li to UL tag to display File info
                fragment +="<tr><td>"+fileName+"</td>"+"<td>"+fileSize+" Kb</td></tr>"
            }
    
            $("#tableAdjuntos tbody").append(fragment);
        }

        return false;
    });

    $("#btnConfirmAtach").on("click", function (e) {
        e.preventDefault();

        atachs = $("#tableAdjuntos tbody tr").length;
        $("#modalAtach").fadeOut();
        return false;
    });

    $("#btnCancelAtach").on("click", function (e) {
        e.preventDefault();

        $("#modalAtach").fadeOut();
        $("#fileAtachs")[0].reset();
        $("#tableAdjuntos tbody").empty();

    });

    //añadir registro de adjuntos
    $("#fileAtachs").on("submit", function (e) {
        e.preventDefault()

        $.ajax({
            // URL to move the uploaded image file to server
            url: RUTA + 'pedidos/uploadCotAtachs',
            // Request type
            type: "POST", 
            // To send the full form data
            data: new FormData( this ),
            contentType:false,      
            processData:false,
            dataType:"json",    
            // UI response after the file upload  
            success: function(data)
            {   
                $.each (data, function (item) { 
                    FILES.push(data[item]); 
                });
                
                registerAtachs($("#cod_pedido").val());

                $("#modalAtach").fadeOut();
                $("#fileAtachs")[0].reset();
                $("#tableAdjuntos tbody").empty();
            }
        });
        
        return false;
    });

    $("#fecha, #concepto, #espec_items").on("change", function (e) {
        e.preventDefault();

        $("#saveItem span").addClass('parpadea');

        return false;
    });

    $("#detalle_pedido tbody").on("change","input", function (e) {
        e.preventDefault();

        $("#saveItem span").addClass('parpadea');
        
        return false;
    });

})

function registerAtachs(codigo){
    if( FILES.length > 0) {
        
        DATA = [];

        $.each(FILES, function(row){
            var NOMBRE = FILES[row],
                CODPED = codigo;

                item = {};

                if (NOMBRE !== ""){
                    item['nombre'] = NOMBRE;
                    item['codped'] = CODPED;
                }

                DATA.push(item);
        })

        INFO    = new FormData();
        aInfo   = JSON.stringify(DATA);
 
        INFO.append('data', aInfo);

        $.ajax({
            data: INFO,
            type: 'POST',
            url : RUTA + 'pedidos/saveAtachs',
            processData: false, 
            contentType: false,
        });
    }
}

function dataPreview(){
        DATA = [];
        var TABLA = $("#detalle_pedido tbody > tr");

        TABLA.each(function(){
            var ITEM        = $(this).find('td').eq(2).text(),
                CODITEM     = $(this).find('td').eq(3).text(),
                DESCRIPCION = $(this).find('td').eq(4).text(),
                CANTIDAD    = $(this).find('td').eq(6).children().val(),
                ESTADO      = $(this).find('td').eq(7).text(),
                UNIDAD      = $(this).find('td').eq(11).text(),
                NUMERO      = $("#numero").val(),
                FECHA       = $("#fecha").val(),
                USUARIO     = $("#usuario").val(),
                PROYECTO    = $("#proyecto").val(),
                AREA        = $("#area").val(),
                COSTOS      = $("#costos").val(),
                TRANSPORTE  = $("#transporte").val(),
                CONCEPTO    = $("#concepto").val(),
                SOLICITANTE = $("#solicitante").val(),
                ESTREG      = $("#registro").val(),
                ESTDOC      = $("#documento").val(),
                TIPO        = $("#tipo").val(),
                ESPEC       = $("#espec_items").val(),
                DOCTIP      = "VISTA PREVIA";

            item = {};

            if ( ITEM !== '' ){
                item["item"]        = ITEM;
                item["coditem"]     = CODITEM;
                item["cantidad"]    = CANTIDAD;
                item["descripcion"] = DESCRIPCION;
                item["estado"]      = ESTADO;
                item["unidad"]      = UNIDAD;
                item["numero"]      = NUMERO;
                item["fecha"]       = FECHA;
                item["usuario"]     = USUARIO;
                item["proyecto"]    = PROYECTO;
                item["area"]        = AREA;
                item["costos"]      = COSTOS;
                item["transporte"]  = TRANSPORTE;
                item["concepto"]    = CONCEPTO;
                item["solicitante"] = SOLICITANTE;
                item["registro"]    = ESTREG;
                item["documento"]   = ESTDOC;
                item["tipo"]        = TIPO;
                item["espec_items"] = ESPEC;
                item["doctip"]      = DOCTIP;

                //una vez agregados los datos al array "item" declarado anteriormente hacemos un .push() para agregarlos a nuestro array principal "DATA".
                DATA.push(item);
            }
        });

        INFO    = new FormData();
        aInfo   = JSON.stringify(DATA);
 
        INFO.append('data', aInfo);

        $.ajax({
            data: INFO,
            type: 'POST',
            url : RUTA + 'pedidos/genPreview',
            processData: false, 
            contentType: false,
            dataType:"text",
            success : function(response) {
                $(".insidePreview object")
                .attr("data","")
                .attr("data",response)
            },
            error : function(xhr, status) {
                alert('Disculpe, existió un problema');
            },
        });
}

function registerItemsTable(codigo){
    if ( $("#detalle_pedido tbody tr").length > 0) {
        DATA = [];
        var TABLA = $("#detalle_pedido tbody > tr");

        TABLA.each(function(){
            var ITEM    = $(this).find('td').eq(2).text(),
                INDICE  = $(this).find('td').eq(3).data("indice"), 
                CODITEM = $(this).find('td').eq(3).text(),
                CANTIDAD= $(this).find('td').eq(6).children().val(),
                ESTADO  = 1,
                UNIDAD  = $(this).find('td').eq(3).data("unidad"),
                VERIFICA= $(this).find('td').eq(9).children().prop("checked"),
                CODPED  = codigo;
            
            item = {};

            if ( ITEM !== '' ){
                item["item"]     = ITEM;
                item["indice"]   = INDICE;
                item["coditem"]  = CODITEM;
                item["cantidad"] = CANTIDAD;
                item["estado"]   = ESTADO;
                item["unidad"]   = UNIDAD;
                item["codped"]   = CODPED;
                item["verifica"] = VERIFICA;

                //una vez agregados los datos al array "item" declarado anteriormente hacemos un .push() para agregarlos a nuestro array principal "DATA".
                DATA.push(item);
            }
        });

        INFO    = new FormData();
        aInfo   = JSON.stringify(DATA);
 
        INFO.append('data', aInfo);

        $.ajax({
            data: INFO,
            type: 'POST',
            url : RUTA + 'pedidos/saveItemsTable',
            processData: false, 
            contentType: false,
        });
    }
}

function getListRequest(){
    $.post(RUTA+"pedidos/mainList", data=0,
        function (data, textStatus, jqXHR) {
            $("#tabla_pedidos tbody")
                .empty()
                .append(data);
        },
        "text"
    );
}

function getMails(codigo,tipo){
    if ( $("#listMailToSend tbody tr").length > 0){
        DATA = [];
        var TABLA = $("#listMailToSend tbody > tr");

        TABLA.each(function(){
            var MAIL    = $(this).find('td').eq(0).text(),
                MSG     = $("#mail_mssg").val(),
                CODPED  = codigo,
                TIPPED  = tipo,
                ORIGEN  = "carroyo@sepcon.net";
            
            item = {};

            if ( MAIL !== '' ){
                item["mail"]    = MAIL;
                item["msg"]     = MSG;
                item["codped"]  = CODPED;
                item["tipo"]    = TIPPED;
                item["origen"]  = ORIGEN;

                //una vez agregados los datos al array "item" declarado anteriormente hacemos un .push() para agregarlos a nuestro array principal "DATA".
                DATA.push(item);
            }
        });

        INFO    = new FormData();
        aInfo   = JSON.stringify(DATA);
 
        INFO.append('data', aInfo);

        $.ajax({
            data: INFO,
            type: 'POST',
            url : RUTA + 'pedidos/mailProcess',
            processData: false, 
            contentType: false,
            success : function(response) {
                if(response) {
                    mostrarMensaje("msj_correcto","Correo enviado");
                    $("#dialogSend").fadeOut();
                    $("#btnSendCancel").trigger("click");
                }else {
                    mostrarMensaje("msj_error","No se completo el envio");
                }
            },
        });
    }
}


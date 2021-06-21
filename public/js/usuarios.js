$(function(){
    var accion = "";
    var tab = "";
    var actual_tab = "tab1";
    var item = 0;

    activar_opcion();

    $("#editreg, #savereg, #cancelreg, #deletereg, #exportexcel").addClass("oculto");

    ////#region  comandos
    //agregar un registro
    $("#newreg").on("click", function (e) {
        e.preventDefault();

        $("form")[0].reset();
        $("#detalle_modulos tbody").empty();

        $("#modalProcess").fadeIn();

        $.post(RUTA+"usuarios/newCode", {data:0},
            function (data, textStatus, jqXHR) {
                $("#codigo").val(data);
            },
            "text"
        );

        accion = "n";

        return false;
    });

    $("#saveItem").on("click", function (e) {
        e.preventDefault();

        if($("#usuario").val().length <= 0){
            mostrarMensaje("msj_error","Rellene el campo usuario");
            return false;
        }else if($("#nombres").val().length <= 0){
            mostrarMensaje("msj_error","Rellene el campo nombres");
            return false;
        }else if($("#clave").val().length <= 0){
            mostrarMensaje("msj_error","Rellene el campo clave");
            return false;
        }else if($("#cod_niv").val().length <= 0){
            mostrarMensaje("msj_error","Seleccione el nivel");
            return false;
        }else if($("#cod_est").val().length <= 0){
            mostrarMensaje("msj_error","Selecciones el estado");
            return false;
        }
      
        $("#formProcess").trigger("submit");

        return false;
    });

    $("#formProcess").submit(function (e) { 
        e.preventDefault();
        
        var str = $(this).serialize();

        if ( accion=="n" ){
            $.post(RUTA+"usuarios/newUser", str,
                function (data, textStatus, jqXHR) {
                    if (!data.error){
                        saveModules(data.codigo);
                        saveProyects(data.codigo);
                        saveWarehouse(data.codigo);
                        saveMails(data.codigo);
                        $("#saveItem span").removeClass('parpadea');
                        mostrarMensaje("msj_info",data.mensaje);
                    }else {
                        mostrarMensaje("msj_error",data.mensaje);
                    }
                },
                "json"
            );
        }else{
            $.post(RUTA+"usuarios/updateUser", str,
                function (data, textStatus, jqXHR) {
                    if (!data.error){
                        saveModules(data.codigo);
                        saveProyects(data.codigo);
                        saveWarehouse(data.codigo);
                        saveMails(data.codigo);
                        $("#saveItem span").removeClass('parpadea');
                        mostrarMensaje("msj_info",data.mensaje);
                    }else {
                        mostrarMensaje("msj_error",data.mensaje);
                    }
                },
                "json"
            );
        }

        return false;
    });
    //#endregion comandos
    
    //#region combos
    //seleccionar nivel
    $("#nivel").focus(function (e) { 
        e.preventDefault();

        $(".seleccion").fadeOut();

        $.post(RUTA +"usuarios/niveles", {data:9},
            function (data, textStatus, jqXHR) {
                $("#listaNivel")
                .empty()
                .append(data)
                .end();

                $("#listaNivel").parent().slideDown();
            },
            "text"
        );
        return false;
    });

    $("#listaNivel").on("click","a", function (e) {
        e.preventDefault();

        $("#cod_niv").val($(this).attr("href"));
        $("#nivel").val($(this).text());

        $(this).parent().parent().parent().slideUp();
        $("#saveItem span").addClass('parpadea');

        return false;
    });

    //seleccionar estado
    $("#estado").focus(function (e) { 
        e.preventDefault();

        $(".seleccion").fadeOut();

        $.post(RUTA +"usuarios/estados", {data:9},
            function (data, textStatus, jqXHR) {
                $("#listaEstado")
                .empty()
                .append(data)
                .end();

                $("#listaEstado").parent().slideDown();
            },
            "text"
        );
        return false;
    });

    $("#listaEstado").on("click","a", function (e) {
        e.preventDefault();

        $("#cod_est").val($(this).attr("href"));
        $("#estado").val($(this).text());

        $(this).parent().parent().parent().slideUp();
        $("#saveItem span").addClass('parpadea');

        return false;
    });

    //seleccionar el responsable
    $("#responsable").focus(function (e) { 
        e.preventDefault();

        $(".seleccion").fadeOut();

        $.post(RUTA +"usuarios/responsables", {data:0},
            function (data, textStatus, jqXHR) {
                $("#listaResponsable")
                .empty()
                .append(data)
                .end();

                $("#listaResponsable").parent().slideDown();
            },
            "text"
        );
        return false;
    });

    //seleccion de la lista responsables
    $("#listaResponsable").on("click","a", function (e) {
        e.preventDefault();

        $("#cod_resp").val($(this).attr("href"));
        $("#responsable").val($(this).text());
        $("#cod_cargo").val($(this).data("ccargo"));
        $("#cargo").val($(this).data("dcargo"));

        $(this).parent().parent().parent().slideUp();
        $("#saveItem span").addClass('parpadea');

        return false;
    });

    //filtrado en la lista de solicitante
    $("#responsable").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#listaResponsable li a").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    //#endregion combos

    //activar los tabs
    $(".tabs_process").on("click","a",function(e){
        e.preventDefault();

        $(".tabs_process a").removeClass("tab_selected");
        $(this).addClass("tab_selected");

        $(".tab").hide();

        actual_tab = $(this).data("tab"); 
        tab = '#'+$(this).data("tab");

        $(tab).fadeIn();

        return false;
    });

    //llamer la ventana de proyectos o modulos
    $("#addItem").on("click", function (e) {
        e.preventDefault();

        $(".rightList").css('right',"0");

        if (actual_tab == "tab1"){
            $.post(RUTA+"usuarios/modulos", {data:0},
                function (data, textStatus, jqXHR) {
                    $("#tabla_items tbody")
                    .empty()
                    .append(data);
                },
                "text"
            );   
        }else if(actual_tab == "tab2"){
            $.post(RUTA+"usuarios/proyectos", {data:0},
                function (data, textStatus, jqXHR) {
                    $("#tabla_items tbody")
                    .empty()
                    .append(data);
                },
                "text"
            );
        }else if (actual_tab == "tab3") {
            $.post(RUTA+"usuarios/almacenes", {data:0},
                function (data, textStatus, jqXHR) {
                    $("#tabla_items tbody")
                    .empty()
                    .append(data);
                },
                "text"
            );
        }else if(actual_tab == "tab4"){
            $.post(RUTA+"usuarios/correos", {data:0},
                function (data, textStatus, jqXHR) {
                    $("#tabla_items tbody")
                    .empty()
                    .append(data);
                },
                "text"
            );
        }

        return false;
    });
    
    //mostrar la clave
    $("#eyePassword").on("click",function(e){
        e.preventDefault();

        if ($("#clave").attr("type") == "password") {
            $("#clave").attr("type","text");
            $(this).children("i")
                .removeClass("fa-eye")
                .addClass("fa-eye-slash");
        }else{
            $("#clave").attr("type","password");
            $(this).children("i")
                .removeClass("fa-eye-slash")
                .addClass("fa-eye");
        }   
        return false;
    })

    //pasar proyectos o modulos
    $("#tabla_items").on("click","tr",function(e){
        e.preventDefault();
        if (actual_tab === "tab1"){
            var codigo      = $(this).children('td:eq(0)').text();
            var descripcion =  $(this).children('td:eq(1)').text();
            //verifica si existe el modulo en la tabla
            if (!checkExistTable($("#detalle_modulos tbody tr"),descripcion,2)) {
                item = $("#detalle_modulos tbody tr").length + 1;
                var row = '<tr>'+
                            '<td class="centro con_borde"><a href="#"><i class="fas fa-eraser"></i></a> </td>'+
                            '<td class="con_borde pl20">'+$.strPad(item,2)+'</td>'+
                            '<td class="con_borde pl20" data-codigo="'+codigo+'">'+descripcion+'</td>'+
                            '<td class="centro con_borde"><input type="checkbox"></td>'+
                            '<td class="centro con_borde"><input type="checkbox"></td>'+
                            '<td class="centro con_borde"><input type="checkbox"></td>'+
                            '<td class="centro con_borde"><input type="checkbox"></td>'+
                            '<td class="centro con_borde"><input type="checkbox"></td>'+
                            '<td class="centro con_borde"><input type="checkbox"></td>'+
                            '<td class="centro con_borde"><input type="checkbox"></td>'+
                            '<td class="centro con_borde"><input type="checkbox" class="total"></td>'+
                        '</tr>';
                
                if (descripcion != "")
                    $("#detalle_modulos tbody").append(row);
            }else{
                mostrarMensaje("msj_error","Ya esta registrado");
            }
            return false;
        }else if ( actual_tab == "tab2") {
            var codigo      = $(this).children('td:eq(0)').text();
            var descripcion =  $(this).children('td:eq(1)').text();
            var idx = $(this).data("idx");
            //verifica si existe el modulo en la tabla
            if (!checkExistTable($("#detalle_proyectos tbody tr"),codigo,2)){
                item = $("#detalle_proyectos tbody tr").length + 1;

                var row = '<tr>'+
                            '<td class="centro con_borde"><a href="#"><i class="fas fa-eraser"></i></a> </td>'+
                            '<td class="con_borde centro">'+$.strPad(item,2)+'</td>'+
                            '<td class="con_borde pl20" data-idx="'+ idx +'">'+codigo+'</td>'+
                            '<td class="con_borde pl20">'+descripcion+'</td>'+
                        '</tr>';
                
                $("#detalle_proyectos tbody").append(row);
            } else{
                mostrarMensaje("msj_error","Ya esta registrado");
            }
        }else if ( actual_tab == "tab3") {
            var codigo      = $(this).children('td:eq(0)').text();
            var descripcion =  $(this).children('td:eq(1)').text();
            var idx = $(this).data("idx");
            //verifica si existe el modulo en la tabla
            if (!checkExistTable($("#detalle_almacen tbody tr"),codigo,2)){
                item = $("#detalle_almacen tbody tr").length + 1;

                var row = '<tr>'+
                            '<td class="centro con_borde"><a href="#"><i class="fas fa-eraser"></i></a> </td>'+
                            '<td class="con_borde centro">'+$.strPad(item,2)+'</td>'+
                            '<td class="con_borde pl20" data-idx="'+ idx +'">'+codigo+'</td>'+
                            '<td class="con_borde pl20">'+descripcion+'</td>'+
                        '</tr>';
                
                $("#detalle_almacen tbody").append(row);
            } else{
                mostrarMensaje("msj_error","Ya esta registrado");
            }
        }else if (actual_tab == "tab4") {
            var nombre      = $(this).children('td:eq(0)').text();
            var correo =  $(this).children('td:eq(1)').text();
            var idx = $(this).data("idx");
            //verifica si existe el modulo en la tabla
            if (!checkExistTable($("#detalle_aprobacion tbody tr"),nombre,3)){
                item = $("#detalle_aprobacion tbody tr").length + 1;

                var row = '<tr>'+
                            '<td class="centro con_borde"><a href="#"><i class="fas fa-eraser"></i></a> </td>'+
                            '<td class="con_borde centro">'+$.strPad(item,2)+'</td>'+
                            '<td class="con_borde centro"></td>'+
                            '<td class="con_borde pl20"></td>'+
                            '<td class="con_borde pl20" data-idx="'+ idx +'">'+nombre+'</td>'+
                            '<td class="con_borde pl20">'+correo+'</td>'+
                        '</tr>';
                
                $("#detalle_aprobacion tbody").append(row);
                $(".rightList").css('right',"-100%");

            } else{
                mostrarMensaje("msj_error","Ya esta registrado");
            }
        }
        else if ( actual_tab == "modulo") {
            var modulo = $(this).children('td:eq(1)').text();
            var idx = $(this).data("idx");

            $("#detalle_aprobacion tbody > tr:last-child").find('td').eq(2).text(idx);
            $("#detalle_aprobacion tbody > tr:last-child").find('td').eq(3).text(modulo);

            actual_tab = "tab4";
            $(".rightList").css('right',"-100%");

        }
        return false;
    })

    //cerrar items 
    $("#closeItems").on("click",function(e){
        e.preventDefault();

        $(".rightList").css('right',"-100%");

        return false;
    })

    //cerra la vemtana de proceso
    $("#closeModalProcess,#cancelItem").on("click", function (e) {
        e.preventDefault();

        $.post(RUTA + "usuarios/allusers",
            function (data, textStatus, jqXHR) {
                $("#tabla_usuarios tbody")
                .empty()
                .append(data);

                $("#modalProcess").fadeOut();

            },
            "text"
        );

        return false;
    });

    //borrar y regenerar los numeros de la tabla de modulos
    $("#detalle_modulos tbody").on("click","a", function (e) {
        e.preventDefault();

        $(this).parent().parent().remove();

        fillTables($("#detalle_modulos tbody > tr"),1);

        return false;
    });

     //borrar y regenerar los numeros de la tabla de proyectos
    $("#detalle_proyectos tbody").on("click","a", function (e) {
        e.preventDefault();

        $(this).parent().parent().remove();

        fillTables($("#detalle_proyectos tbody > tr"),1);

        return false;
    });

    $("#detalle_modulos").on("click",".total",function(e) { 
        //e.preventDefault();
        var sw = $(this).prop("checked");

        $(this).parent().parent().find('td').eq(3).children().prop("checked",sw);
        $(this).parent().parent().find('td').eq(4).children().prop("checked",sw);
        $(this).parent().parent().find('td').eq(5).children().prop("checked",sw);
        $(this).parent().parent().find('td').eq(6).children().prop("checked",sw);
        $(this).parent().parent().find('td').eq(7).children().prop("checked",sw);
        $(this).parent().parent().find('td').eq(8).children().prop("checked",sw);
        $(this).parent().parent().find('td').eq(9).children().prop("checked",sw);
       
        // return false;
    })

    $("#usuario, #nombres, #clave").on("change", function (e) {
        e.preventDefault();

        $("#saveItem span").addClass('parpadea');

        return false;
    });

    $("#detalle_aprobacion tbody").on("click","td", function (e) {
        e.preventDefault();

        $.post(RUTA+"usuarios/parametros", {data:0},
            function (data, textStatus, jqXHR) {
                $("#tabla_items tbody")
                .empty()
                .append(data);
            },
            "text"
        );
        
        actual_tab = "modulo";

        $(".rightList").css('right',"0");

        return false;
    });

    //acciones en la tabla usuario 
    $("#tabla_usuarios tbody").on("click","a", function (e) {
        e.preventDefault();

        switch ($(this).data("action")) {
            case 's':
                $.post(RUTA+"usuarios/showpass", {data:$(this).attr("href")},
                    function (data, textStatus, jqXHR) {
                        $("#seepass").text(data);
                    },
                    "text"
                );
                $("#dialogShow").fadeIn();
                break;        
            case 'u':
                $.post(RUTA+"usuarios/userById", {data:$(this).attr("href")},
                    function (data, textStatus, jqXHR) {

                        $("#cod_user").val(data.id_cuser);
                        $("#cod_resp").val(data.ccodper);
                        $("#cod_niv").val(data.nnivuser);
                        $("#cod_est").val(data.nestado);
                        $("#old_pass").val(data.cpasword);
                        $("#codigo").val(data.ccoduser);
                        $("#usuario").val(data.cnameuser);
                        $("#nombres").val(data.cnombres);
                        $("#clave").val(data.cpasword);
                        $("#responsable").val(data.responsable);
                        $("#desde").val(data.fvigdesde);
                        $("#hasta").val(data.fvighasta);
                        $("#nivel").val(data.nivel);
                        $("#estado").val(data.estado);


                        $.post(RUTA +"usuarios/getModules", {cod: $("#cod_user").val()},
                            function (data, textStatus, jqXHR) {
                                $("#detalle_modulos tbody")
                                .empty()
                                .append(data);
                            },
                            "text"
                        );

                        $.post(RUTA +"usuarios/getProyects", {cod: $("#cod_user").val()},
                            function (data, textStatus, jqXHR) {
                                $("#detalle_proyectos tbody")
                                .empty()
                                .append(data);
                            },
                            "text"
                        );

                        $.post(RUTA +"usuarios/getWarehouse", {cod: $("#cod_user").val()},
                            function (data, textStatus, jqXHR) {
                                $("#detalle_almacen tbody")
                                .empty()
                                .append(data);
                            },
                            "text"
                        );

                        $.post(RUTA +"usuarios/getAutorization", {cod: $("#cod_user").val()},
                            function (data, textStatus, jqXHR) {
                                $("#detalle_aprobacion tbody")
                                .empty()
                                .append(data);
                            },
                            "text"
                        );
                    },
                    "json"
                );
                accion="u";
                $("#modalProcess").fadeIn();
                break;
            case 'd':
                $("#cod_user").val($(this).attr("href"));
                $("#dialogConfirm").fadeIn();
                break;
        }

        return false;
    });
    //ver la clave del usuario;

    $("#btnCloseShow").on("click", function(e) {
        e.preventDefault();

        $("#dialogShow").fadeOut();
        
        return false;
    });

    $("#btnConfirm").on("click",function(e){
        e.preventDefault();
        
        $.post(RUTA+"usuarios/deleteUser", {cod:$("#cod_user").val()},
            function (data, textStatus, jqXHR) {

                $.post(RUTA + "usuarios/allusers",function (data, textStatus, jqXHR) {
                        $("#tabla_usuarios tbody")
                        .empty()
                        .append(data);

                        mostrarMensaje("msj_info","Usuario desabilitado");
                        $("#dialogConfirm").fadeOut();

                    },
                    "text"
                );
                
            },
            "text"
        );

        return false;
    })

    $("#btnCancel").on("click",function(e){
        e.preventDefault();

        $("#dialogConfirm").fadeOut();
        
        return false;
    })
})

function saveModules(codigo){
    if ( $("#detalle_modulos tbody tr").length > 0){
        DATA = [];
        var TABLA = $("#detalle_modulos tbody > tr");

        TABLA.each(function(){
            var CODMOD      = $(this).find('td').eq(2).data("codigo"),
                AGREGAR     = $(this).find('td').eq(3).children().prop("checked"),
                MODIFICAR   = $(this).find('td').eq(4).children().prop("checked"),
                ELIMINAR    = $(this).find('td').eq(5).children().prop("checked"),
                IMPRIMIR    = $(this).find('td').eq(6).children().prop("checked"),
                PROCESAR    = $(this).find('td').eq(7).children().prop("checked"),
                VISIBLE     = $(this).find('td').eq(8).children().prop("checked"),
                ESTADO      = $(this).find('td').eq(9).children().prop("checked"),
                TODOS       = $(this).find('td').eq(10).children().prop("checked"),
                CODUSER     = codigo;
            
            item = {};

            if ( CODMOD !== '' ){
                item["codm"]  = CODMOD;
                item["agre"]  = AGREGAR;
                item["modi"]  = MODIFICAR;
                item["elim"]  = ELIMINAR;
                item["impr"]  = IMPRIMIR;
                item["proc"]  = PROCESAR;
                item["visi"]  = VISIBLE;
                item["esta"]  = ESTADO;
                item["todo"]  = TODOS;
                item["user"]  = CODUSER;

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
            url : RUTA + 'usuarios/modules',
            processData: false, 
            contentType: false,
        });

    }
}

function saveProyects(codigo){
    if ( $("#detalle_proyectos tbody tr").length > 0){
        DATA = [];
        var TABLA = $("#detalle_proyectos tbody > tr");

        TABLA.each(function(){
            var CODPROY     = $(this).find('td').eq(2).data("idx"),
                CODUSER     = codigo;
            
            item = {};

            if ( CODPROY !== '' ){
                item["codp"]  = CODPROY;
                item["user"]  = CODUSER;

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
            url : RUTA + 'usuarios/proyects',
            processData: false, 
            contentType: false
        });

    }
}

function saveWarehouse(codigo){
    if ( $("#detalle_almacen tbody > tr").length > 0) {
        ALMACEN = [];

        var TABLA = $("#detalle_almacen tbody > tr");
        TABLA.each(function(){
            var CODALM = $(this).find('td').eq(2).data("idx"),
                CODUSER = codigo;

            item = {};

            if ( CODALM !== '' ){
                item["coda"]  = CODALM;
                item["user"]  = CODUSER;

                //una vez agregados los datos al array "item" declarado anteriormente hacemos un .push() para agregarlos a nuestro array principal "DATA".
                ALMACEN.push(item);
            }
        });

        INFO    = new FormData();
        aInfo   = JSON.stringify(ALMACEN); 
 
        INFO.append('almacen', aInfo);

        $.ajax({
            data: INFO,
            type: 'POST',
            url : RUTA + 'usuarios/warehouses',
            processData: false, 
            contentType: false
        });
    }
}

function saveMails(codigo){ 
    if ( $("#detalle_aprobacion tbody > tr").length > 0) {
        DATA = [];

        var TABLA = $("#detalle_aprobacion tbody > tr");
        TABLA.each(function(){
            var CODAPR  = $(this).find('td').eq(3).data("idx"),
                CODMOD  = $(this).find('td').eq(2).text(),
                CODUSER = codigo;

            item = {};

            if ( CODAPR !== '' ){
                item["codp"]  = CODAPR;
                item["codm"]  = CODMOD;
                item["user"]  = CODUSER;

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
            url : RUTA + 'usuarios/aprobaciones',
            processData: false, 
            contentType: false
        });
    }
}




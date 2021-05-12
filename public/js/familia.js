$(function(){
    //dejar marcado el menu
    activar_opcion();

     //nuevo registro
     $("#newreg").on("click", function (e) {
        e.preventDefault();

        $(this).addClass('desactivado');
        $("#searchreg, #exportexcel, #home").addClass('desactivado');
        $(".datainputs, #savereg, #cancelreg").removeClass('desactivado');

        accion = "n";

        return false;
    });

    //editar el registro
    $("#editreg").on("click", function (e) {
        e.preventDefault();

        $(this).addClass('desactivado');
        $("#searchreg, #exportexcel, #home, #newreg").addClass('desactivado');
        $(".datainputs:eq(2), #savereg, #cancelreg").removeClass('desactivado');

        accion = "u";

        return false;
    });

    //cancelar el grabado
    $("#cancelreg").on("click", function (e) {
        e.preventDefault();

        $(this).addClass('desactivado');
        $(".datainputs, #savereg").addClass('desactivado');
        $("#newreg, #searchreg, #exportexcel, #home").removeClass('desactivado');

        $("form")[0].reset();

        $(".seleccion").fadeOut();

        return false;
    });

    //grabar el registro
    $("#savereg").on("click", function (e) {
        e.preventDefault();

        if ( $("#codigo_grupo").val().length <= 0 ) {
            mostrarMensaje("msj_error","Debe Seleccionar un grupo...");
            $(this).focus();
            
            return false;
        }else if ( $("#codigo_clase").val().length <= 0 ) {
            mostrarMensaje("msj_error","Debe Seleccionar la clase...");
            $(this).focus();
            
            return false;
        }else if ( $("#descripcion").val().length <= 0 ) {
            mostrarMensaje("msj_error","Complete la descripción de la familia");
            $(this).focus();
            
            return false;
        }

        if (accion = "n") {
            data = $("form").serialize();

            $.post(RUTA + 'familia/existItem', data, function (data, textStatus, jqXHR) {
               if (data == '0') {
                    data = $("form").serialize();
                    $.post(RUTA + 'familia/newFamily' ,data,function(data,textStatus,jqXHR){
                        mostrarMensaje("msj_correcto",data);
                        $(this).addClass('desactivado');
                        $(".datainputs, #savereg").addClass('desactivado');
                        $("#newreg, #searchreg, #exportexcel, #home").removeClass('desactivado');
                        $("form")[0].reset();
                    })
                    mostrarMensaje("msj_correcto","No existe la clase");
                }else {
                    mostrarMensaje("msj_error","La clase ya existe");
                }
            });
        }else {
            data = $("form").serialize();
            $.post(RUTA + 'familia/modifyFamily' ,data,function(data,textStatus,jqXHR){
                mostrarMensaje("msj_correcto",data);
                $(this).addClass('desactivado');
                $(".datainputs, #savereg").addClass('desactivado');
                $("#newreg, #searchreg, #exportexcel, #home").removeClass('desactivado');
                $("form")[0].reset();
            })
        }

        return false;
    })

    //desplegar grupo
    $("#nombre_grupo").focus(function (e) { 
        e.preventDefault();
        
        $("#codigo_grupo").val("");
        $(this).select();
        $(".seleccion").fadeOut();

        $(this).next(".seleccion").fadeIn();

        return false;
    });

    //desplegar clase
    $("#nombre_clase").focus(function (e) { 
        e.preventDefault();
        
        $("#codigo_clase").val("");
        $(this).select();

        if ( $("#listaClase li").length <= 0 ) {
            mostrarMensaje("msj_info","El grupo seleccionado no tiene clases");
        }else {
            $(this).next(".seleccion").fadeIn();
        }
        
        return false;
    });


    //selecciona el elemento de la grupo
    $("#listaGrupo").on("click","li a", function (e) {
        e.preventDefault();

        $(this).parent().parent().parent().fadeOut();

        $("#codigo_grupo").val( $(this).attr("href") );
        $("#nombre_grupo").val($(this).text());

        if ( $("#codigo_grupo").val().length > 0 ) {
            $.post("familia/getClassList", {data :$("#codigo_grupo").val()},
                function (data, textStatus, jqXHR) {
                    //var codigo = leftPad(data,2);
                    //$("#codigo_clase").val(codigo);
                    $("#listaClase")
                        .empty()
                        .append(data);
                },
                "text"
            );
        } 

        return false;
    });

     //busqueda en el combo de grupo
     $("#nombre_grupo").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#listaGrupo li a").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    //selecciona el elemento de la grupo
    $("#listaClase").on("click","li a", function (e) {
        e.preventDefault();

        $(this).parent().parent().parent().fadeOut();

        $("#codigo_clase").val( $(this).attr("href") );
        $("#nombre_clase").val($(this).text());

        if ( $("#codigo_grupo").val().length > 0 && $("#codigo_clase").val().length > 0) {
            $.post("familia/getFamilyCode", {codgroup :$("#codigo_grupo").val(), codclass :$("#codigo_clase").val()},
                function (data, textStatus, jqXHR) {
                    var codigo = leftPad(data,2);
                    $("#codigo_familia").val(codigo);
                },
                "text"
            );
        }

        return false;
    });

    //busqueda en el combo de clase
    $("#nombre_clase").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#listaClase li a").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    //busqueda
    $("#searchreg").on("click", function (e) {
        e.preventDefault();

        $.post(RUTA + 'familia/listFamilies',function(data,textStatus,jqXHR){
            $(".tableSeek tbody")
            .empty()
            .append(data);

            $("#modalSeek").fadeIn();
        })

        return false
    });

    //cerrar el modal de busqueda
    $("#closeModal").on("click", function (e) {
        e.preventDefault()

        $("#modalSeek").fadeOut();
        
        return false;
    });

    //busqueda en la lista
    $("#inputSearch").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $(".tableSeek tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

     //selecciona item de la lista
     $(".tableSeek").on("click","a", function (e) {
        e.preventDefault();

        if ($(this).data("accion") === "modify") {
            $("#codigo_grupo").val($(this).data("codgrupo"));
            $("#nombre_grupo").val($(this).parent().parent().children('td:eq(0)').text());
            $("#codigo_clase").val($(this).data("codclase"));
            $("#nombre_clase").val($(this).parent().parent().children('td:eq(1)').text());
            $("#codigo_familia").val($(this).data("codfamil"));
            $("#descripcion").val($(this).parent().parent().children('td:eq(2)').text());
            $("#indice").val($(this).attr("href"));
        
            $("#editreg").removeClass('desactivado');
        }else{
            console.log("Marcar para borrar");
        }
        
        $("#modalSeek").fadeOut();
        
        return false;
    });
})
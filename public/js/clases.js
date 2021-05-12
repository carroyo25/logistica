$(function(){
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
        $(".datainputs:eq(1), #savereg, #cancelreg").removeClass('desactivado');

        accion = "u";

        return false;
    });

    //grabar el registro
    $("#savereg").on("click", function (e) {
        e.preventDefault();

        if ( $("#codigo_grupo").val().length <= 0 ) {
            mostrarMensaje("msj_error","Debe Seleccionar un grupo...");
            $(this).focus();
            
            return false;
        }else if ( $("#descripcion").val().length <= 0 ) {
            mostrarMensaje("msj_error","Complete la descripción de la clase");
            $(this).focus();
            
            return false;
        }

        data = $("form").serialize();

        if (accion = "n") {
            $.post(RUTA + 'clase/existItem', data, function (data, textStatus, jqXHR) {
               if (data == '0') {
                    data = $("form").serialize();
                    $.post(RUTA + 'clase/newClass' ,data,function(data,textStatus,jqXHR){
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
            $.post(RUTA + 'clase/modifyClass' ,data,function(data,textStatus,jqXHR){
                mostrarMensaje("msj_correcto",data);
                $(this).addClass('desactivado');
                $(".datainputs, #savereg").addClass('desactivado');
                $("#newreg, #searchreg, #exportexcel, #home").removeClass('desactivado');
                $("form")[0].reset();
            })
        }

        return false;
    });

     //cancelar el grabado
     $("#cancelreg").on("click", function (e) {
        e.preventDefault();

        $(this).addClass('desactivado');
        $(".datainputs, #savereg").addClass('desactivado');
        $("#newreg, #searchreg, #exportexcel, #home").removeClass('desactivado');
        $(".seleccion").fadeOut();

        $("form")[0].reset();

        return false;
    });
    
    //muestra la lista desplegable
    $("#nombre_grupo").focus(function (e) { 
        e.preventDefault();
        
        $("#codigo_grupo").val("");
        $(this).select();

        $(".seleccion").fadeIn();

        return false;
    });

    //busqueda
    $("#searchreg").on("click", function (e) {
        e.preventDefault();

        $.post(RUTA + 'clase/listClasses',function(data,textStatus,jqXHR){
            $(".tableSeek tbody")
            .empty()
            .append(data);    
        })

        $("#modalSeek").fadeIn();
        return false;
    });

    //cerrar el modal de busqueda
    $("#closeModal").on("click", function (e) {
        e.preventDefault()

        $("#modalSeek").fadeOut();
        
        return false;
    });

    //buscar en la tabla
    $("#inputSearch").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $(".tableSeek tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });


    //selecciona el elemento de la lista
    $("#lista").on("click","li a", function (e) {
        e.preventDefault();

        $(this).parent().parent().parent().fadeOut();

        $("#codigo_grupo").val( $(this).attr("href") );
        $("#nombre_grupo").val($(this).text());

        if ( $("#codigo_grupo").val().length > 0 ) {
            $.post("clase/getLastClassCode", {data :$("#codigo_grupo").val()},
                function (data, textStatus, jqXHR) {
                    var codigo = leftPad(data,2);
                    $("#codigo_clase").val(codigo);
                },
                "text"
            );
        }

        return false;
    });

    //busqueda en el combo
    $("#nombre_grupo").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#lista li a").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });


    //selecciona item de la lista
    $(".tableSeek").on("click","a", function (e) {
        e.preventDefault();

        if ($(this).data("accion") === "modify") {
            $("#codigo_grupo").val($(this).data("grp"));
            $("#nombre_grupo").val($(this).parent().parent().children('td:eq(0)').text());
            $("#codigo_clase").val($(this).data("cls"));
            $("#descripcion").val($(this).parent().parent().children('td:eq(1)').text());
            $("#indice").val($(this).attr("href"));
        
            $("#editreg").removeClass('desactivado');
        }else{
            console.log("Marcar para borrar");
        }
        
        
        $("#modalSeek").fadeOut();
        
        return false;
    });
})
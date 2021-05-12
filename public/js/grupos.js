$(function(){

    var accion = "";

    activar_opcion();

    var access = $("#flags").text();

    $("#newreg").on("click", function (e) {
        e.preventDefault();
    
        if ( access.substring(0, 1) != 1 ) {
            mostrarMensaje("msj_error","No tiene permisos suficientes")
            return false;
        }

        $(this).addClass('desactivado');
        $("#searchreg, #exportexcel, #home").addClass('desactivado');
        $(".datainputs, #savereg, #cancelreg").removeClass('desactivado');

        $.post(RUTA + 'grupo/getLastGroupCode', function (data, textStatus, jqXHR) {
            var codigo = leftPad(data,2);
            $("#codigo").val(codigo);

            accion = "n";
        });

        $("#descripcion").focus();

        return false;
    });

    $("#editreg").on("click", function (e) {
        e.preventDefault();

        if ( access.substring(1, 2) != 1) {
            mostrarMensaje("msj_error","No tiene permisos suficientes")
            return false;
        }

        $(this).addClass('desactivado');
        $("#searchreg, #exportexcel, #home, #newreg").addClass('desactivado');
        $(".datainputs, #savereg, #cancelreg").removeClass('desactivado');

        accion = "u";

        return false;
    });

    //grabar los datos 
    $("#savereg").on("click", function (e) {
        e.preventDefault();

        if ( $("#descripcion").val().length <= 0 ) {
            mostrarMensaje("msj_error","Complete la descripción...");
            $(this).focus();
            
            return false;
        }

        data = $("form").serialize();

        if (accion == 'n') {
            $.post(RUTA + 'grupo/existItem', data, function (data, textStatus, jqXHR) {
                if (data == '0') {
                    data = $("form").serialize();
                    $.post(RUTA + 'grupo/newGroup' ,data,function(data,textStatus,jqXHR){
                        mostrarMensaje("msj_correcto",data);
                        $(this).addClass('desactivado');
                        $(".datainputs, #savereg").addClass('desactivado');
                        $("#newreg, #searchreg, #exportexcel, #home").removeClass('desactivado');
                        $("form")[0].reset();
                    })
                }else {
                    mostrarMensaje("msj_error","Grupo ya fue registrado");
                }            
            });
        }else {
            data = $("form").serialize();
            $.post(RUTA + 'grupo/modifyGroup' ,data,function(data,textStatus,jqXHR){
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

        $("form")[0].reset();

        return false;
    });

    //busqueda
    $("#searchreg").on("click", function (e) {
        e.preventDefault();
        
        $.post(RUTA + 'grupo/listGroups',function(data,textStatus,jqXHR){
            $("table tbody")
            .empty()
            .append(data);

            $("#modalSeek").fadeIn();
        })

        return false;
    });

    //busqueda en la tabla
    $("#inputSearch").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $(".tableSeek tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    //cerrar el modal de busqueda
    $("#closeModal").on("click", function (e) {
        e.preventDefault()

        $("#modalSeek").fadeOut();
        
        return false;
    });

    $("body").on("click","table tr a", function (e) {
        e.preventDefault();

        if ($(this).data("accion") == "modify") {
            $("#indice").val($(this).attr("href"));
            $("#codigo").val($(this).parent().parent().children('td:eq(0)').text());
            $("#descripcion").val($(this).parent().parent().children('td:eq(1)').text());
            $("#editreg").removeClass('desactivado');
        }else {
            console.log(eliminar);
        }
        
        $("#modalSeek").fadeOut();
        
        return false;
    });
})
$(function(){
    activar_opcion();

    $("#newreg, #editreg, #editreg, #savereg, #cancelreg, #deletereg, #exportexcel").addClass("oculto");

    $("#searchreg").on("click", function (e) {
        e.preventDefault();

        $.post(RUTA + 'personal/listAll',function(data,textStatus,jqXHR){
            $("#tableSeek tbody")
            .empty()
            .append(data);
        })

        $("#modalSeek").fadeIn();

        return false;
    });

    $("#closeModal").on("click", function (e) {
        e.preventDefault()
        $("#modalSeek").fadeOut();
        return false;
    });

     //filtardo cuando se escribe en la tabla de busqueda
     $("#inputSearch").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#tableSeek tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    //selecciona item de la lista
    $("#tableSeek").on("click","tr", function (e) {
        e.preventDefault();

        $.post(RUTA + "personal/listById", {idx:$(this).data("idx")},
            function (data, textStatus, jqXHR) {
                $("#codigo_personal").val(data.internal);
                $("#nro_documento").val(data.dni);
                $("#estado").val(data.estado);
                $("#apellidos").val(data.apellidos);
                $("#nombres").val(data.nombres);
                $("#centro_costos").val(data.dcostos);
                $("#sede").val(data.dsede);
                $("#correo").val(data.correo);
            },
            "json"
        );

        $("#editreg, #deletereg").removeClass("desactivado");
        $("#modalSeek").fadeOut();
        
        return false;
    });
})
$(function(){
    abrirVentanaEspera();

    $(document).ready(function(){
        activar_opcion();

        cerrarVentanaEspera();
    });

    $("#tabla_ingresos tbody").on("click","a", function (e) {
        e.preventDefault();

        abrirVentanaEspera();

        $.post(RUTA+"cargoplan/detalleId", {id:$(this).attr("href")},
            function (data, textStatus, jqXHR) {
                $("#modalProcess").fadeIn();
            },
            "json"
        );
        
        return false;
    });

    $("#btnCerrarDetalle").click(function (e) { 
        e.preventDefault();
        
        $("#modalProcess").fadeOut();
        cerrarVentanaEspera();
        
        return false;
    });
})
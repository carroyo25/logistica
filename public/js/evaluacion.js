$(function(){
    abrirVentanaEspera();

    $(document).ready(function(){
        activar_opcion();

        cerrarVentanaEspera();
    });

    $("#tabla_ingresos tbody").on("click","a", function (e) {
        e.preventDefault();

        $("#ruc").val($(this).data("ruc"));
        $("#nombre").val($(this).parent().parent().parent().find('td').eq(1).text());
        $("#pedido").val($(this).parent().parent().parent().find('td').eq(2).text());
        $("#orden").val($(this).parent().parent().parent().find('td').eq(3).text());
        $("#tipo").val($(this).data("tipo"));
        $("#participa").val($(this).parent().parent().parent().find('td').eq(4).text())
        $("#entrega").val($(this).parent().parent().parent().find('td').eq(5).text())
        $("#calidad").val($(this).parent().parent().parent().find('td').eq(6).text())
        $("#almacen").val($(this).parent().parent().parent().find('td').eq(7).text())
        $("#puntuacion").text($(this).parent().parent().parent().find('td').eq(8).text())
        
        abrirVentanaEspera();
        $.post(RUTA+"evaluacion/calificacion", {id:$(this).attr("href"),enti:$("#ruc").val()},
            function (data, textStatus, jqXHR) {
                cerrarVentanaEspera();
                $("#modalProcess").fadeIn();

                $("#atenciones").val(data.atenciones);
                $("#puntaje_total").val(data.puntaje);
            },
            "json"
        );
        return false;
    });

    $("#btnCerrarDetalle").click(function (e) { 
        e.preventDefault();
        
        $("#modalProcess").fadeOut();

        return false;
    });
})
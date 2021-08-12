$(function(){
    var origen = 0;

    $("#btnEnviar").on("click", function (e) {
        e.preventDefault();

        return false;
    });

    $("#detalle_pedido tbody").on("keypress",".precio", function (e) {

        let cant = $(this).parent().parent().find("td").eq(3).text();
        let precio = $(this).val();
        let total = (parseFloat(cant) * parseFloat(precio));

        let suma = 0;
        let igv = parseFloat($('input:radio[name=igv]:checked').val());

        if(e.which == 13) {
            $(this).parent().parent().find("td").eq(5).text(total.toFixed(2));

            //obtiene la suma de la cotizacion
            $('#detalle_pedido tbody > tr').each(function(){ //filas con clase 'dato', especifica una clase, asi no tomas el nombre de las columnas
                suma += parseFloat($(this).find('td').eq(5).text()||0,10) //numero de la celda 3
            })

            if (suma > 0) {
                $("#subtotal").val(suma.toFixed(2));
                $("#igv").val((suma*igv).toFixed(2));
                $("#total").val((suma*(1+igv)).toFixed(2));
            }
        }

    });

    $("#detalle_pedido tbody").on("change",".fechaentrega",function(e){
        let entrega = new Date($(this).val()).getTime();
        let actual  = new Date($("#fechaDoc").val()).getTime();

        let diff = (entrega - actual)/(1000*60*60*24);

        if (diff < 1 ) {
            mostrarMensaje("msj_error","Verifique la fecha de entrega");
            return false;
        }
    })

    $("#detalle_pedido tbody").on("click","a", function (e) {
        e.preventDefault();

        $("#cotizacion").trigger("click");

        return false;
    });

    $("#uploadfile").change(function (e) { 
        e.preventDefault();
        
        $("#adjuntos").trigger("submit");

        return false;
    });

    $("#adjuntos").on("submit", function (e) {
        e.preventDefault();

        $.ajax({
            // URL to move the uploaded image file to server
            url:'uploadToServer.php',
            // Request type
            type: "POST", 
            // To send the full form data
            data: new FormData( this ),
            contentType:false,      
            processData:false,     
            // UI response after the file upload  
            success: function(data)
            {
                console.log(data);
            }
        });
        return false;
    });

    $("#btnAdjuntar").on("click", function (e) {
        e.preventDefault();

        $("#uploadfile").trigger("click");

        return false;
    });
})
$(function(){
    //Detectar el dispositivo
    var isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function() {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };

    if (isMobile.Android())
    {
        device = "Android";
    }
    else if (isMobile.BlackBerry())
    {
        device = "BlackBerry";
    }
    else if (isMobile.iOS())
    {
        device = "Iphone";
    }
    else if (isMobile.Opera())
    {
        device = "Opera";
    }
    else if (isMobile.Windows())
    {
        device = "IEMobile";
    }
    else
    {
        device = "Default";
    } 

    $("#btnLogin").on("click", function (e) {
        e.preventDefault();

        if($("#user").val().length <= 0){
            mostrarMensaje("msj_error","Escriba su usuario");
            return false;
        }else if($("#pass").val().length <= 0){
            mostrarMensaje("msj_error","Escriba su clave");
            return false;
        }

        $("#formLogin").trigger("submit");
        return false;
    });

    $("#formLogin").submit(function (e) { 
        e.preventDefault();
 
        var str = $(this).serialize();

        $.post(RUTA+"main/loginUser", str,
            function (data, textStatus, jqXHR) {
                if(data.estado == -1 )
                    mostrarMensaje("msj_error","Usuario o clave incorrectos");
                else if(data.estado == 0) 
                    mostrarMensaje("msj_info","Usuario no habilitado");
                else if(data.estado == 1) {
                    window.location = RUTA + "panel";   
                }
            },
            "json"
        );

        return false;
    });

})
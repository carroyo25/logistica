$(function(){
    $(".menu_horizontal a").on("click", function () {
        $("li").removeClass("selected");
        $(this).parent().addClass("selected");

        var activarForm = $(this).data("index");
        
        $(".datos").hide();

        switch (activarForm) {
            case 1:
                $("#form1").fadeIn();
                $(".banner p").text("Nombre: ");
                break
            case 2:
                $("#form2").fadeIn();
                $(".banner p").text("Nombre : TECLADO MULTIMEDIA USB");
                break;
            case 3:
                $("#form3").fadeIn();
                $(".banner p").text("Nombre : TECLADO MULTIMEDIA USB");
                break;
            case 4:
                $("#form4").fadeIn();
                $(".banner p").text("Nombre : TECLADO MULTIMEDIA USB");
                break;
            case 5:
                $("#form5").fadeIn();
                $(".banner p").text("Nombre : TECLADO MULTIMEDIA USB");
                break;
            case 6:
                $("#form6").fadeIn();
                $(".banner p").text("Nombre : TECLADO MULTIMEDIA USB");
                break    
        }
    });
})
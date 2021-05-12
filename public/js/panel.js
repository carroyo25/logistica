$(function(){
	var open = "";
	var menuOpen = false;

	$(".avatar").css("opacity","1");

	$(".foto").on("click","> a", function (e) {
		e.preventDefault();

		$(".menuCabecera").slideToggle();

		return false;
	});

	$(".menu-btn").on("click", function () {
		if (!menuOpen){
			$(this).addClass('open');
			$(".acordeon").css("left","-240px");
			$(".workData, .workOneForm").css("margin-left","85px");
			menuOpen = true;
		}else {
			$(this).removeClass('open');
			$(".acordeon").css("left","0");
			$(".workData, .workOneForm").css("margin-left","17%");
			menuOpen = false;
		}
	});

	$(".accordeon").on("click",".link", function (e) {
		e.preventDefault();

		if (open != $(this).text() ) {
			$(this).next(".submenu").slideToggle();
			$(this).children(".fa-chevron-down").css("transform","rotate(180deg)");
			open = $(this).text()
		}else{
			$(this).next(".submenu").slideToggle();
			$(this).children(".fa-chevron-down").css("transform","rotate(0)");
		}

		return false;
	});
})


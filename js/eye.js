function oko(selekt) {
	switch(selekt) {
		case 0:
			if($('input:eq(1)').attr("type") == "password") {
				$('input:eq(1)').attr({"type":"text"});
				$('i:eq(1)').removeClass("fa-eye-slash");
				$('i:eq(1)').addClass("fa-eye");
			} else {$('input:eq(1)').attr({"type":"password"});
				$('i:eq(1)').removeClass("fa-eye");
				$('i:eq(1)').addClass("fa-eye-slash");}	
			break;
		case 1:
				if($('input:eq(2)').attr("type") == "password") {
				$('input:eq(2)').attr({"type":"text"});
				$('i:eq(2)').removeClass("fa-eye-slash");
				$('i:eq(2)').addClass("fa-eye");
			} else {$('input:eq(2)').attr({"type":"password"});
				$('i:eq(2)').removeClass("fa-eye");
				$('i:eq(2)').addClass("fa-eye-slash");}	
			break;
		case 2:
			if($('input:eq(5)').attr("type") == "password") {
				$('input:eq(5)').attr({"type":"text"});
				$('i:eq(3)').removeClass("fa-eye-slash");
				$('i:eq(3)').addClass("fa-eye");
			} else {$('input:eq(5)').attr({"type":"password"});
				$('i:eq(3)').removeClass("fa-eye");
				$('i:eq(3)').addClass("fa-eye-slash");}	
			break;
	}
}
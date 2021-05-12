$(document).ready(function() {
				$("#registration").submit(function(event) {
					let valid = 1;
					$("#reguserfeed").hide();
					$("#regpassfeed").hide();
					$("#regrepfeed").hide();
					$("#reguser").removeClass("is-invalid");
					$("#regpass").removeClass("is-invalid");
					$("#regrep").removeClass("is-invalid");
  					if($("#regpass").val() != $("#regrep").val()) {
  						$("#regrep").addClass("is-invalid");
  						$("#regrepfeed").show().text("Your passwords do not match.");
  						valid = 0;
  					}
  					if($("#regpass").val().length < 7) {
  						$("#regpass").addClass("is-invalid");
  						$("#regpassfeed").show().text("Your password has to be at least 7 characters.");
  						valid = 0;
  					}
  					if(($("#reguser").val().indexOf("@") >= 0) || $("#reguser").val().indexOf("<") >= 0 || $("#reguser").val().indexOf(">") >= 0 || $("#reguser").val().indexOf("'") >= 0 || $("#reguser").val().indexOf("#") >= 0 || $("#reguser").val().indexOf("/") >= 0 || $("#reguser").val().indexOf("\"") >= 0 || $("#reguser").val().indexOf(".") >= 0 || $("#reguser").val().indexOf(";") >= 0 || $("#reguser").val().indexOf("&") >= 0) {
  						$("#reguser").addClass("is-invalid");
  						$("#reguserfeed").show().text("Your username must not contain any special characters.");
  						valid = 0;
  					}
            if($("#reguser").val().length > 20) {
              $("#reguser").addClass("is-invalid");
              $("#reguserfeed").show().text("Your username cannot be longer than 20 characters.");
              valid = 0;
            }
  					if($("#reguser").val() == "") {
  						$("#reguser").addClass("is-invalid");
  						$("#reguserfeed").show().text("Please choose a username.");
  						valid = 0;
  					}
  					if($("#regpass").val() == "") {
  						$("#regpass").addClass("is-invalid");
  						$("#regpassfeed").show().text("Please choose a password.");
  						valid = 0;
  					}
  					if($("#regrep").val() == "") {
  						$("#regrep").addClass("is-invalid");
  						$("#regrepfeed").show().text("Please repeat your chosen password.");
  						valid = 0;
  					}
  					if(valid == 0) {
  						event.preventDefault();
  					}
				});
        $("#login").submit(function(event) {
            let valid = 1;
            $("#loguserfeed").hide();
            $("#logpassfeed").hide();
            $("#loguser").removeClass("is-invalid");
            $("#logpass").removeClass("is-invalid");

            if($("#loguser").val() == "") {
                $("#loguser").addClass("is-invalid");
                $("#loguserfeed").show().text("Please enter a username.");
                valid = 0;
              }
              if($("#logpass").val() == "") {
                $("#logpass").addClass("is-invalid");
                $("#logpassfeed").show().text("Please enter a password.");
                valid = 0;
              }

            if(valid == 0) {
              event.preventDefault();
            }
          });
});
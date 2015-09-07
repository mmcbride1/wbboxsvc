//* activation.js *//

$(document).ready(function() {

   $("#opt").click(function() {

      var bool = ($(this).val() == "deactivate");

      var out = (bool) ? "deactivate account?" : "activate account?";

      if(confirm(out) == true) {

         $.ajax({

            url: "includes/activation.php"

         });

      }

      else {return false;}

      if (bool) {

         $('#currentstate').html("YOUR ACCOUNT IS CURRENTLY INACTIVE");

         $(this).val("activate");

      }

      else if ($(this).val() == "activate") {

         $('#currentstate').html("YOUR ACCOUNT IS CURRENTLY ACTIVE");

         $(this).val("deactivate");

      }

   });

});
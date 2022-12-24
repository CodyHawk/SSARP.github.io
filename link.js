$(document).ready(function() {

   //On pressing a key on "Search box" in "search.php" file. This function will be called.

   $("#codeInput").keyup(function() {

       //Assigning search box value to javascript variable named as "name".

       var name = $('#codeInput').val();

       var url_string = window.location.href;
       var url = new URL(url_string);
       var id = url.searchParams.get("id");

       //Validating, if "name" is empty.

       if (name == "") {

           //Assigning empty value to "display" div in "search.php" file.


       }

       //If name is not empty.

       else {

           //AJAX is called.

           $.ajax({

               //AJAX type is "Post".

               type: "POST",

               //Data will be sent to "ajax.php".

               url: "linkAjax.php",

               //Data, that will be sent to "ajax.php".

               data: {

                   //Assigning value of "name" into "search" variable.

                   code: name,
                   id: id

               },

               //If result found, this funtion will be called.

               success: function(output) {
    console.log(output);
    output = output.split( ',' );
    if (output[0] === 'Success') {
$("#code").fadeOut(1100);
setTimeout(
  function() 
  {
$("#Success").show();
  }, 1000);
document.getElementById("name").innerHTML = output[1];
document.getElementById("image").src = output[2];
$("#code").fadeOut(1100);
setTimeout(
  function() 
  {
window.location.replace("dashboard.php");
  }, 3500);

               }
}

           });

       }

   });

});
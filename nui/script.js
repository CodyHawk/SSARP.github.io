$(function() {
    window.addEventListener('message', function(event) {
        if (event.data.type == "enableui") {
            document.body.style.display = event.data.enable ? "block" : "none";
			console.log('enabled');
		} else if (event.data.type == "uiChange") {
           $('.page').hide();
		   $(event.data.page).show();
		   console.log($(event.data.page).html());
        }
    });
});
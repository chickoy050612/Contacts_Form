$(document).ready(
	function(){
		$('form[name="addForm"]').submit(
			function(event){
				process_submit(event);
			}
		);
		$('form input[type="submit"]').click(
			function(){
				$('input[type="submit"]', $(this).parents('form'))
								.removeAttr("clicked");
				$(this).attr("clicked", "true");
			}
		);
	}
);

function process_submit(event){
	var but = $('input[type="submit"][clicked="true"]').val();
	if (but == "Cancel"){
		if (!confirm("Are you sure you want to cancel adding this contact?")){
			event.preventDefault();
			return false;
		} 
	}
	else if (but == "Delete"){
		
		if (!confirm("Are you sure you want to delete?")){
			event.preventDefault();
			return false;
		} 

	}  
}

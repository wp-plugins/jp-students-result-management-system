/*
	Author: S.k.joy
	Author URI: http://skjoy.info
*/

jQuery(document).ready(function($){
	$('#result-form').submit(function(e){
		//Execute when submit
		e.preventDefault();
		
		//Add necessary veriable
		
		var exam_reg = $('#exam-reg').val();
		
		//Add Jquery ajax method
		
		$.ajax({
			type: 'POST',
			url:  '../wp-admin/admin-ajax.php',
			data: {
				action: 'jsrms_student_result_view',
				examroll: exam_reg
			},
			
			// If Success it will be execute
			
			success: function(data){
				var checkext = $('.result-container').find('.student-result-container').length;
				
				if(checkext) {
					$('.student-result-container').html(data);
				} else {
					$('<div/>', {
					
					class: 'student-result-container',
					html: data
						
					}).insertAfter('.result');
				
				}
				
				
			},
			
			// If error it will be execute
			
			error: function(XMLHttpRequest, textStatus, errorTrown){
				alert(errorTrown);
			},
			
			// Add loader
			
			beforeSend: function(){
				   $('.loader').show()
			},
			complete: function(){
				   $('.loader').hide();
			}
  
		});
	});
});
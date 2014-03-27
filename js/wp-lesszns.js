(function ($) {
    $(function () {
    
    	if(1 == $('#complete-container').length) {
    		$('#complete-container .reset').click(function(){
        		
        		var sArticleId, iPostId;
 				sArticleId = $("article").attr('id');
				iPostId = parseInt(sArticleId.split('-')[1]);
				
				$.post(ajaxurl, { 
 					action:     'complete_questionnaire',
 					post_id:    iPostId,
 					reset:  true
 
				}, function (response) {
					console.log(response);
					if ('ok' == response) { 
						
                        $('#questionnaire-container #thisquestion .mark.complete').remove();
                        $('#questionnaire-container #thisquestion input').removeClass('bad').removeClass('good').prop('disabled', false);
                    	$('#questionnaire-container #questionnaire').show('fast');
                    	$('#questionnaire-container button.start').hide();
                    	$('#questionnaire-container').fadeIn();
                        $('#complete-container .message').text('Fill the test to complete this lesson');                    	
                        $('#complete-container').fadeOut();
		
                    }
				});
        	}) 
    	}
 
 		if(1 === $('#questionnaire-container').length) {
        	
        	total = $('#questionnaire-container input').length;
        	sCompleted = 0;
        	
        	$('#questionnaire-container button.start').click(function(){
        		$('#questionnaire-container #questionnaire').fadeIn('fast');
        		$(this).hide();
        	}) 
        	
        	// Hide when clicking outside:
        	$('body').click(function(){
				hidequestionnaire();
        	})
        	$('#questionnaire-container #questionnaire, #questionnaire-container button.start').click(function(event){
   				 event.stopPropagation();
			});
			
			// Hide when clicking 'esc'
			$( document ).on( 'keydown', function ( e ) {
				if ( e.keyCode === 27 ) { // ESC
					hidequestionnaire();
				}
			});
        	
        	// We use the change attribute so that the event handler fires
			// whenever the checkbox or its associated label are clicked.
			$('#questionnaire-container input').change(function (evt) {
 				
				var sArticleId, iPostId;
 				sArticleId = $("article").attr('id');
				iPostId = parseInt(sArticleId.split('-')[1]);

 				sAnswer = $(this).val();
 				sQuestionID = $(this).attr('id');
				sQuestion = parseInt(sQuestionID.split('-')[1]);
				
				

				$.post(ajaxurl, { 
 					action:     'complete_questionnaire',
					post_id:    iPostId,
					sAnswer: 	sAnswer,
					sQuestion: 	sQuestion,
					sCompleted: sCompleted,
					sTotal:		total
 
				}, function (response) {
					console.log(response);
					if (true == response) { 
                        $('#questionnaire-container #thisquestion-'+sQuestion).append('<div class="mark completed"></div>');
                        $('#questionnaire-container #thisquestion-'+sQuestion+' input').removeClass('bad').addClass('good').prop('disabled', true);
                        sCompleted++;
                     	$('#questionnaire-container #thisquestion-'+sQuestion).children('.additional-info').text('');
                     	$('#questionnaire-container #thisquestion-'+sQuestion).next().show();
                    } else if (false == response) {
						$('#questionnaire-container #thisquestion-'+sQuestion+' input').addClass('bad');
                    } else if ('complete' == response){
                        alert("There was an error marking this post as read. Please try again."); 
                    } // end if/else
                    
                    console.log(sCompleted);
                    console.log(total);
                    
                    if ( sCompleted == total) {
                    	$('#questionnaire-container #questionnaire').fadeOut('fast');
                    	level = $('#complete-container').attr('data-level');
                        $('#complete-container .message').html('Lesson completed!!</br></br><a class="button next lesson" href="/level/'+level+'/" >Continue</a>');    	
                        $('#complete-container').fadeIn();
                    } else {
                    }
				});
				
			});
        
        } // end if
        
        function hidequestionnaire(){
        	if ($('#questionnaire-container #questionnaire').is(':visible')){
				$('#questionnaire-container #questionnaire').fadeOut('fast');
				$('#questionnaire-container button.start').text('Continue your test').fadeIn();
			}
        }
 
    });
}(jQuery));
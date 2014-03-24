$(document).ready(function(){
	$('#tweet-form').validation({
		required: [
			{
				name: 'tweet',
				validate: function($el) {
					return $el.val().length > 0 && $el.val().length <= 140;
				}
			},
			{
				name: 'timezone',
				validate: function($el) {
					return $el.val() != '';
				}
			}
		],
		fail: function() {
			$('#submit-danger').addClass('active').html('Please complete the form!')
		}
	});

	$('#signin-display').click(function(){
		$('#signin').submit();
	});
});
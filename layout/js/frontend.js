$(function () {
	// trigger The selectBoxIt
	$('select').selectBoxIt({
		autoWidth: false
	});

	//Tagging 
	var my_custom_options = {
		"no-duplicate": true,
		"no-duplicate-callback": window.alert,
		"no-duplicate-text": "Duplicate tags",
		"type-zone-class": "type-zone",
		"tag-box-class": "tagging",
		"forbidden-chars": [",", ".", "_", "?"]
	};	
	$("#tagBox").tagging( my_custom_options);	

	//Toggeling Between SingUp & Login
	$('.login-page h1 span').click(function () {
		$(this).addClass('selected').siblings().removeClass('selected');

		$('.login-page form').hide();

		$('.' + $(this).data('class')).fadeIn(100);

	});


	// placeHolder toggeling
	$('[placeholder]').focus(function (){

		$(this).attr('data-text', $(this).attr('placeholder'));

		$(this).attr('placeholder', '');
	}).blur(function () {

		$(this).attr('placeholder', $(this).attr('data-text'));

	});
	// add astrisk to required feilds
	$('input').each(function(){

		if ($(this).attr('required') === 'required'){

			$(this).after('<span class="astrisk">*</span>');

		}
	});

	// Remove The Error And Sucses Message After X  Time :)
	$('.messages').delay(9000).fadeOut(500);
	
	//confirmation On Deleting Member
	$('.confirm').click(function(){
		return confirm('Are You Sure ?');
	});


	$('.live').keyup(function () {

		$($(this).data('class')).text($(this).val());
	});	

	$(".toggel-cat").click(function () {
		$(".sub-cats").fadeToggle(100);
	});
			
});
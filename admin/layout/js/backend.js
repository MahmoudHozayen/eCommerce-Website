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
	// make password field test Field
	var passwordInput = $('.password')

	$('.show-pass').hover(function (){

		passwordInput.attr('type', 'text');
	}, function () {
		
		passwordInput.attr('type', 'password');
	});
	//confirmation On Deleting Member
	$('.confirm').click(function(){
		return confirm('Are You Sure ?');
	});
	//Categories View Option
	$('.cat h3').click(function () {
		$(this).next(".full-view").fadeToggle(150);
	});

	$('.categories .option span').click(function () {
		$(this).addClass('active').siblings('span').removeClass('active');

		if ($(this).data('view') === 'full') {
			$(".cat .full-view").fadeIn(150);
		} else {
			$(".cat .full-view").fadeOut(150);
		}
	});

	//Show Delete btn On Hove Sub Categorie

	$('.child-cat').hover(function(){
		$(this).find('.sub-delete').fadeIn();
	}, function () {
		$(this).find('.sub-delete').fadeOut();
	});

});

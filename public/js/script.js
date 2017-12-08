var showAjaxSuccessMessage=function(msg,appendMsg){
	$('.flash').hide();
	$('#flashSuccess').html(msg);
	$('#flashSuccess').fadeIn(1000);
};
var showAjaxErrorMessage=function(msg,appendMsg){
	$('.flash').hide();
	$('#flashError').html(msg);
	$('#flashError').fadeIn(1000);
};
var showAjaxWarningMessage=function(msg,appendMsg){
	$('.flash').hide();
	$('#flashWarning').html(msg);
	$('#flashWarning').fadeIn(1000);
};
var showAjaxInfoMessage=function(msg,appendMsg){
	$('.flash').hide();
	$('#flashInfo').html(msg);
	$('#flashInfo').fadeIn(1000);
};
var loading=function(){
	$('*').css('cursor','wait');
	$('.btn').addClass('link-disable');
}
var stopLoading=function(){
	$('*').css('cursor','auto');
	$('.btn').removeClass('link-disable');
}
var redirectTo=function(url){
	stopLoading();
	window.location.replace(url);
}
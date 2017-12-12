$('#flashMessage').ready(function(){
	setTimeout(function() {
        $('#flashMessage').slideUp('fast');
    }, 10000);
});
var showMessage=function(msg,type){
	$('#flashMessage').hide();
	$('#flashMessage').addClass(type);
	$('#flashMessage').html(msg);
	$('#flashMessage').slideDown('fast');
	setTimeout(function() {
        $('#flashMessage').slideUp('fast');
    }, 10000);
}
var showAjaxSuccessMessage=function(msg){
	showMessage(msg,'alert-success');
};
var showAjaxErrorMessage=function(msg){
	showMessage(msg,'alert-danger');
};
var showAjaxWarningMessage=function(msg){
	showMessage(msg,'alert-warning');
};
var showAjaxInfoMessage=function(msg){
	showMessage(msg,'alert-info');
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
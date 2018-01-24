var savePageConfig=function(){
	loading();
	var id=$('#pageConfigId').val();
	var pageUrl=$('#pageConfigUrl').val();
	var url="/main/savePageConfig";
	var title=$("#pageTitle").val();
	var description=$("#pageDescription").val();
	var data={
	id:id,
	pageUrl:pageUrl,
	title:title,
	description:description
	}
	var success=function(response){
		var status=response.status;
		var errors=response.errors;
		var msg=response.msg;
		$('#pageTitleError').html('');
		$("#pageDescriptionError").html('');
		if(errors){
			$('#pageTitleError').html(errors['título da página']);
			$('#pageTitle').focus();
			$("#pageDescriptionError").html(errors['descrição da página']);
			$('#pageDescription').focus();
		}
		if(status){
			showAjaxSuccessMessage(msg);
		}else{
			showAjaxErrorMessage(msg);
		}
		stopLoading();
	};
	var fail=function(){
		showAjaxErrorMessage('Não foi possível salvar o título e a descrição da página.');
		stopLoading();
	};
	$.ajax({
	type:"post",
	dataType:"json",
	url:url,
	data:data,
	success:success,
	error:fail
	});
}

var slugify=function(){
	loading();
	var title=$('#title').val();
	var url="/main/slugify?title="+title;
	var success=function(response){
		$('#slug').val(response.slug);
		stopLoading();
	};
	var fail=function(){
		showAjaxErrorMessage('Não foi possível gerar o slug.');
		stopLoading();
	};
	$.ajax({
	type:"get",
	dataType:"json",
	url:url,
	success:success,
	error:fail
	});
}
$('#flashMessage').ready(function(){
	setTimeout(function() {
        $('#flashMessage').slideUp('fast');
    }, 10000);
});
var clearMessage = function() {
	$('#flashMessage').removeClass('alert-danger');
	$('#flashMessage').removeClass('alert-success');
	$('#flashMessage').removeClass('alert-info');
	$('#flashMessage').removeClass('alert-warning');
}
var showMessage=function(msg,type){
	clearMessage();
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
$(document).ready(function(){
	$(".phone").inputmask({mask: ["(99) 9999-9999","(99) 99999-9999"]});
	$(".cep").inputmask({mask: ["99999-999"]});
	$(".email").inputmask({ alias: "email"});
	$('[data-toggle="tooltip"]').tooltip({
		html:true
	});
	function limpa_formulário_cep(){
		$("#street").val("");
		$("#neighborhood").val("");
		$("#city").val("");
		$("#state").val("");
	}
	$("#cep").blur(function(){
		var cep=$(this).val().replace(/\D/g,'');
		if(cep!=""){
			var validacep=/^[0-9]{8}$/;
			if(validacep.test(cep)){
				$("#street").val("...");
				$("#neighborhood").val("...");
				$("#city").val("...");
				$.getJSON("//viacep.com.br/ws/"+cep+"/json/?callback=?",function(dados){
					if(!("erro" in dados)){
						$("#street").val(dados.logradouro);
						$("#neighborhood").val(dados.bairro);
						$("#city").val(dados.localidade);
						$("#state").val(dados.uf);
						$("#number").focus();
					}else{
						limpa_formulário_cep();
						showAjaxErrorMessage("CEP não encontrado.")
					}
				});
			}else{
				limpa_formulário_cep();
				showAjaxErrorMessage("CEP inválido.")
			}
		}else{
			limpa_formulário_cep();
		}
	});
});

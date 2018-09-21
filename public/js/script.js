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

$(function(){
	$('#longTextModal').on('show.bs.modal', function (event) {
		$('#longTextModalBody').html('');
		var button = $(event.relatedTarget);
		var url = button.data('url');
		var title = button.data('title');
		var modal = $(this);
		modal.find('.modal-title').html(title);
		genericAjax(url, 'get', setModalText);
		return;
	});
	$('#confirmationModal').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var url = button.data('url');
		var method = button.data('method');
		var title = button.data('title');
		var modal = $(this);
		modal.find('.modal-title').text(title);
		if(method){
			modal.find("#confirmationModalBtn").prop('href','javascript:'+method);
		}else if(url){
			modal.find("#confirmationModalBtn").prop('href',basePath+url);
		}
		return;
	});
	$('#uploadModal').on('show.bs.modal', function (event) {
		$('#formUploadFile').show();
		$('#uploadModalLoading').hide();
		var button = $(event.relatedTarget);
		var title = button.data('title');
		var method = button.data('method');
		var userId = button.data('user');
		var subprojectId = button.data('subproject');
		var version = button.data('version');
		var albumId = button.data('album');
		var parentDocumentId = button.data('parent-doc');
		var multiple = button.data('multiple');
		if(multiple){
			$('#inputFile').prop("multiple", true);
		}else{
			$('#inputFile').prop("multiple", false);
		}
		if(parentDocumentId){
			version++;
		}
		$("input[name='fileVersion']").val(version);
		$("input[name='subprojectId']").val(subprojectId);
		$("input[name='userId']").val(userId);
		$("input[name='parentDocumentId']").val(parentDocumentId);
		$("input[name='albumId']").val(albumId);
		var modal = $(this);
		modal.find('#fileName').html("");
		modal.find('#inputFile').val('');
		modal.find('.modal-title').html(title);
		modal.find('#uploadModalBtn').prop('href', "javascript:"+method+'()');
		return;
	});
	$('#genericModal').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var url = button.data('url');
		var size = button.data('size');
		var title = button.data('title');
		var method = button.data('method');
		var id = button.data('id');
		
		var hasButton = button.data('button');
		var buttonHtml='';
		if(hasButton){
			var buttonTitle = button.data('button-title');
			var buttonMethod = button.data('button-method');
			buttonHtml="<a id='genericModalBtn' class='btn btn-primary' href='javascript:"+buttonMethod+"'>"+buttonTitle+"</a>";
		}
		var modal = $(this);
		modal.find('.modal-title').html('');
		modal.find('.modal-dialog').removeClass('modal-lg');
		modal.find('.modal-dialog').removeClass('modal-sm');
		modal.find('#genericModalBtn').remove();
		
		modal.find('.modal-title').html(title);
		modal.find('.modal-dialog').addClass('modal-'+size);
		modal.find('.modal-footer').append(buttonHtml);
		if(id){
			$("#genericModalId").val(id);
		}
		if(url){
			url+='?id='+id;
			var successMethod = button.data('success-method');
			var errorMethod = button.data('error-method');
			genericAjax(basePath+url, 'get', window[successMethod], window[errorMethod]);
		}else if(method){
			runMethod(method);
			return;
		}
	});
	$('#inputModal').on('show.bs.modal', function (event) {
		$("#inputModalErrorMessage").html("");
		var button = $(event.relatedTarget);
		var title = button.data('title');
		var input=button.data('input');
		var method = button.data('method');
		var id = button.data('id');
		var modal = $(this);
		modal.find('.modal-title').html(title);
		modal.find('#input').val(input);
		modal.find('#inputModalId').val(id);
		modal.find("#inputModalBtn").prop('href','javascript:'+method+'()');
		return;
	});
	$("input[type='file']").on('change', function(event){
		var inputFile=event.currentTarget;
		var files=$(inputFile)[0].files;
		var filesCount=files.length;
		$('#uploadModalErrorMessage').html('');
		if(filesCount>15){
			$('#uploadModalErrorMessage').html('Selecione no máximo 15 arquivos.');
			$(inputFile).val('');
			return;
		}
		$('#filesCountLabel').html('');
		var fileName=files[0].name;
		if(filesCount>1){
			fileName+='...';
			$('#filesCountLabel').html(filesCount+' arquivos foram selecionados.');
		}
	    $(inputFile).closest('.custom-file').find('label').html(fileName);
	    return;
	});
	$(document).on('click', 'a[data-run-method]',function() {
		var method=$(this).data('run-method');
		runMethod(method);
		return;
	});
});

var genericAjax = function(url, type, successFunction, failFunction, data=null, async=true, processData=true, contentType='application/x-www-form-urlencoded; charset=UTF-8', cache=false, dataType='json'){
	$.ajax({
		url:basePath+url,
		type:type,
		success:successFunction,
		error:failFunction,
		data:data,
		async:async,
		processData:processData,
		contentType:contentType,
		dataType:dataType,
		cache:cache
	});
};

var showGenericModalLoading=function(){
	$('#genericModalBody').html("<div class='col-md-2'></div><div class='col-md-8'><i id='genericModalLoading' class='fa fa-spinner fa-spin' style='font-size:15em'></i></div><div class='col-md-2'></div>");
}

var reload = function(){
	document.location.reload();
};

var runMethod=function(method){
	var paramIndex=method.indexOf('(');
	if(paramIndex!==-1){
		var closeParamIndex=method.indexOf(')');
		param=method.substr(paramIndex, closeParamIndex);
		param=param.replace('(','');
		param=param.replace(')','');
		var params=param.split(',');
		method=method.substr(0, paramIndex);
		var fn=window[method];
		fn.apply(this, Array.prototype.slice.call(params));
		return;
	}
	var fn=window[method];
	if (typeof fn === "function") fn();
}

$('#flashMessage').ready(function(){
	setTimeout(function() {
		$('#flashMessage').slideUp('fast');
	}, 10000);
});
var clearFlashMessage=function(){
	$('#alert').removeClass('alert-success');
	$('#alert').removeClass('alert-danger');
	$('#alert').removeClass('alert-info');
}
var showMessage=function(msg,type){
	$('#alert').show();
	clearFlashMessage();
	$('#alert').addClass(type);
	$('#alertMessage').html(msg);
	$('#alert').slideDown('fast');
	setTimeout(function() {
		$('#alert').slideUp('fast');
	}, 10000);
	$('#flashMessage').remove();
};
var showAjaxSuccessMessage=function(msg){
	showMessage(msg,'alert-success');
};
var showAjaxErrorMessage=function(msg){
	showMessage(msg,'alert-danger');
};
var showAjaxInfoMessage=function(msg){
	showMessage(msg,'alert-info');
};
var loading=function(){
	$('*').css('cursor','wait');
	$('.btn').addClass('link-disable');
};
var stopLoading=function(){
	$('*').css('cursor','auto');
	$('.btn').removeClass('link-disable');
};
var redirectTo=function(url){
	stopLoading();
	window.location.replace(url);
};
var slugify=function(input){
	var slug=$(input).val();
	genericAjax("/admin/slugify?slug="+slug, 'get', setSlugify);
};
var setSlugify = function(json){
	$('#slug').val(json.slug);
};
(function() {
	'use strict';
	window.addEventListener('load', function() {
		var forms = document.getElementsByClassName('needs-validation');
		var validation = Array.prototype.filter.call(forms, function(form) {
			form.addEventListener('submit', function(event) {
				if (form.checkValidity() === false) {
					event.preventDefault();
					event.stopPropagation();
					for (var i=0;i<editors.length;i++) {
						var editor=editors[i];
						var textAreaId=editor.element.id;
						if(!editor.getData() || editor.getData()=='<p>&nbsp;</p>'){
							$('#'+textAreaId).closest('div.form-group').find('div.invalid-feedback').show();
						}else{
							$('#'+textAreaId).closest('div.form-group').find('div.invalid-feedback').hide();
						}
					}
				}
				form.classList.add('was-validated');
			}, false);
		});
	}, false);
})();

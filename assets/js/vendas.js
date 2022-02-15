$(function(){

		var importarPlanilha = function(){
			var modal = $('#modal-importar-planilha');
			var btnModalImportar = $('.btn-modal-importar-planilha');

			btnModalImportar.bind('click',function(e){
				modal.modal('show');
				var btnImportar = modal.find('button.btn-importar');
				var formImportar = modal.find('form#form-importar-planilha');

				btnImportar.bind('click',function(e){
					formImportar.submit();
				});

			});
		}
		importarPlanilha();
});

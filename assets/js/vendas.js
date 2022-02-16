$(function(){

	var importarPlanilha = function(){
		var modal = $('#modal-importar-planilha');
		var btnModalImportar = $('.btn-modal-importar-planilha');

		btnModalImportar.bind('click',function(e){
			modal.modal('show');
			var btnImportar = modal.find('button.btn-importar');
			var formImportar = modal.find('form#form-importar-planilha');

			btnImportar.bind('click',function(e){
			     var inputArquivo = formImportar.find('input#arquivo');
             
                if(inputArquivo.val()==''){
                    return false;
                }
                formImportar.submit();
			});

		});
	}
	importarPlanilha();
    
    var tblVendas = $('#tabela-vendas').DataTable({
        'paging'        :   true,
        'lengthChange'  :   false,
        'searching'     :   false,
        'ordering'      :   true,
        'info'          :   true,
        'autoWidth'     :   false,
        'responsive'    :   true,
        'processing'    :   true,
        'serverSide'    :   true,
        'ajax'          :  BASE_URL+'index.php/api/vendas',
        'columns'       : [
            {data   :   'id', className: 'text-center'},
            {data   :   'dt_venda', className: 'text-center','render':function(data){
                
                var tempDate = new Date(data);
                return tempDate.toLocaleDateString('pt-BR', {timeZone: 'UTC'});
            }},
            {data   :   'cliente_razao_social'},
            {data   :   'descricao', className: 'text-center'},
            {data   :   'horas_trabalhadas', className: 'text-right'},
            {data   :   'valor_faturado', className: 'text-right', 'render':function(data){return Number(data).toLocaleString('pt-br', {minimumFractionDigits: 2})}},
            {data   :   'valor_custo', className: 'text-right','render':function(data){return Number(data).toLocaleString('pt-br', {minimumFractionDigits: 2})}},
            {data   :   'resultado_venda',className: 'text-right', 'render':function(data){return Number(data).toLocaleString('pt-br', {minimumFractionDigits: 2})}},
        ],
        'language': {
            'url': '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json'
        }
    }); 
});

$(function(){

    var tblIndicadores = $('#tabela-indicadores').DataTable({
        'paging'        :   true,
        'lengthChange'  :   false,
        'searching'     :   false,
        'ordering'      :   true,
        'info'          :   true,
        'autoWidth'     :   false,
        'responsive'    :   true,
        'processing'    :   true,
        'serverSide'    :   true,
        'ajax'          :  BASE_URL+'index.php/api/vendas/indicadores/servicos',
        'columns'       : [
            {data   :   'servico_descricao'},
            {data   :   'horas_trabalhadas', className: 'text-right', 'render':function(data){return Number(data).toLocaleString('pt-br', {minimumFractionDigits: 2})}},
            {data   :   'valor_faturado', className: 'text-right', 'render':function(data){return Number(data).toLocaleString('pt-br', {minimumFractionDigits: 2})}},
            {data   :   'valor_custo', className: 'text-right','render':function(data){return Number(data).toLocaleString('pt-br', {minimumFractionDigits: 2})}},
            {data   :   'resultado_venda',className: 'text-right', 'render':function(data){return Number(data).toLocaleString('pt-br', {minimumFractionDigits: 2})}},
        ],
        'language': {
            'url': '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json'
        }
    }); 
});

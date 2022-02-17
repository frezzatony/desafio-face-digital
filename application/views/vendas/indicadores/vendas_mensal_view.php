	<section class="content-header">
		<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Indicador de Vendas - Totais mensais</h1>
			</div>
			
		</div>
		</div><!-- /.container-fluid -->
	</section>
	<section class="content">
		<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
                        <table id="tabela-indicadores" class="table table-bordered table-striped">
                          <thead>
                              <tr>
                                <th class="text-center">Mês/Ano</th>
                                <th class="text-center">Horas trabalhadas</th>
                                <th class="text-center">Valor total faturado(R$)</th>
                                <th class="text-center">Custo total (R$)</th>
                                <th class="text-center">Resultado (R$)</th>
                              </tr>
                          </thead>
                          <tbody>
                          
                          </tbody>
                          <tfoot>
                              <tr>
                                <th class="text-center">Mês/Ano</th>
                                <th class="text-center">Horas trabalhadas</th>
                                <th class="text-center">Valor total faturado(R$)</th>
                                <th class="text-center">Custo total (R$)</th>
                                <th class="text-center">Resultado (R$)</th>
                              </tr>
                          </tfoot>
                        </table>

					</div>
				</div>
			</div>
		</div>
	</section>
    
    
<?php

    $this->template->set('view_javascsripts',
        array(
            '/assets/js/vendas/indicadores/vendas_mensais.js'
        )
    );
    
?>
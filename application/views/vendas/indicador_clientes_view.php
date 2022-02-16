	<section class="content-header">
		<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Indicador de Vendas - Totais por clientes</h1>
			</div>
			
		</div>
		</div><!-- /.container-fluid -->
	</section>
	<?php echo $this->session->flashdata('error'); ?>
	<section class="content">
		<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
                        <table id="tabela-vendas" class="table table-bordered table-striped">
                          <thead>
                          <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Data</th>
                            <th class="text-center">Cliente</th>
                            <th class="text-center">Serviço</th>
                            <th class="text-center">Horas consumidas</th>
                            <th class="text-center">Valor faturado(R$)</th>
                            <th class="text-center">Custo (R$)</th>
                            <th class="text-center">Resultado (R$)</th>
                          </tr>
                          </thead>
                          <tbody>
                          
                          </tbody>
                          <tfoot>
                          <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Data</th>
                            <th class="text-center">Cliente</th>
                            <th class="text-center">Serviço</th>
                            <th class="text-center">Horas consumidas</th>
                            <th class="text-center">Valor faturado(R$)</th>
                            <th class="text-center">Custo (R$)</th>
                            <th class="text-center">Resultado (R$)</th>
                          </tr>
                          </tfoot>
                        </table>

					</div>
				</div>
			</div>
		</div>
	</section>
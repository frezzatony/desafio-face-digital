	<section class="content-header">
		<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Vendas</h1>
			</div>
			<div class="col-sm-6 text-right">
				<button class="btn btn-success btn-modal-importar-planilha" >
					<i class="fa fa-upload"></i> Importar planilha
				</button>
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

					</div>
				</div>
			</div>
		</div>
	</section>


	<div class="modal fade" id="modal-importar-planilha">
		<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Importação de controle de vendas</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="/index.php/vendas/importar" id="form-importar-planilha" method="POST" enctype="multipart/form-data">
					
                    <div class="form-group">
						<label for="planilha">Selecione um arquivo para importação:</label>
						<input type="file" class="form-control" id="arquivo" name="arquivo" placeholder="Selecione um arquivo...">
					</div>
                    
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="cabecalho" name="cabecalho">
                        <label class="form-check-label" for="cabecalho">Primeira linha contém cabeçalho</label>
                    </div>
				</form>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-success btn-importar"><i class="fa fa-upload"></i> Importar </button>
			</div>
		</div>
		</div>
	</div> 

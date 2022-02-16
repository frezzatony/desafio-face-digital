	<section class="content-header">
		<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Vendas - Importação de dados</h1>
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
                        <?php
                            if($this->session->flashdata('success')){
                        ?>
                            <div class="callout callout-success">
                              <h5><?php echo $this->session->flashdata('success');?></h5>
                            </div>
                        <?php    
                            }
                        ?>
                        
                        <?php
                            if($this->session->flashdata('warning')){
                        ?>
                            <div class="callout callout-warning">
                              <h5><?php echo $this->session->flashdata('warning');?></h5>
                            </div>
                        <?php    
                            }
                        ?>
                        
                        <?php
                            if($this->session->flashdata('errors')){
                        ?>
                            <div class="callout callout-danger">
                              <h5>Vendas não importadas:</h5>
                              <?php
                                foreach($this->session->flashdata('errors') as $error){
                              ?>
                                <p>
                                    Linha: <?php echo $error['row'];?>
                                    Erro: <?php echo $error['error'];?>
                                </p>
                              <?php
                                }
                              ?>
                            </div>
                        <?php    
                            }
                        ?>
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

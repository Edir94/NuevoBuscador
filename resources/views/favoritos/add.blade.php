<div class="modal fade bs-example-modal-lg" role="dialog" id="modalAgregarFavorito" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<span class="glyphicon glyphicon-star"></span>
				<strong>MIS FAVORITOS</strong>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<div class="panel">
					<form>
						<div class="row">

							<div class="item form-group col-xs-12">
								<div class="col-xs-10">
									<label class="control-label  col-xs-5" for="name" align="center">
				                		<h4>Temas</h4>
				                	</label>
				                	<label class="control-label  col-xs-6 col-md-offset-1" for="name" align="center">
				                		<h4>Palabras Clave</h4>
				                	</label>
								</div>
								<div class="col-xs-12 col-md-offset-10">
                                	<button type="button" class="btn-add-temas btn btn-primary btn-xs" onclick="btnAgregar(this)"><span class="glyphicon glyphicon-plus"></span></button>
                            	</div>
							</div>


							<div class="item form-group col-xs-12" id="idFavorito">
								<div class=" item form-group col-xs-12 palabraClaves">
									<div id="idTema" class="col-xs-5 col-sm-5 col-md-5 div-tema">
										<input type="text" class="col-xs-12 col-sm-12 col-md-12 text-temas" name="tema0" id="tema0"  value="">
									</div>
									<div id="idPalabraClave" class="col-xs-5 col-sm-5 col-md-5 div-pc">
										<input type="text" class="col-xs-12 col-sm-12 col-md-12 text-claves" name="pClave0" id="pClave0" value="">
									</div>
									<div class="col-xs-2 col-md-1 div-eliminar">
			                            <button type="button" class="btn-del-temas btn btn-default btn-xs" onclick="btnEliminar(this)"><span class="glyphicon glyphicon-minus"></span></button>
			                        </div>
			                    </div>
							</div>

						</div>
					</form>
				</div>
			</div>
			<div class="modal-footer">
				<button id="btnAgregarFvorito" type="button" class="btn btn-primary">Agregar</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>
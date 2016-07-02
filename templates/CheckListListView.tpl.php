<?php
	$this->assign('title','arquivos | CheckLists');
	$this->assign('nav','checklists');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script("scripts/app/checklists.js").wait(function(){
		$(document).ready(function(){
			page.init();
		});
		
		// hack for IE9 which may respond inconsistently with document.ready
		setTimeout(function(){
			if (!page.isInitialized) page.init();
		},1000);
	});
</script>

<div class="container">

<h1>
	<i class="icon-th-list"></i> Check List
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Procurar..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</h1>

	<!-- underscore template for the collection -->
	<script type="text/template" id="checkListCollectionTemplate">
		<table class="collection table table-bordered table-hover">
		<thead>
			<tr>
				<th id="header_Id">Id<% if (page.orderBy == 'Id') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Vistoriaid">Vistoriaid<% if (page.orderBy == 'Vistoriaid') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_QuestaoDescricao">Questão<% if (page.orderBy == 'QuestaoDescricao') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Tipoavaliacao">Tipo Avaliação<% if (page.orderBy == 'Tipoavaliacao') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Observacao">Observação<% if (page.orderBy == 'Observacao') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('id')) %>">
				<td><%= _.escape(item.get('id') || '') %></td>
				<td><%= _.escape(item.get('vistoriaid') || '') %></td>
				<td><%= _.escape(item.get('questaoDescricao') || '') %></td>
				<td><%= _.escape(item.get('tipoavaliacao') || '') %></td>
				<td><%= _.escape(item.get('observacao') || '') %></td>
			</tr>
		<% }); %>
		</tbody>
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="checkListModelTemplate">
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div id="idInputContainer" class="control-group">
					<label class="control-label" for="id">Id</label>
					<div class="controls inline-inputs">
						<span class="input-xlarge uneditable-input" id="id"><%= _.escape(item.get('id') || '') %></span>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="vistoriaidInputContainer" class="control-group">
					<label class="control-label" for="vistoriaid">Vistoria</label>
					<div class="controls inline-inputs">
						<select id="vistoriaid" name="vistoriaid"></select>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="questaoidInputContainer" class="control-group">
					<label class="control-label" for="questaoid">Questão</label>
					<div class="controls inline-inputs">
						<select id="questaoid" name="questaoid"></select>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="tipoavaliacaoInputContainer" class="control-group">
					<label class="control-label" for="tipoavaliacao">Tipo avaliação</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="tipoavaliacao" placeholder="Tipoavaliacao" value="<%= _.escape(item.get('tipoavaliacao') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="observacaoInputContainer" class="control-group">
					<label class="control-label" for="observacao">Observação</label>
					<div class="controls inline-inputs">
						<textarea class="input-xlarge" id="observacao" rows="3"><%= _.escape(item.get('observacao') || '') %></textarea>
						<span class="help-inline"></span>
					</div>
				</div>
			</fieldset>
		</form>

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="deleteCheckListButtonContainer" class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="deleteCheckListButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Excluir CheckList</button>
						<span id="confirmDeleteCheckListContainer" class="hide">
							<button id="cancelDeleteCheckListButton" class="btn btn-mini">Cancelar</button>
							<button id="confirmDeleteCheckListButton" class="btn btn-mini btn-danger">Confirmar</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade" id="checkListDetailDialog">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3>
				<i class="icon-edit"></i> Alterar CheckList
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="checkListModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" >Cancelar</button>
			<button id="saveCheckListButton" class="btn btn-primary">Salvar </button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="checkListCollectionContainer" class="collectionContainer">
	</div>

	<p id="newButtonContainer" class="buttonContainer">
		<button id="newCheckListButton" class="btn btn-primary">Inserir CheckList</button>
	</p>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>

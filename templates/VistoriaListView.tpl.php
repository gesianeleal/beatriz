<?php
	$this->assign('title','NBR 9050/2015 | Vistorias');
	$this->assign('nav','vistorias');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script("scripts/app/vistorias.js").wait(function(){
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
	<i class="icon-th-list"></i> Vistorias
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Procurar..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</h1>

	<!-- underscore template for the collection -->
	<script type="text/template" id="vistoriaCollectionTemplate">
		<table class="collection table table-bordered table-hover">
		<thead>
			<tr>
				<th id="header_Id">Id<% if (page.orderBy == 'Id') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Data">Data<% if (page.orderBy == 'Data') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
                                <th id="header_ObraNome">Obra<% if (page.orderBy == 'Obraid') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>                                    
                                <th id="header_EngenheiroNome">Engenheiro<% if (page.orderBy == 'EngenheiroNome') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>                                    
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('id')) %>">
				<td><%= _.escape(item.get('id') || '') %></td>
				<td><%if (item.get('data')) { %><%= _date(app.parseDate(item.get('data'))).format('MMM D, YYYY') %><% } else { %>NULL<% } %></td>
				<td><%= _.escape(item.get('obraNome') || '') %></td>
				<td><%= _.escape(item.get('engenheiroNome') || '') %></td>
			</tr>
		<% }); %>
		</tbody>
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="vistoriaModelTemplate">

	</script>

        <!-- modal edit dialog -->
	<div class="modal hide fade" id="vistoriaDetailDialog">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3>
				<i class="icon-edit"></i> Visualizar Vistoria
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>                                
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="vistoriaModelContainer"></div>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="vistoriaCollectionContainer" class="collectionContainer">
	</div>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>

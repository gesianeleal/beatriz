<?php
	$this->assign('title','NBR 9050/2015 | Checklistvistorias');
	$this->assign('nav','checklistvistorias');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script("scripts/app/checklistvistorias.js").wait(function(){
		$(document).ready(function(){
			page.init();
		});
		
		// hack for IE9 which may respond inconsistently with document.ready
		setTimeout(function(){
			if (!page.isInitialized) page.init();
		},1000);
	});
</script>

<h2><i class="icon-check"></i> Select Tables</h2>

<p>Select the tables and views to include in this application.  The Singular and Plural names
are automatically detected and will be used in the names of generated classes.  You may
adjust them here.  If you prefix every column in a table consistently (ex a_id, a_name)
the Column Prefix will be removed for class properties.</p>

<p>Note that tables with no primary key or a
composite primary key are not supported.  Views are supported but depending on the contents
of the view, update operations may not work.  Views are de-selected by default.</p>

<form id="generateForm" action="generate" method="post" class="form-horizontal">

	<table class="collection table table-bordered table-striped">
	<thead>
		<tr>
			<th class="checkboxColumn"><input type="checkbox" id="selectAll" checked="checked"
				onclick="$('input.tableCheckbox').attr('checked', $('#selectAll').attr('checked')=='checked');"/></th>
			<th>Table</th>
			<th>Singular Name</th>
			<th>Plural Name</th>
			<th>Column Prefix</th>
		</tr>
	</thead>
	<tbody>
	<?php 
	
	/* these are reserved words that will conflict with phreeze */
	function is_reserved_table_name($name)
	{
		$reserved = array('criteria','phreezer','phreezable','reporter','controller','dataset');
		return in_array(strtolower($name), $reserved);
	} 

	/* these are property names that cannot be used due to conflicting with the client-side libraries */
	function is_reserved_column_name($name)
	{
		$reserved = array('url','urlroot','idattribute','attributes','isnew','changedattributes','previous','previousattributes','defaults');
		return in_array(strtolower($name), $reserved);
	} 
	?>
	
	<?php foreach ($this->dbSchema->Tables as $table) { 
	
		$invalidColumns = array();
		foreach ($table->Columns as $column) {
			if (is_reserved_column_name($column->NameWithoutPrefix) )
			{
				$invalidColumns[] = $column->Name;
			}
		} 
	?>
		<tr id="">
			<td class="checkboxColumn">
			<?php if (count($invalidColumns)>0) { ?>
				<a href="#" class="popover-icon" rel="popover" onclick="return false;"
					data-content="This table contains one or more column names that conflict with the client-side libraries.  To include this table, please rename the following column(s):<br/><br/><ul><li><?php $this->eprint( implode("</li><li>", $invalidColumns) ); ?></li></ul>"
					data-original-title="Reserved Word"><i class="icon-ban-circle">&nbsp;</i></a>
			<?php } elseif ($table->IsView) { ?>
				<input type="checkbox" class="tableCheckbox" name="table_name[]" value="<?php $this->eprint($table->Name); ?>" />
			<?php } elseif ($table->NumberOfPrimaryKeyColumns() < 1) { ?>
				<a href="#" class="popover-icon" rel="popover" onclick="return false;"
					data-content="Phreeze does not currently support tables without a primary key column"
					data-original-title="No Primary Key"><i class="icon-ban-circle">&nbsp;</i></a>
			<?php } elseif ($table->NumberOfPrimaryKeyColumns() < 1) { ?>
				<a href="#" class="popover-icon" rel="popover" onclick="return false;"
					data-content="Phreeze does not currently support tables with multiple/compound key columns"
					data-original-title="Compound Primary Key"><i class="icon-ban-circle">&nbsp;</i></a>
			<?php } else { ?>
				<input type="checkbox" class="tableCheckbox" name="table_name[]" value="<?php $this->eprint($table->Name); ?>" checked="checked" />
			<?php } ?>
			</td>
			<td class="tableNameColumn">
			
			<?php if (is_reserved_table_name($table->Name)) { ?>
				<a href="#" class="popover-icon error" rel="popover" onclick="return false;"
					data-content="This table name is a reserve word in the Phreeze framework.<br/><br/>'Model' has been appended to the end of your class name.  You can change this to something else as long as you do not use the reserved Phreeze classname as-is."
					data-original-title="Reserved Word"><i class="icon-info-sign">&nbsp;</i></a>
			<?php } elseif ($table->IsView) { ?>
				<a href="#" class="popover-icon view" rel="popover" onclick="return false;"
					data-content="Views are supported by Phreeze however only read-operations will be allowed by default.<br/><br/>Because views do not support keys or indexes, Phreeze will treat the leftmost column of the view as the primary key.  For optimal results please design your view so that the leftmost column returns a unique value for each row."
					data-original-title="View Information"><i class="icon-table">&nbsp;</i></a>
			<?php }else{ ?>
				<i class="icon-table">&nbsp;</i>
			<?php } ?>
			<?php $this->eprint($table->Name); ?></td>
			
			<?php if (is_reserved_table_name($table->Name)) { ?>
				<td><input class="objname objname-singular" type="text" id="<?php $this->eprint($table->Name); ?>_singular" name="<?php $this->eprint($table->Name); ?>_singular" value="<?php $this->eprint($this->studlycaps($table->Name)); ?>Model" /></td>
				<td><input class="objname objname-plural" type="text" id="<?php $this->eprint($table->Name); ?>_plural" name="<?php $this->eprint($table->Name); ?>_plural" value="<?php $this->eprint($this->studlycaps($table->Name)); ?>Models" /></td>
			<?php } else { ?>
				<td><input class="objname objname-singular" type="text" id="<?php $this->eprint($table->Name); ?>_singular" name="<?php $this->eprint($table->Name); ?>_singular" value="<?php $this->eprint($this->studlycaps( $table->GetObjectName() )); ?>" /></td>
				<td><input class="objname objname-plural" type="text" id="<?php $this->eprint($table->Name); ?>_plural" name="<?php $this->eprint($table->Name); ?>_plural" value="<?php $this->eprint($this->studlycaps($this->plural( $table->GetObjectName() ))); ?>" /></td>
			<?php } ?>
			<td><input type="text" class="colprefix span2" id="<?php $this->eprint($table->Name); ?>_prefix" name="<?php $this->eprint($table->Name); ?>_prefix" value="<?php $this->eprint($table->ColumnPrefix); ?>" size="15" /></td>
		</tr>
	<?php } ?>
	</tbody>
	</table>

	<p>
		<input type="hidden" name="host" id="host" value="<?php $this->eprint($this->host) ?>" />
		<input type="hidden" name="port" id="port" value="<?php $this->eprint($this->port) ?>" />
		<input type="hidden" name="type" id="type" value="<?php $this->eprint($this->type) ?>" />
		<input type="hidden" name="schema" id="schema" value="<?php $this->eprint($this->schema) ?>" />
		<input type="hidden" name="username" id="username" value="<?php $this->eprint($this->username) ?>" />
		<input type="hidden" name="password" id="password" value="<?php $this->eprint($this->password) ?>" />

		<button class="btn btn-inverse"><i class="icon-play"></i> Generate Application</button>
	</p>
</form>

<script type="text/javascript" src="scripts/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="scripts/app/analyze.js"></script>

<?php
	$this->display('_Footer.tpl.php');
?>

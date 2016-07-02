<?php
	$this->assign('title','BEATRIZ Check List');
	$this->assign('nav','check');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
  $(function() {
    $('#datetimepicker1').datetimepicker({
      language: 'pt-BR'
    });
  });
</script>

<h1>
    <i class="icon-th-list"></i> Vistoria
</h1>

<form action="analyze" method="post" class="form-horizontal">
	<fieldset class="well">
            <div id="dataInputContainer" class="control-group">
                <label class="control-label" for="data">Data</label>
                <div class="controls inline-inputs">
                    <input id="dataCheck" name="dataCheck" type="date" value="" />
                </div>
            </div>
            <div id="obraidInputContainer" class="control-group">
                <label class="control-label" for="obraid">Obra</label>
                <div class="controls inline-inputs">
                    <select id="obraid" name="obraid">                        
                        <?php foreach ($this->obras as $obra) : ?>
                                <option value="<?php $this->eprint($obra->id); ?>"><?php $this->eprint($obra->local); ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <div id="engenheiroidInputContainer" class="control-group">
                <label class="control-label" for="engenheiroid">Engenheiro</label>
                <div class="controls inline-inputs">
                    <select id="engenheiroid" name="engenheiroid">
                    <?php foreach ($this->engenheiros as $engenheiro) : ?>
                            <option value="<?php $this->eprint($engenheiro->id); ?>"><?php $this->eprint($engenheiro->nome); ?></option>
                    <?php endforeach;?>
                    </select>
                </div>
            </div>	
	</fieldset>
	<p>
	<input type="submit" class="btn btn-inverse" value="Responder Questionário" />
	</p>
</form>

<?php
	$this->display('_Footer.tpl.php');
?>
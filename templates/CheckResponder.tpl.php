<?php
	$this->assign('title','BEATRIZ Check List');
	$this->assign('nav','checkresponder');

	$this->display('_Header.tpl.php');
        
?>

<form id="generateForm" action="generate" method="post" class="form-horizontal">
    <p>
        <input type="submit" id="salvar" class="btn btn-inverse" value="Salvar" />
    </p>	
    <input type="hidden" name="obraid" id="obraid" value="<?php $this->eprint($this->obraid) ?>" />
    <input type="hidden" name="engenheiroid" id="engenheiroid" value="<?php $this->eprint($this->engenheiroid) ?>" />        
    <input type="hidden" name="data" id="data" value="<?php $this->eprint($this->data) ?>" />        
    
  <div class="panel-group">
    <div class="panel panel-default"> 
	<?php foreach ($this->grupoQuestoes as $grupoQuestao) : $first = true;?>                            
        <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" href="#<?php $this->eprint($grupoQuestao->id); ?>"><?php $this->eprint($grupoQuestao->descricao); ?></a>
            </h4>
          </div>

          <div id="<?php $this->eprint($grupoQuestao->id); ?>" class="panel-collapse collapse">
            <ul class="list-group">
                <?php foreach ($this->output as $questao) :?>
                    <?php if ($grupoQuestao->id == $questao->grupoquestaoid) { ?>
                        <?php if ($first) { $first = false; ?>
                                <table class="collection table table-bordered table-striped">
                                <thead>
                                        <tr>
                                                <th>Perguntas</th>
                                        </tr>
                                </thead>
                                <tbody>	
                        <?php } ?>

                        <tr id="gridEditavel">
                            <td class="tableNameColumn">		

                                <i class="icon-table">&nbsp;</i>
                                <?php $this->eprint($questao->descricao); ?>
                                <br>
                                <input size="20" class="input-xlarge" type="radio" id="<?php $this->eprint($questao->id); ?>_TIPO" name="<?php $this->eprint($questao->id); ?>_TIPO" value="CO" />Conforme
                                <input size="20" class="input-xlarge" type="radio" id="<?php $this->eprint($questao->id); ?>_TIPO" name="<?php $this->eprint($questao->id); ?>_TIPO" value="NC" />Não Conforme
                                <input size="20" type="radio" class="input-xlarge" id="<?php $this->eprint($questao->id); ?>_TIPO" name="<?php $this->eprint($questao->id); ?>_TIPO" value="NA" checked="true"/>Não Aplicável
                            </td>                
                        </tr>
                <?php } endforeach;?>
                </tbody>
                </table>
            </ul>
          </div>
	<?php endforeach;?>
        </div>
    </div>
</form>




<?php
	$this->display('_Footer.tpl.php');
?>
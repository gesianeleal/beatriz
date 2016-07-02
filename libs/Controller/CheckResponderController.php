<?php
/** @package Cargo::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/VistoriaCriteria.php");
require_once("Model/Vistoria.php");
require_once("Model/ChecklistvistoriaCriteria.php");
require_once("Model/Checklistvistoria.php");
require_once("Model/QuestaoCriteria.php");
require_once("Model/Questao.php");
require_once("Model/ObraCriteria.php");
require_once("Model/Obra.php");
require_once("Model/EngenheiroCriteria.php");
require_once("Model/Engenheiro.php");


class CheckResponderController extends AppBaseController
{

	/**
	 * Override here for any controller-specific functionality
	 */
	protected function Init()
	{
		parent::Init();

		// TODO: add controller-wide bootstrap code
	}
        
	public function Responder()
	{ 
            $engenheiroid = $_REQUEST['engenheiroid'];
            $obraid = $_REQUEST['obraid'];
            $data = $_REQUEST['data'];
            
            $vistoria = new Vistoria($this->Phreezer);
            $vistoria->Data = $data;
            $vistoria->Engenheiroid = $engenheiroid;
            $vistoria->Obraid = $obraid; 
            $vistoria->Save();           
            
            $criteria = new QuestaoCriteria();

            $questaoSelecionadas = $this->Phreezer->Query('Questao',$criteria);
            $rows = $questaoSelecionadas->ToObjectArray(true, $this->SimpleObjectParams());
 
	    foreach ($rows as $questao){
                $chaveReq = $questao->id . '_TIPO';   
                $checklist = new Checklistvistoria($this->Phreezer);
                $checklist->Questaoid = $questao->id;
                $checklist->Vistoriaid = $vistoria->Id;
                $checklist->Tipoavaliacao = RequestUtil::Get($chaveReq); //botão clicados
                $checklist->Save();
            }
            
            $criteria2 = new ChecklistvistoriaCriteria();
            $criteria2->Vistoriaid_Equals = $vistoria->Id;
            
            $checklistvistorias = $this->Phreezer->Query('ChecklistvistoriaReporter',$criteria2);
            $rows2 = $checklistvistorias->ToObjectArray(true,$this->SimpleObjectParams());
            
            $texto = "";
	    foreach ($rows2 as $tipo){
                if($tipo->tipoavaliacao == "CO"){
                    $texto .= round($tipo->total/count($rows)*100,2) ."% Conforme\n";
                } elseif ($tipo->tipoavaliacao == "NC") {
                    $texto .= round($tipo->total/count($rows)*100,2) ."% Não Conforme\n";                    
                } elseif ($tipo->tipoavaliacao == "NA") {
                    $texto .= round($tipo->total/count($rows)*100,2) ."% Não Aplicavel\n";
                }           
            }
            
            $obra = $this->Phreezer->Get('Obra',$obraid);
            $engenheiro = $this->Phreezer->Get('Engenheiro',$engenheiroid);
            
            echo "<h2>Salvo com sucesso</h2>\n";
            echo "<h3>Obra: " . $obra->Local . "</h3>\n";            
            echo "<h3>Engenheiro:" . $engenheiro->Nome ."</h3>\n";            
            echo "<h4>" . $texto ."</h4>\n";            
            //echo $texto;
            
            $gc = GlobalConfig::GetInstance();
	    //$gc->GetRenderEngine()->display("VistoriaListView.tpl");
            $gc->GetRenderEngine()->display("Respondido.tpl");
            //$this->RenderJSON($_REQUEST, $this->JSONPCallback());
	}
}
?>
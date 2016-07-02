<?php
/** @package Cargo::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/QuestaoCriteria.php");
require_once("Model/Questao.php");
require_once("Model/GrupoquestaoCriteria.php");
require_once("Model/Grupoquestao.php");
require_once("Model/ChecklistvistoriaCriteria.php");
require_once("Model/Checklistvistoria.php");
require_once("Model/ObraCriteria.php");
require_once("Model/Obra.php");
require_once("Model/EngenheiroCriteria.php");
require_once("Model/Engenheiro.php");

class CheckController extends AppBaseController
{

	/**
	 * Override here for any controller-specific functionality
	 */
	protected function Init()
	{
		parent::Init();

		// TODO: add controller-wide bootstrap code
	}
        	
        public function Vistoria()
	{
            $criteria = new EngenheiroCriteria();

            $engenheiros = $this->Phreezer->Query('Engenheiro',$criteria);
            $engenheirosA = $engenheiros->ToObjectArray(true, $this->SimpleObjectParams());
            
            $criteria2 = new ObraCriteria();

            $obras = $this->Phreezer->Query('Obra',$criteria2);
            $obrasA = $obras->ToObjectArray(true, $this->SimpleObjectParams());
            
            $this->Assign('obras', $obrasA);
            $this->Assign('engenheiros', $engenheirosA);
            
            $this->Render("check");
	}

	public function Responder()
	{
            $output = new stdClass();
            $criteria = new QuestaoCriteria();

            $questaos = $this->Phreezer->Query('Questao',$criteria);
            $output->rows = $questaos->ToObjectArray(true, $this->SimpleObjectParams());
            $output->totalResults = count($output->rows);
            $output->totalPages = 1;
            $output->pageSize = $output->totalResults;
            $output->currentPage = 1;
            
            $engenheiroid = $_REQUEST['engenheiroid'];
            $obraid = $_REQUEST['obraid'];
            $data = $_REQUEST['dataCheck'];
            
            $criteria2 = new GrupoquestaoCriteria();

            $grupoQuestoes = $this->Phreezer->Query('Grupoquestao',$criteria2);
            $gQuestoes = $grupoQuestoes->ToObjectArray(true, $this->SimpleObjectParams());
            
            $this->Assign('obraid', $obraid);
            $this->Assign('engenheiroid', $engenheiroid);
            $this->Assign('data', $data);
            $this->Assign('output', $output->rows);
            $this->Assign('grupoQuestoes', $gQuestoes);
            $this->Render("checkresponder");//redireciona para proxima pagina
	}
}
?>
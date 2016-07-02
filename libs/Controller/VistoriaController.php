<?php
/** @package    BEATRIZ::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Vistoria.php");
require_once("Model/CheckListCriteria.php");
require_once("Reporter/CheckListReporter.php");
require_once("Model/Checklistvistoria.php");

/**
 * VistoriaController is the controller class for the Vistoria object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package BEATRIZ::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class VistoriaController extends AppBaseController
{

	/**
	 * Override here for any controller-specific functionality
	 *
	 * @inheritdocs
	 */
	protected function Init()
	{
		parent::Init();

		// TODO: add controller-wide bootstrap code
		
		// TODO: if authentiation is required for this entire controller, for example:
		// $this->RequirePermission(ExampleUser::$PERMISSION_USER,'SecureExample.LoginForm');
	}

	/**
	 * Displays a list view of Vistoria objects
	 */
	public function ListView()
	{
		$this->Render();
	}

	/**
	 * API Method queries for Vistoria records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new VistoriaCriteria();
			
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('Id,Data,Obraid,ObraNome,Engenheiroid,EngenheiroNome'
				, '%'.$filter.'%')
			);

			// TODO: this is generic query filtering based only on criteria properties
			foreach (array_keys($_REQUEST) as $prop)
			{
				$prop_normal = ucfirst($prop);
				$prop_equals = $prop_normal.'_Equals';

				if (property_exists($criteria, $prop_normal))
				{
					$criteria->$prop_normal = RequestUtil::Get($prop);
				}
				elseif (property_exists($criteria, $prop_equals))
				{
					// this is a convenience so that the _Equals suffix is not needed
					$criteria->$prop_equals = RequestUtil::Get($prop);
				}
			}

			$output = new stdClass();

			// if a sort order was specified then specify in the criteria
 			$output->orderBy = RequestUtil::Get('orderBy');
 			$output->orderDesc = RequestUtil::Get('orderDesc') != '';
 			if ($output->orderBy) $criteria->SetOrder($output->orderBy, $output->orderDesc);

			$page = RequestUtil::Get('page');

			if ($page != '')
			{
				// if page is specified, use this instead (at the expense of one extra count query)
				$pagesize = $this->GetDefaultPageSize();

				$vistorias = $this->Phreezer->Query('VistoriaReporter',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $vistorias->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $vistorias->TotalResults;
				$output->totalPages = $vistorias->TotalPages;
				$output->pageSize = $vistorias->PageSize;
				$output->currentPage = $vistorias->CurrentPage;
			}
			else
			{
				// return all results
				$vistorias = $this->Phreezer->Query('VistoriaReporter',$criteria);
				$output->rows = $vistorias->ToObjectArray(true, $this->SimpleObjectParams());
				$output->totalResults = count($output->rows);
				$output->totalPages = 1;
				$output->pageSize = $output->totalResults;
				$output->currentPage = 1;
			}


			$this->RenderJSON($output, $this->JSONPCallback());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method retrieves a single Vistoria record and render as JSON
	 */
	public function Read()
	{
            $pk = $this->GetRouter()->GetUrlParam('id');
            $vistoria = $this->Phreezer->Get('Vistoria',$pk);
             
            $criteria = new CheckListCriteria();
            $criteria->Vistoriaid_Equals = $vistoria->Id;
            
            $checklistvistorias = $this->Phreezer->Query('CheckListReporter',$criteria);
            $rows2 = $checklistvistorias->ToObjectArray(true,$this->SimpleObjectParams());
                        
            $texto = "";
	    foreach ($rows2 as $tipo){
                if($tipo->tipoavaliacao == "CO"){
                    $texto .= $tipo->questaoDescricao ." Conforme";
                } elseif ($tipo->tipoavaliacao == "NC") {
                    $texto .= $tipo->questaoDescricao ." Não Conforme";                    
                } elseif ($tipo->tipoavaliacao == "NA") {
                    $texto .= $tipo->questaoDescricao ." Não Aplicavel";
                }
                if (!empty($tipo->observacao)){
                    $texto .= "Observação: ". $tipo->observacao ."\n";                
                }
                $texto .= "<br>";
            }
            
            $criteria2 = new ChecklistvistoriaCriteria();
            $criteria2->Vistoriaid_Equals = $vistoria->Id;
            
            $checklistvistorias = $this->Phreezer->Query('ChecklistvistoriaReporter',$criteria2);
            $rows = $checklistvistorias->ToObjectArray(true,$this->SimpleObjectParams());
            
            $texto2 = "";
	    foreach ($rows as $tipo){
                if($tipo->tipoavaliacao == "CO"){
                    $texto2 .= round($tipo->total/count($rows)*10,2) ."% Conforme\n";
                } elseif ($tipo->tipoavaliacao == "NC") {
                    $texto2 .= round($tipo->total/count($rows)*10,2) ."% Não Conforme\n";                    
                } elseif ($tipo->tipoavaliacao == "NA") {
                    $texto2 .= round($tipo->total/count($rows)*10,2) ."% Não Aplicavel\n";
                }           
            }
            
            $obra = $this->Phreezer->Get('Obra',$vistoria->Obraid);
            $engenheiro = $this->Phreezer->Get('Engenheiro',$vistoria->Engenheiroid);
     
            echo "<h3>Obra: " . $obra->Local . "</h3>\n";            
            echo "<h3>Engenheiro:" . $engenheiro->Nome ."</h3>\n";            
            echo $texto;            
            echo "<h4>" . $texto2 ."</h4>";
            
            $gc = GlobalConfig::GetInstance();
	    //$gc->GetRenderEngine()->display("VistoriaListView.tpl");
	    $gc->GetRenderEngine()->display("Respondido.tpl");
            //$this->RenderJSON($_REQUEST, $this->JSONPCallback());

                
      /*          try
		{
                        $criteria = new ChecklistvistoriaCriteria();
                        $criteria->Vistoriaid_Equals = $vistoria->Id;			
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('Id,Vistoriaid,Questaoid,Tipoavaliacao,Observacao'
				, '%'.$filter.'%')
			);

			$output = new stdClass();

			// if a sort order was specified then specify in the criteria
 			$output->orderBy = RequestUtil::Get('orderBy');
 			$output->orderDesc = RequestUtil::Get('orderDesc') != '';
 			if ($output->orderBy) $criteria->SetOrder($output->orderBy, $output->orderDesc);

			$page = RequestUtil::Get('page');

			if ($page != '')
			{
				// if page is specified, use this instead (at the expense of one extra count query)
				$pagesize = $this->GetDefaultPageSize();

				$checklistvistorias = $this->Phreezer->Query('CheckListReporter',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $checklistvistorias->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $checklistvistorias->TotalResults;
				$output->totalPages = $checklistvistorias->TotalPages;
				$output->pageSize = $checklistvistorias->PageSize;
				$output->currentPage = $checklistvistorias->CurrentPage;
			}
			else
			{
				// return all results
				$checklistvistorias = $this->Phreezer->Query('CheckListReporter',$criteria);
				$output->rows = $checklistvistorias->ToObjectArray(true, $this->SimpleObjectParams());
				$output->totalResults = count($output->rows);
				$output->totalPages = 1;
				$output->pageSize = $output->totalResults;
				$output->currentPage = 1;
			}


			$this->RenderJSON($output, $this->JSONPCallback());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
*/
		/*try
		{
			$pk = $this->GetRouter()->GetUrlParam('id');
			$vistoria = $this->Phreezer->Get('Vistoria',$pk);
			$this->RenderJSON($vistoria, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}*/
	}

	/**
	 * API Method inserts a new Vistoria record and render response as JSON
	 */
	public function Create()
	{
		try
		{
						
			$json = json_decode(RequestUtil::GetBody());

			if (!$json)
			{
				throw new Exception('The request body does not contain valid JSON');
			}

			$vistoria = new Vistoria($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			// this is an auto-increment.  uncomment if updating is allowed
			//$vistoria->Id = $this->SafeGetVal($json, 'id');
			$vistoria->Data = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'data')));
			$vistoria->Obraid = $this->SafeGetVal($json, 'obraid');
			$vistoria->Engenheiroid = $this->SafeGetVal($json, 'engenheiroid');

			$vistoria->Validate();
			$errors = $vistoria->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$vistoria->Save();
				//$this->RenderJSON($vistoria, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method updates an existing Vistoria record and render response as JSON
	 */
	public function Update()
	{
		try
		{
						
			$json = json_decode(RequestUtil::GetBody());

			if (!$json)
			{
				throw new Exception('The request body does not contain valid JSON');
			}

			$pk = $this->GetRouter()->GetUrlParam('id');
			$vistoria = $this->Phreezer->Get('Vistoria',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			// this is a primary key.  uncomment if updating is allowed
			// $vistoria->Id = $this->SafeGetVal($json, 'id', $vistoria->Id);

			$vistoria->Data = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'data', $vistoria->Data)));
			$vistoria->Obraid = $this->SafeGetVal($json, 'obraid', $vistoria->Obraid);
			$vistoria->Engenheiroid = $this->SafeGetVal($json, 'engenheiroid', $vistoria->Engenheiroid);

			$vistoria->Validate();
			$errors = $vistoria->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$vistoria->Save();
				$this->RenderJSON($vistoria, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}


		}
		catch (Exception $ex)
		{


			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method deletes an existing Vistoria record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('id');
			$vistoria = $this->Phreezer->Get('Vistoria',$pk);

			$vistoria->Delete();

			$output = new stdClass();

			$this->RenderJSON($output, $this->JSONPCallback());

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}
}

?>

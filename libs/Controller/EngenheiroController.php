<?php
/** @package    BEATRIZ::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Engenheiro.php");

/**
 * EngenheiroController is the controller class for the Engenheiro object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package BEATRIZ::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class EngenheiroController extends AppBaseController
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
	 * Displays a list view of Engenheiro objects
	 */
	public function ListView()
	{
		$this->Render();
	}

	/**
	 * API Method queries for Engenheiro records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new EngenheiroCriteria();
			
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('Id,Nome'
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

				$engenheiros = $this->Phreezer->Query('Engenheiro',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $engenheiros->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $engenheiros->TotalResults;
				$output->totalPages = $engenheiros->TotalPages;
				$output->pageSize = $engenheiros->PageSize;
				$output->currentPage = $engenheiros->CurrentPage;
			}
			else
			{
				// return all results
				$engenheiros = $this->Phreezer->Query('Engenheiro',$criteria);
				$output->rows = $engenheiros->ToObjectArray(true, $this->SimpleObjectParams());
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
	 * API Method retrieves a single Engenheiro record and render as JSON
	 */
	public function Read()
	{
		try
		{
			$pk = $this->GetRouter()->GetUrlParam('id');
			$engenheiro = $this->Phreezer->Get('Engenheiro',$pk);
			$this->RenderJSON($engenheiro, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method inserts a new Engenheiro record and render response as JSON
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

			$engenheiro = new Engenheiro($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			// this is an auto-increment.  uncomment if updating is allowed
			// $engenheiro->Id = $this->SafeGetVal($json, 'id');

			$engenheiro->Nome = $this->SafeGetVal($json, 'nome');

			$engenheiro->Validate();
			$errors = $engenheiro->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$engenheiro->Save();
				$this->RenderJSON($engenheiro, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method updates an existing Engenheiro record and render response as JSON
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
			$engenheiro = $this->Phreezer->Get('Engenheiro',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			// this is a primary key.  uncomment if updating is allowed
			// $engenheiro->Id = $this->SafeGetVal($json, 'id', $engenheiro->Id);

			$engenheiro->Nome = $this->SafeGetVal($json, 'nome', $engenheiro->Nome);

			$engenheiro->Validate();
			$errors = $engenheiro->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$engenheiro->Save();
				$this->RenderJSON($engenheiro, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}


		}
		catch (Exception $ex)
		{


			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method deletes an existing Engenheiro record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('id');
			$engenheiro = $this->Phreezer->Get('Engenheiro',$pk);

			$engenheiro->Delete();

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

<?php
/** @package    Beatriz::Reporter */

/** import supporting libraries */
require_once("verysimple/Phreeze/Reporter.php");

/**
 * This is an example Reporter based on the CheckList object.  The reporter object
 * allows you to run arbitrary queries that return data which may or may not fith within
 * the data access API.  This can include aggregate data or subsets of data.
 *
 * Note that Reporters are read-only and cannot be used for saving data.
 *
 * @package Beatriz::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class CheckListReporter extends Reporter
{

	public $Id;
	public $Vistoriaid;
	public $Questaoid;
        public $QuestaoDescricao;
	public $Tipoavaliacao;
	public $Observacao;

	/*
	* GetCustomQuery returns a fully formed SQL statement.  The result columns
	* must match with the properties of this reporter object.
	*
	* @see Reporter::GetCustomQuery
	* @param Criteria $criteria
	* @return string SQL statement
	*/
	static function GetCustomQuery($criteria)
	{
            
		$sql = "select
			`checklistvistoria`.`ID` as Id
			,`checklistvistoria`.`VISTORIAID` as Vistoriaid
			,`checklistvistoria`.`QUESTAOID` as Questaoid
			,`questao`.`DESCRICAO` as QuestaoDescricao
			,`checklistvistoria`.`TIPOAVALIACAO` as Tipoavaliacao
			,`checklistvistoria`.`OBSERVACAO` as Observacao
		from `checklistvistoria`
                inner join `questao` on `checklistvistoria`.`QUESTAOID` = `questao`.`ID`
                where `checklistvistoria`.`VISTORIAID` = '" . $criteria->Escape($criteria->Vistoriaid_Equals) . "'";
  
		return $sql;
	}
	
	/*
	* GetCustomCountQuery returns a fully formed SQL statement that will count
	* the results.  This query must return the correct number of results that
	* GetCustomQuery would, given the same criteria
	*
	* @see Reporter::GetCustomCountQuery
	* @param Criteria $criteria
	* @return string SQL statement
	*/
	static function GetCustomCountQuery($criteria)
	{
		$sql = "select count(1) as counter from `checklistvistoria`";

		// the criteria can be used or you can write your own custom logic.
		// be sure to escape any user input with $criteria->Escape()
		$sql .= $criteria->GetWhere();

		return $sql;
	}
}

?>
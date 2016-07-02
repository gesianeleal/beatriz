<?php
/** @package    Beatriz::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");
require_once("verysimple/Phreeze/IDaoMap2.php");

/**
 * CheckListMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the CheckListDAO to the checklistvistoria datastore.
 *
 * WARNING: THIS IS AN AUTO-GENERATED FILE
 *
 * This file should generally not be edited by hand except in special circumstances.
 * You can override the default fetching strategies for KeyMaps in _config.php.
 * Leaving this file alone will allow easy re-generation of all DAOs in the event of schema changes
 *
 * @package Beatriz::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class CheckListMap implements IDaoMap, IDaoMap2
{

	private static $KM;
	private static $FM;
	
	/**
	 * {@inheritdoc}
	 */
	public static function AddMap($property,FieldMap $map)
	{
		self::GetFieldMaps();
		self::$FM[$property] = $map;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public static function SetFetchingStrategy($property,$loadType)
	{
		self::GetKeyMaps();
		self::$KM[$property]->LoadType = $loadType;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function GetFieldMaps()
	{
		if (self::$FM == null)
		{
			self::$FM = Array();
			self::$FM["Id"] = new FieldMap("Id","checklistvistoria","ID",true,FM_TYPE_INT,11,null,true);
			self::$FM["Vistoriaid"] = new FieldMap("Vistoriaid","checklistvistoria","VISTORIAID",false,FM_TYPE_INT,11,null,false);
			self::$FM["Questaoid"] = new FieldMap("Questaoid","checklistvistoria","QUESTAOID",false,FM_TYPE_INT,11,null,false);
			self::$FM["Tipoavaliacao"] = new FieldMap("Tipoavaliacao","checklistvistoria","TIPOAVALIACAO",false,FM_TYPE_VARCHAR,2,null,false);
			self::$FM["Observacao"] = new FieldMap("Observacao","checklistvistoria","OBSERVACAO",false,FM_TYPE_TEXT,null,null,false);
		}
		return self::$FM;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function GetKeyMaps()
	{
		if (self::$KM == null)
		{
			self::$KM = Array();
			self::$KM["fk_checklistvistoria_questao"] = new KeyMap("fk_checklistvistoria_questao", "Questaoid", "Questao", "Id", KM_TYPE_MANYTOONE, KM_LOAD_LAZY); // you change to KM_LOAD_EAGER here or (preferrably) make the change in _config.php
			self::$KM["fk_checklistvistoria_vistoria"] = new KeyMap("fk_checklistvistoria_vistoria", "Vistoriaid", "Vistoria", "Id", KM_TYPE_MANYTOONE, KM_LOAD_LAZY); // you change to KM_LOAD_EAGER here or (preferrably) make the change in _config.php
		}
		return self::$KM;
	}

}

?>
<?php
/** @package    Beatriz::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");
require_once("verysimple/Phreeze/IDaoMap2.php");

/**
 * ObraMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the ObraDAO to the obra datastore.
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
class ObraMap implements IDaoMap, IDaoMap2
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
			self::$FM["Id"] = new FieldMap("Id","obra","ID",true,FM_TYPE_INT,11,null,true);
			self::$FM["Local"] = new FieldMap("Local","obra","LOCAL",false,FM_TYPE_VARCHAR,200,null,false);
			self::$FM["Endereco"] = new FieldMap("Endereco","obra","ENDERECO",false,FM_TYPE_VARCHAR,200,null,false);
			self::$FM["Bairro"] = new FieldMap("Bairro","obra","BAIRRO",false,FM_TYPE_VARCHAR,50,null,false);
			self::$FM["Municipio"] = new FieldMap("Municipio","obra","MUNICIPIO",false,FM_TYPE_VARCHAR,50,null,false);
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
			self::$KM["fk_vistoria_obra_idx"] = new KeyMap("fk_vistoria_obra_idx", "Id", "Vistoria", "Obraid", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
		}
		return self::$KM;
	}

}

?>
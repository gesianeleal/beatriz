<?php
/** @package    Beatriz::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");
require_once("verysimple/Phreeze/IDaoMap2.php");

/**
 * VistoriaMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the VistoriaDAO to the vistoria datastore.
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
class VistoriaMap implements IDaoMap, IDaoMap2
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
			self::$FM["Id"] = new FieldMap("Id","vistoria","ID",true,FM_TYPE_INT,11,null,true);
			self::$FM["Data"] = new FieldMap("Data","vistoria","DATA",false,FM_TYPE_DATE,null,null,false);
			self::$FM["Obraid"] = new FieldMap("Obraid","vistoria","OBRAID",false,FM_TYPE_INT,11,null,false);
			self::$FM["Engenheiroid"] = new FieldMap("Engenheiroid","vistoria","ENGENHEIROID",false,FM_TYPE_INT,11,null,false);
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
			self::$KM["fk_checklistvistoria_vistoria"] = new KeyMap("fk_checklistvistoria_vistoria", "Id", "Checklistvistoria", "Vistoriaid", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
			self::$KM["fk_vistoria_engenheiro_idx"] = new KeyMap("fk_vistoria_engenheiro_idx", "Engenheiroid", "Engenheiro", "Id", KM_TYPE_MANYTOONE, KM_LOAD_LAZY); // you change to KM_LOAD_EAGER here or (preferrably) make the change in _config.php
			self::$KM["fk_vistoria_obra_idx"] = new KeyMap("fk_vistoria_obra_idx", "Obraid", "Obra", "Id", KM_TYPE_MANYTOONE, KM_LOAD_LAZY); // you change to KM_LOAD_EAGER here or (preferrably) make the change in _config.php
		}
		return self::$KM;
	}

}

?>
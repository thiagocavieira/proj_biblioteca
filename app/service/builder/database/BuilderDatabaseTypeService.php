<?php
/**
 * Classe de databases do builder
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class BuilderDatabaseTypeService
{
    const MYSQL  = 1;
    const PGSQL  = 2;
    const ORACLE = 3;
    const MSSQL  = 4;
    const SQLITE = 5;
    const FIREBIRD  = 6;

	public static function getType($type)
	{
		if($type == 'pgsql')
		{
			$typeId = self::PGSQL;
		}
		else if($type == 'mysql')
		{
			$typeId = self::MYSQL;
		}
		else if($type == 'sqlite')
		{
			$typeId = self::SQLITE;
		}
		else if(in_array($type, ['ibase', 'fbird']))
		{
			$typeId = self::FIREBIRD;
		}
		else if($type == 'oracle')
		{
			$typeId = self::ORACLE;
		}
		else if(in_array($type, ['mssql','dblib', 'sqlsrv']))
		{
			$typeId = self::MSSQL;
		}
		else
		{
			throw new Exception("Database type not supported");
		}

		return $typeId;
	}

	public static function getAutoIncrementCommand($database_type_id, $table = null, $column = null, $size = '')
	{
	    if($database_type_id == self::MYSQL)
	    {
	        return " INT {$size} AUTO_INCREMENT ";
	    }
	    elseif($database_type_id == self::ORACLE)
	    {
	        return "CREATE SEQUENCE {$table}_{$column}_seq START WITH 1 INCREMENT BY 1; \n
	        CREATE OR REPLACE TRIGGER {$table}_{$column}_seq_tr \n
	        BEFORE INSERT ON {$table} FOR EACH ROW \n
	        WHEN \n
	        (NEW.{$column} IS NULL) \n
	        BEGIN \n
	        SELECT {$table}_{$column}_seq.NEXTVAL INTO :NEW.{$column} FROM DUAL; \n
	        END;\n";
	    }
	    elseif($database_type_id == self::PGSQL)
	    {
	        return " SERIAL ";
	    }
	    elseif($database_type_id == self::MSSQL)
	    {
	        return " INT IDENTITY ";
	    }
	    elseif($database_type_id == self::SQLITE)
	    {
	        return ' INTEGER ';
	    }
	    elseif($database_type_id == self::FIREBIRD)
	    {
	        return ' integer generated by default as identity primary key ';
	    }
	}
}
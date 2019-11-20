<?php
namespace config;

use tvustat\ConnectionPreloaded;
use tvustat\DBTableEntry;

interface dbTable
{

    /**
     *
     * @return array[string]
     */
    public static function getCollumNames();

    /**
     *
     * @return string
     */
    public static function getTableName();

    /**
     *
     * @return string
     */
    public static function getIDString();

    /**
     *
     * @param DBTableEntry $tableEntry
     * @return array[mixed]
     */
    public static function classToCollumns(DBTableEntry $tableEntry);

    /**
     *
     * @param array $columns
     * @param ConnectionPreloaded $conn
     * @return DBTableEntry
     */
    public static function array2Elmt(array $columns, ConnectionPreloaded $conn);
}


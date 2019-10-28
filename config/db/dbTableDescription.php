<?php
namespace config;

abstract class dbTableDescription
{

    /**
     *
     * @return array
     */
    abstract static function getCollumNames();

    /**
     *
     * @return string
     */
    abstract static function getTableName();

    /**
     *
     * @return string
     */
    abstract static function getIDString();

    /**
     *
     * @param
     *            mixed
     * @return array
     */
    abstract static function classToCollumns($value);
}


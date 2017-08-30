<?php
namespace Application\Commom;

use Symfony\Component\Yaml\Yaml;

/**
 * Description of ConfigApplication
 *
 * @author rmncst
 */
class ConfigApplication 
{
    const PATH_PARAMETERS = __DIR__."/../../../config/parameters.yml";
    const PATH_PATHS = __DIR__."/../../../config/paths.yml";
    const COLUMN_CONNECTIONS = 'connections';
    const COLUMN_CLI_CONNECTION = 'cli_connection';

    public static function getRootPathApp()
    {
        return __DIR__.'/../../../';
    }

    public static function getParameters()
    {
        $input = file_get_contents(self::PATH_PARAMETERS);
        $input = Yaml::parse($input);

        return $input;
    }

    public static function getPaths()
    {
        $input = file_get_contents(self::PATH_PATHS);
        $input = Yaml::parse($input);

        return $input;
    }

    public static function getParametersConnections()
    {
        return self::getParameters()[self::COLUMN_CONNECTIONS];
    }
    
    public static function getParametersCliConnection()
    {
        return self::getParametersConnection(self::getParameters()[self::COLUMN_CLI_CONNECTION]);
    }
        
    public static function getParametersConnection($name)
    {
        return self::getParameters()[self::COLUMN_CONNECTIONS][$name];
    }
    
    public static function getPathMetadataEntityAnnotation()
    {
        return self::getRootPathApp().''.self::getPaths()['doctrine']['orm']['metadata_entity_annotation'];
    }
    
    public static function getEntityNamespace()
    {
        return self::getPaths()['doctrine']['orm']['entity_namesapace'];
    }

    public static function getControllerRootDirectory()
    {
        return self::getRootPathApp().''.self::getPaths()['application']['controller'];
    }

    public static function getRoutesFilePath()
    {
        return self::getRootPathApp().''.self::getPaths()['application']['routes_file'];
    }

    public static function getRoutesArray()
    {
        $input_yaml = file_get_contents(self::getRoutesFilePath());
        $output = Yaml::parse($input_yaml);

        return $output;
    }
}

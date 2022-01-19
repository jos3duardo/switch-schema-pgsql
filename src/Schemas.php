<?php

namespace Jos3duardo;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

/**
 *  Class Schemas
 *
 * @package Jos3duardo\Schemas
 */
class Schemas
{
    protected function listTables($schema)
    {
        return DB::table('information_schema.tables')
            ->select('table_name')
            ->where('table_schema', $schema)
            ->get();
    }

    protected function tableExists($schema, $tableName)
    {
        $tables = $this->listTables($schema);
        foreach ($tables as $table) {
            if ($table->table_name === $tableName) {
                return true;
            }
        }
        return false;
    }

    public function schemaExists($schemaName)
    {
        $schema = DB::table('information_schema.schemata')
            ->select('schema_name')
            ->where('schema_name', $schemaName)
            ->count();

        return $schema > 0;
    }

    public function create($schema)
    {
        DB::statement("CREATE SCHEMA $schema");
    }

    public function switchTo($schema = 'public')
    {
        $this->setSchemaInConnection($schema);
        if (!is_array($schema)) {
            $schema = [$schema];
        }

        $query = 'SET search_path TO ' . implode(',', $schema);

        DB::statement($query);
    }

    private function setSchemaInConnection($schema)
    {
        $driver = DB::connection()->getConfig('driver');
        $config = Config::get("database.connection.$driver");
        $config['schema'] = $schema;

        Config::set("database.connections.$driver", $config);
    }

    public function drop($schema)
    {
        DB::statement("DROP SCHEMA $schema CASCADE");
    }

    public function migrate($schema, $args = [])
    {
        $this->switchTo($schema);

        if (!$this->tableExists($schema, 'migrations')) {
            Artisan::call('migrate:install');
        }

        Artisan::call('migrate', $args);
    }

    public function migrateRefresh($schema, $args = [])
    {
        $this->switchTo($schema);
        Artisan::call('migrate:refresh', $args);
    }

    public function migrateReset($schema, $args = [])
    {
        $this->switchTo($schema);
        Artisan::call('migrate:reset', $args);
    }

    public function migrateRollback($schema, $args = [])
    {
        $this->switchTo($schema);
        Artisan::call('migrate:rollback', $args);
    }

    public function getSearchPath()
    {
        $query = DB::select('show search');
        return array_pop($query)->serarch_path;
    }

}

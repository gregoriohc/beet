<?php

namespace Gregoriohc\Beet\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Gregoriohc\Beet\Filesystem\FileBuilder;

abstract class ObjectCommand extends Command
{
    const MS = '            ';

    /**
     * The file builder instance.
     *
     * @var FileBuilder
     */
    protected $builder;

    /**
     * The action.
     *
     * @var string
     */
    protected $action;

    /**
     * The object name.
     *
     * @var string
     */
    protected $object;

    /**
     * The object table name.
     *
     * @var string
     */
    protected $table;

    /**
     * The columns.
     *
     * @var array
     */
    protected $columns;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new object and all its related files';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->builder = new FileBuilder();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->columns = json_decode($this->argument('columns'), true);
        $this->object = studly_case($this->argument('object'));
        $this->table = snake_case_plural($this->object);

        $files = $this->objectFiles();

        if ('destroy' == $this->action && !$this->confirm('Are you sure you want to destroy the object '.$this->object.' and all its files? [y|N]')) {
            return;
        } elseif ('update' == $this->action && !$this->confirm('Are you sure you want to update the object '.$this->object.' and its base files? [y|N]')) {
            return;
        }

        foreach ($files as $file) {
            switch($file['action']) {
                case 'create':
                    $file['end'] = isset($file['end']) ? $file['end'] : null;
                    $file['variables'] = isset($file['variables']) ? $file['variables'] : [];
                    try {
                        $fileCreated = $this->builder->create($file['stub'], $file['variables'], $file['end']);
                        $this->info($fileCreated . ' created successfully!');
                    } catch (\Exception $e) {
                        $this->error($e->getMessage());
                    }
                    break;
                case 'update':
                    $file['end'] = isset($file['end']) ? $file['end'] : null;
                    $file['variables'] = isset($file['variables']) ? $file['variables'] : [];
                    try {
                        $fileCreated = $this->builder->create($file['stub'], $file['variables'], $file['end'], true);
                        $this->info($fileCreated . ' updated successfully!');
                    } catch (\Exception $e) {
                        $this->error($e->getMessage());
                    }
                    break;
                case 'destroy':
                    $file['end'] = isset($file['end']) ? $file['end'] : $file['stub'];
                    try {
                        $fileDeleted = $this->builder->delete($file['end']);
                        $this->info($fileDeleted . ' deleted successfully!');
                    } catch (\Exception $e) {
                        $this->error($e->getMessage());
                    }
                    break;
                default:
                    break;
            }
        }

        $this->call('migrate');
    }

    /**
     * @return array
     */
    private function objectFiles() {
        $files = [];

        // Migration
        $migrationVariables = [
            '{{table}}' => $this->table,
        ];
        // TODO: Detect if the migration class name already exists
        $date = str_replace([' ', '-'], '_', str_replace(':', '', Carbon::now()->toDateTimeString()));
        $migrationName = $this->objectMigrationTable();
        switch ($this->action) {
            case 'create': {
                $migrationVariables['{{class}}'] = studly_case($migrationName);
                $migrationVariables['{{columns}}'] = $this->objectMigrationColumns();
                $migrationVariables['{{columnsForeign}}'] = $this->objectMigrationColumnsForeign();
                $files[] = [
                    'stub' => 'database/migrations/create_object_table.php',
                    'end' => 'database/migrations/'.$date.'_'.$migrationName.'.php',
                    'variables' => $migrationVariables,
                    'action' => 'create',
                ];
                break;
            }
            case 'update': {
                $migrationVariables['{{class}}'] = studly_case($migrationName);
                $migrationVariables['{{columnsUp}}'] = $this->objectMigrationColumnsUp();
                $migrationVariables['{{columnsUpForeign}}'] = $this->objectMigrationColumnsUpForeign();
                $migrationVariables['{{columnsDown}}'] = $this->objectMigrationColumnsDown();
                $migrationVariables['{{columnsDownForeign}}'] = $this->objectMigrationColumnsDownForeign();
                $files[] = [
                    'stub' => 'database/migrations/update_object_table.php',
                    'end' => 'database/migrations/'.$date.'_'.$migrationName.'.php',
                    'variables' => $migrationVariables,
                    'action' => 'create',
                ];
                break;
            }
            case 'destroy': {
                $migrationVariables['{{class}}'] = studly_case($migrationName);
                $migrationVariables['{{columns}}'] = $this->objectMigrationColumns();
                $migrationVariables['{{columnsForeign}}'] = $this->objectMigrationColumnsForeign();
                $files[] = [
                    'stub' => 'database/migrations/delete_object_table.php',
                    'end' => 'database/migrations/'.$date.'_'.$migrationName.'.php',
                    'variables' => $migrationVariables,
                    'action' => 'create',
                ];
                break;
            }
            default:
                break;
        }

        // Base and final model
        $files[] = [
            'stub' => 'App/Base/Models/Model.php',
            'end' => 'App/Base/Models/' . $this->object . '.php',
            'variables' => [
                '{{class}}' => $this->object,
            ],
            'action' => $this->action,
        ];
        if ('update' != $this->action) {
            $files[] = [
                'stub' => 'App/Models/Model.php',
                'end' => 'App/Models/' . $this->object . '.php',
                'variables' => [
                    '{{class}}' => $this->object,
                ],
                'action' => $this->action,
            ];
        }

        // Base and final controllers for admin, api and web
        foreach (['Admin', 'Api', 'Web'] as $type) {
            $files[] = [
                'stub' => 'App/Base/Http/Controllers/Controller.php',
                'end' => 'App/Base/Http/Controllers/' . $type . '/' . $this->object . 'Controller.php',
                'variables' => [
                    '{{class}}' => $this->object . 'Controller',
                    '{{baseClass}}' => $type . 'Controller',
                    '{{namespaceSuffix}}' => $type,
                ],
                'action' => $this->action,
            ];

            if ('update' != $this->action) {
                $files[] = [
                    'stub' => 'App/Http/Controllers/Controller.php',
                    'end' => 'App/Http/Controllers/' . $type . '/' . $this->object . 'Controller.php',
                    'variables' => [
                        '{{class}}' => $this->object . 'Controller',
                        '{{namespaceSuffix}}' => $type,
                    ],
                    'action' => $this->action,
                ];
            }
        }

        return $files;
    }

    private function objectMigrationTable()
    {
        $count = 0;
        do {
            $count++;
            $migrationName = $this->action.'_'.$count.'_'.snake_case($this->object).'_table';
        } while ($this->builder->existsSimilarInDirectory($migrationName.'.php', base_path('database/migrations')));

        return $migrationName;
    }

    /**
     * @return string
     */
    private function objectMigrationColumns()
    {
        $code = '';
        $code .= '$table->increments(\'id\');' . PHP_EOL . self::MS;
        $code .= '$table->timestamps();' . PHP_EOL . self::MS;
        $code .= $this->objectMigrationColumnsUp();
        $ancestor = class_ancestor('App\\Models\\' . $this->object);
        if ($ancestor) {
            $ancestor = class_basename($ancestor);
            $foreignKey = snake_case($ancestor).'_id';
            $code .= '$table->integer(\''.$foreignKey.'\')->unsigned();' . PHP_EOL . self::MS;
        }
        return $code;
    }

    /**
     * @return string
     */
    private function objectMigrationColumnsForeign()
    {
        $code = '';
        $ancestor = class_ancestor('App\\Models\\' . $this->object);
        if ($ancestor) {
            $ancestor = class_basename($ancestor);
            $ancestorTable = snake_case_plural($ancestor);
            $foreignKey = snake_case($ancestor).'_id';
            $code .= '$table->foreign(\''.$foreignKey.'\')->references(\'id\')->on(\''.$ancestorTable.'\');' . PHP_EOL . self::MS;
        }
        return $code;
    }

    /**
     * @return string
     */
    private function objectMigrationColumnsUp()
    {
        $code = '';
        foreach ($this->columns as $column) {
            $extras = '';
            if (isset($column['length'])) $extras .= ', '.$column['length'];
            $code .= '$table->'.$column['type'].'(\''.$column['name'].'\''.$extras.')';
            if (isset($column['nullable']) && true === $column['nullable']) $code .= '->nullable()';
            if (isset($column['unsigned']) && true === $column['unsigned']) $code .= '->unsigned()';
            if (isset($column['use_current']) && true === $column['use_current']) $code .= '->useCurrent()';
            if (isset($column['default'])) $code .= '->default(\''.$column['default'].'\')';
            $code .= ';' . PHP_EOL . self::MS;
        }
        return $code;
    }

    /**
     * @return string
     */
    private function objectMigrationColumnsUpForeign()
    {

    }

    /**
     * @return string
     */
    private function objectMigrationColumnsDown()
    {
        $code = '';
        foreach ($this->columns as $column) {
            $code .= '$table->dropColumn(\''.$column['name'].'\')';
            $code .= ';' . PHP_EOL . self::MS;
        }
        return $code;
    }

    /**
     * @return string
     */
    private function objectMigrationColumnsDownForeign()
    {

    }
}

<?php

namespace Gregoriohc\Beet\Console\Commands;

class Update extends ObjectCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'beet:update {object} {columns}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update an object and its related files';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->action = 'update';
        parent::handle();
    }
}

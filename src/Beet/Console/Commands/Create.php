<?php

namespace Gregoriohc\Beet\Console\Commands;

class Create extends ObjectCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'beet:create {object} {columns}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new object and all its related files';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->action = 'create';
        parent::handle();
    }
}

<?php

namespace Gregoriohc\Beet\Console\Commands;

class Destroy extends ObjectCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'beet:destroy {object} {columns}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Destroy an object and all its related files';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->action = 'destroy';
        parent::handle();
    }
}

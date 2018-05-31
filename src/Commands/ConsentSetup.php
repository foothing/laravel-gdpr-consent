<?php namespace Foothing\Laravel\Consent\Commands;

use Foothing\Laravel\Consent\ConsentApi;
use Illuminate\Console\Command;

class ConsentSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup treatments database.';

    /**
     * @var \Foothing\Laravel\Consent\ConsentApi
     */
    protected $consent;

    /**
     * Create a new command instance.
     */
    public function __construct(ConsentApi $consent)
    {
        parent::__construct();

        $this->consent = $consent;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->consent->configure();
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\SendMailController;

class StartMailing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:mailing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[Codsletter] Start the process of mailing to subscriptions.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \Log::info("Start mailing...");

        $send_mail_controller = new SendMailController();
        $send_mail_controller->startMailing();

        \Log::info("Start mailing operation finisched!");
    }
}

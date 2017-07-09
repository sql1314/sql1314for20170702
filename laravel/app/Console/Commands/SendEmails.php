<?php

namespace App\Console\Commands;

use App\User;
use App\DripEmailer;
use Illuminate\Console\Command;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:send{user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send drip e-mails to a user';

    /**
     * 滴灌电子邮件服务
     *
     * @var DripEmailer
     */
    protected $drip;

    /**
     * Create a new command instance.
     * 创建命令实例
     * @param DripEmailer $drip
     * @return void
     */
    public function __construct(DripEmailer $drip)
    {
        parent::__construct();
        $this->drip = $drip;
    }

    /**
     *
     *
     * @return mixed
     */
    public function handle()
    {

    }
}

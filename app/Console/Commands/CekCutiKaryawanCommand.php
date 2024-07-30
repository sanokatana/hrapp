<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CutiController;

class CekCutiKaryawanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cuti:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update cuti karyawan records';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cutiController = new CutiController();
        $cutiController->cekCutiKaryawan();

        $this->info('Cuti karyawan has been checked and updated.');
        return 0;
    }
}

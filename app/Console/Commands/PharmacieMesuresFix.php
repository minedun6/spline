<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pharmacie\Pharmacie;

class PharmacieMesuresFix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mesures:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $a = Pharmacie::all();
        echo "Fixing pharmacies...\n";
        
        foreach ($a as $key => $pharmacy) {
            $extras = json_decode($pharmacy->extras, true);
            if (isset(json_decode($pharmacy->extras, true)['mesures'])) {
                $mesures = array_keys(json_decode($pharmacy->extras, true)['mesures']);

                $m = [];
                foreach ($mesures as $k => $v) {
                    $m[$k+1] = json_decode($pharmacy->extras, true)['mesures'][$v];
                }
                if(count($m) != 0){
                    $extras['mesures'] = $m;
                    $pharmacy->extras = json_encode($extras);
                    $pharmacy->save();
                }
            }
        }
        echo "All done!\n";
    }
}

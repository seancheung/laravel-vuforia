<?php

namespace Panoscape\Vuforia\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Panaroma\Vuforia\VuforiaWebService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
* Upload image to Vuforia
*/
abstract class VuforiaJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;
    
    /**
    * Execute the job.
    *
    * @param  VuforiaWebService  $vws
    * @return void
    */
    abstract function handle(VuforiaWebService $vws);
    
    /**
    * The job failed to process.
    *
    * @param  Exception  $exception
    * @return void
    */
    abstract function failed(Exception $exception);
}

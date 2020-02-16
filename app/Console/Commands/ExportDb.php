<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ExportDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a full export of the DB and puts it on s3';

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
        set_time_limit(0);

        // define target file
        $tempLocation     = '/tmp/' . config('database.connections.mysql.database') . '_' . date("Y-m-d_Hi") . '.sql';
        $targetFilePath   = '/mysql/' . config('database.connections.mysql.database') . '_' . date("Y-m-d_Hi") . '.sql';

        // run the cli job
        $process = new Process('mysqldump -u' . config('database.connections.mysql.username') . ' -p' .config('database.connections.mysql.password') . ' ' . config('database.connections.mysql.database') . ' > ' . $tempLocation);
        $process->run();

        try {

            if ($process->isSuccessful())
            {
                $s3 = \Storage::disk('cloud');
                $s3->put($targetFilePath, file_get_contents($tempLocation), 'private');

                $current_timestamp = time() - (144 * 3600);
                $allFiles = $s3->allFiles('/mysql/');

                foreach ($allFiles as $file)
                {
                    // delete the files older then X days..
                    if ( $s3->lastModified($file) <= $current_timestamp )
                    {
                        $s3->delete($file);
                        $this->info("File: {$file} deleted.");
                    }
                }
            }
            else {
                throw new ProcessFailedException($process);
            }

            @unlink($tempLocation);
        }
        catch (\Exception $e)
        {
            $this->info($e->getMessage());
        }
    }
}

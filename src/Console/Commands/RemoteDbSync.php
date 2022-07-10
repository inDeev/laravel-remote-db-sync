<?php

namespace Indeev\LaravelRemoteDbSync\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RemoteDbSync extends Command {
    protected $signature = 'db:sync_remote
                            {connection : Remote DB connection defined in config/database.php}
                            {--skipped=null : Table names (separated by comma) to be ignored}';
    protected $description = 'Synchronize remote database to local';
    protected string $remoteConnection;
    protected array $skippedTables = [];

    /** Common issues:
     *  SQLSTATE[08S01]: Communication link failure: 1153 Got a packet bigger than 'max_allowed_packet' bytes
     *      - add "max_allowed_packet=1000M" to my.cnf
     */

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->remoteConnection = $this->argument('connection');
        $skippedTables = $this->option('skipped');
        $this->skippedTables = $skippedTables === 'null' ? [] : explode(',', $skippedTables);

        if (config('app.env') === 'production') {
            $this->error('Run on production is forbidden.');
            return 0;
        }

        if (!isset(config('database.connections')[$this->remoteConnection])) {
            $this->error('Remote connection ' . $this->remoteConnection . ' not found in database config file.');
            return 0;
        }

        $remoteTables = DB::connection($this->remoteConnection)->getDoctrineSchemaManager()->listTableNames();
        $tablesToSync = array_diff($remoteTables, $this->skippedTables);
        $bar = $this->output->createProgressBar(1);

        $bar->setBarWidth(50);
        $bar->setMessage('Fetching remote tables to sync');
        $bar->setFormat("%message%\n%current%/%max% [%bar%] Processed %elapsed%/%estimated%, %percent%% done.");
        $bar->setMaxSteps(count($tablesToSync));
        $bar->start();

        $remoteHost = config('database.connections.' . $this->remoteConnection . '.host');
        $remoteUser = config('database.connections.' . $this->remoteConnection . '.username');
        $remotePassword = config('database.connections.' . $this->remoteConnection . '.password');
        $remoteDatabase = config('database.connections.' . $this->remoteConnection . '.database');

        foreach ($tablesToSync as $table) {
            Storage::put($table . '.sql', '');
            $bar->setMessage('Fetching remote table ' . $table);
            exec("mysqldump --host=$remoteHost --user=$remoteUser --password='$remotePassword' $remoteDatabase $table > " . storage_path('app/' . $table . '.sql'));
            $bar->advance(0);
            $bar->setMessage('Updating local table ' . $table);
            DB::unprepared(file_get_contents(storage_path('app/' . $table . '.sql')));
            $bar->advance(0);
            $bar->setMessage('Deleting temp file for ' . $table);
            unlink(storage_path('app/' . $table . '.sql'));
            $bar->advance();
        }
        $bar->finish();
        return 0;
    }
}

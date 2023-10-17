<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class SetDbStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set-storage:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install storage in database';

    /**
     * Filesystem instance for file operations.
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $type = $this->choice('Select data to store in the database:', [
            'Only users data',
            'All data',
        ], 0, 3);

        switch ($type) {
            case 'Only users data':
                $this->setUserDbStorage();
                break;
            case 'All data':
                $this->setAllDbStorage();
                break;
            default:
                $this->error('Wrong choice. No data will be stored in the data database..');
        }
    }

    /**
     * Storing users to database.
     *
     * @return void
     */
    protected function setUserDbStorage()
    {
        $content = str_replace("'repository' => 'file',", "'repository' => 'eloquent',", $this->filesystem->get(config_path('statamic/users.php')));
        $this->filesystem->put(config_path('statamic/users.php'), $content);

        $this->filesystem->copy(app_path('Console/Commands/stubs/config/auth.stub'), config_path('auth.php'));

        $this->executeCommand('php please auth:migration');
        $this->executeCommand('php artisan migrate');

        $this->filesystem->copy(app_path('Console/Commands/stubs/App/Models/User.stub'), app_path('Models/User.php'));
    }

    /**
     * Storing all data to database.
     *
     * @return void
     */
    protected function setAllDbStorage()
    {
        $this->executeCommand('composer require statamic/eloquent-driver');
        $this->executeCommand('php artisan vendor:publish --tag="statamic-eloquent-config"');

        $this->filesystem->delete(base_path('content/collections/pages/home.md'));
        $this->filesystem->copy(app_path('Console/Commands/stubs/content/collections/pages.stub'), base_path('content/collections/pages.yaml'));

        $this->executeCommand('php artisan vendor:publish --provider="Statamic\Eloquent\ServiceProvider" --tag=migrations');
        $this->executeCommand('php artisan vendor:publish --tag="statamic-eloquent-entries-table"');
        $this->executeCommand('php artisan migrate');

        $this->setUserDbStorage();
    }

    /**
     * Execute a shell command.
     *
     * @param string $command The shell command to execute.
     *
     * @return void
     */
    protected function executeCommand($command)
    {
        $process = Process::fromShellCommandline($command, base_path());
        $process->setTty(true)->run();

        if (!$process->isSuccessful()) {
            $this->error('Error executing command: ' . $command);
            $this->error($process->getErrorOutput());
            exit(1);
        }
    }
}

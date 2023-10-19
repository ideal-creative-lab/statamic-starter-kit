<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Enums\PackageManager;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class SetPackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:package';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     */
    public function handle()
    {
        $this->initPackageManager();
    }

    /**
     * Install and initialize the package manager.
     *
     * @return void
     */
    protected function initPackageManager()
    {
        $manager = $this->choice(
            'Choose a package manager to install:',
            PackageManager::getValues(),
            0
        );

        $this->filesystem->put(app_path('Console/Commands/stubs/config/manager.stub'), $manager);

        $this->executeCommand($manager . ' init');
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

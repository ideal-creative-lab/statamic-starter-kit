<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class InstallBaseTool extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:tool';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installation of HTMX or LiveWire';

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
        $library = $this->choice('Choose a library to install:', [
            'HTMX',
            'LiveWire',
        ], 0, 3);

        switch ($library) {
            case 'HTMX':
                $this->installHTMX();
                break;
            case 'LiveWire':
                $this->installLiveWire();
                break;
            default:
                $this->error('Invalid choice. No libraries will be installed.');
        }

        $addPreview = $this->ask('Add live preview (y/n)');

        if (strtolower($addPreview) === 'y') {
            $preview = $this->filesystem->get(app_path('Console/Commands/stubs/resources/views/live_preview.stub'));
            $this->filesystem->append(resource_path('views/layout.antlers.html'), $preview);
            $this->info('Live preview added');
        } else {
            $this->info('Live preview skipped');
        }
    }

    /**
     * Install HTMX.
     *
     * @return void
     */
    protected function installHTMX()
    {
        $this->info('Installing HTMX...');

        $this->executeCommand('npm install -D alpinejs htmx.org @alpinejs/morph');
        $this->updateJs();

        $this->info('HTMX installed');
    }

    /**
     * Install LiveWire.
     *
     * @return void
     */
    protected function installLiveWire()
    {
        $this->info('Installing LiveWire...');
        $this->executeCommand('composer require jonassiewertsen/statamic-livewire');
        $this->filesystem->copy(app_path('Console/Commands/stubs/resources/views/layout.antlers.stub'), resource_path('views/layout.antlers.html'));
        $this->info('LiveWire installed');
    }

    /**
     * Update site.js.
     *
     * @return void
     */
    protected function updateJs()
    {
        $content = /** @lang JavaScript */
            <<<EOL
            import Alpine from 'alpinejs'
            import Htmx from 'htmx.org'
            import morph from '@alpinejs/morph'

            window.Alpine = Alpine
            window.Htmx = Htmx
            Alpine.plugin(morph)

            Alpine.start()
            EOL;

        $this->filesystem->put(resource_path('js/site.js'), $content);
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
        $process->run();

        if (!$process->isSuccessful()) {
            $this->error('Error executing command: ' . $command);
            $this->error($process->getErrorOutput());
            exit(1);
        }
    }
}

<?php
use Symfony\Component\Process\Process;

class StarterKitPostInstall
{
    public function handle($console)
    {
        $this->executeCommand('npm install -g bun');
        $this->executeCommand('bun init');

        $console->line('Statamic starter kit installed successfully!');
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

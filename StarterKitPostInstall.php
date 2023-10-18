<?php
use Symfony\Component\Process\Process;

class StarterKitPostInstall
{
    public function handle($console)
    {
        $this->executeCommand('npm install -g bun', $console);
        $this->executeCommand('bun init', $console);

        $console->line('Statamic starter kit installed successfully!');
    }

    /**
     * Execute a shell command.
     *
     * @param string $command The shell command to execute.
     *
     * @param $console
     * @return void
     */
    protected function executeCommand($command, $console)
    {
        $process = Process::fromShellCommandline($command, base_path());
        $process->run();

        if (!$process->isSuccessful()) {
            $console->error('Error executing command: ' . $command);
            $console->error($process->getErrorOutput());
            exit(1);
        }
    }
}

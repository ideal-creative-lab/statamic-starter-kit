<?php

class StarterKitPostInstall
{
    public function handle($console)
    {
        exec('npm install -g bun');
        exec('bun init');
        $console->line('Statamic starter kit installed successfully!');
    }
}

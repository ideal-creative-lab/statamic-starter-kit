<?php

class StarterKitPostInstall
{
    public function handle($console)
    {
        exec('php artisan set:package');
        $console->line('Statamic starter kit installed successfully!');
    }
}

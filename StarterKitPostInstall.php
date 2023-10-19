<?php

class StarterKitPostInstall
{
    public function handle($console)
    {
        $console->line('Statamic starter kit installed successfully! Please run php artisan set:package to install base package manager');
    }
}

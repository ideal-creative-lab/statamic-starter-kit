<p align="center"><img src="https://statamic.com/assets/branding/Statamic-Logo+Wordmark-Rad.svg" width="400" alt="Statamic Logo" /></p>

<h1 align="center">
    <a href="https://github.com/ideal-creative-lab/statamic-starter-kit">
        Statamic Starter Kit
    </a>
</h1>

<p align="center">
    <i align="center">This is a starter kit for Statamic projects</i>
</p>

<h4 align="center">
    <img src="https://img.shields.io/badge/release-v1.0.0-blue" alt="Project Version">
    <img src="https://img.shields.io/badge/laravel-10.8-blueviolet" alt="Laravel Version">
    <img src="https://img.shields.io/badge/php-%3E=8.1-royalblue" alt="PHP Version">
    <img src="https://img.shields.io/badge/platform-*nix-lightgrey" alt="Platform">
    <img src="https://img.shields.io/badge/license-proprietary-green" alt="License">
</h4>

## Introduction

**Statamic Starter Kit** is a collection of tools and libraries to make developing your Statamic projects easier. This set includes several useful packages and tools that will help you quickly get started and easily develop web applications in Statamic.

> [!IMPORTANT]
> Before contributing to the project, please, carefully read through this README document.

### Prerequisites
To run the project, you need to install [PHP](https://www.php.net/manual/en/install.php) and dependency manager
[Composer](https://getcomposer.org) first.

#### Installation:

1.  Before installation, you can choose between clean up project and installing over existing files (layout.antlers.stub and User model can be overwritten).
    
    Recommended installation on a clean statamic project.
    
    ```zsh
    php please starter-kit:install ideal-creative-lab/statamic-starter-kit
    ```

2. Run command for installation of [HTMX or LiveWire](https://github.com/ideal-creative-lab/statamic-starter-kit/wiki/How-to-install-HTMX-LiveWire):
    ```zsh
    php artisan install:tool
    ```

3. If you want to [store data in database](https://github.com/ideal-creative-lab/statamic-starter-kit/wiki/How-to-store-data-in-database) instead of files then run following command:
    ```zsh
    php artisan set-storage:db
    ```
    
4. For hot reloading you can use Bun:
    ```zsh
    bun run dev
    ```

## Contribute
See [CONTRIBUTING.md](CONTRIBUTING.md) for ways to get started.

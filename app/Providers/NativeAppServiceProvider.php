<?php

namespace App\Providers;

use Native\Desktop\Facades\Window;
use Native\Desktop\Contracts\ProvidesPhpIni;

class NativeAppServiceProvider implements ProvidesPhpIni
{
    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        Window::open();

        $this->startLanServer();
    }

    /**
     * Also serve the app to other PCs on the network (http://HOST-IP:8000).
     */
    protected function startLanServer(): void
    {
        $port = 8000;

        // Already running (app reopened)? Don't start a second one.
        $probe = @fsockopen('127.0.0.1', $port, $errno, $errstr, 0.3);
        if ($probe) {
            fclose($probe);

            return;
        }

        $php = PHP_BINARY ?: config('nativephp.binary_path');

        $command = 'start /B "" "' . $php . '" -S 0.0.0.0:' . $port
            . ' -t "' . public_path() . '" "' . base_path('lan-router.php') . '" > NUL 2>&1';

        pclose(popen($command, 'r'));
    }

    /**
     * Return an array of php.ini directives to be set.
     */
    public function phpIni(): array
    {
        return [
        ];
    }
}

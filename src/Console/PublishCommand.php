<?php
namespace Clumsy\CMS\Console;

use Illuminate\Console\Command;

/**
 * Publish the Clumsy assets to the public directory
 *
 * @author Tomas Buteler <tbuteler@gmail.com>
 */
class PublishCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'clumsy:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Clumsy and dependencies\' assets';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (!file_exists(getcwd().'/bootstrap/app.php')) {
            $this->info("Not inside an app directory. Publishing aborted.");
            return;
        }

        $packages = [
            'Clumsy Utils'  => 'Clumsy\Utils\UtilsServiceProvider',
            'Clumsy Eminem' => 'Clumsy\Eminem\EminemServiceProvider',
            'Clumsy CMS'    => 'Clumsy\CMS\CMSServiceProvider',
        ];

        foreach ($packages as $title => $provider) {
            $this->callSilent('vendor:publish', ['--provider' => $provider, '--tag' => ['public'], '--force' => true]);
            $this->info("{$title} assets published");
        }
    }
}

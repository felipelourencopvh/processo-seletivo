<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MaryImportCommand extends Command
{
    protected $signature = 'mary:import';

    protected $description = 'Command description';

    protected $files;

    /** Create a new command instance. */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    public function handle(): void
    {

        foreach (File::allFiles(base_path('vendor/robsontenorio/mary/src/View/Components')) as $file) {
            $name = $file->getBasename('.php');
            $filename = Str::of($name)->kebab();
            $path = $this->getSourceFilePath($name);
            $contents = $this->getSourceFile($name);

            if ( ! $this->files->exists($path)) {
                $this->files->put($path, $contents);
                $this->info("File : {$path} created");
            } else {
                $this->info("File : {$path} already exits");
            }

        }
    }

    /**
     * Get the full path of generate class
     *
     * @return string
     */
    public function getSourceFilePath($name)
    {
        return app_path('View/Components').'/'.$name.'.php';
    }

    public function getSourceFile($name)
    {
        return $this->getStubContents($this->getStubPath(), $this->getStubVariables($name));
    }

    public function getStubContents($stub, $stubVariables = [])
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace) {
            $contents = str_replace('$'.$search.'$', $replace, $contents);
        }

        return $contents;

    }

    public function getStubPath()
    {
        return __DIR__.'/../../../stubs/mary.stub';
    }

    public function getStubVariables($name)
    {
        return [
            'name' => $name,
        ];
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeRepository extends Command
{
    protected $signature = 'make:repository {name}';
    protected $description = 'Create Repository with Interface and bind it';

    public function handle()
    {
        $name = $this->argument('name');

        $name = str_replace('\\', '/', $name);

        $segments = explode('/', $name);
        $className = array_pop($segments);
        $subPath = implode('/', $segments);

        $interfaceNamespace = 'App\\Repositories\\Contracts';
        $repositoryNamespace = 'App\\Repositories\\Eloquents';

        if ($subPath) {
            $interfaceNamespace .= '\\' . str_replace('/', '\\', $subPath);
            $repositoryNamespace .= '\\' . str_replace('/', '\\', $subPath);
        }

        $interfaceDir = app_path("Repositories/Contracts/{$subPath}");
        $repositoryDir = app_path("Repositories/Eloquents/{$subPath}");

        File::ensureDirectoryExists($interfaceDir);
        File::ensureDirectoryExists($repositoryDir);

        $interfacePath = "{$interfaceDir}/{$className}Interface.php";
        $repositoryPath = "{$repositoryDir}/{$className}.php";

        if (!File::exists($interfacePath)) {
            File::put($interfacePath, $this->interfaceTemplate($className, $interfaceNamespace));
            $this->info("Interface created.");
        }

        if (!File::exists($repositoryPath)) {
            File::put($repositoryPath, $this->repositoryTemplate($className, $repositoryNamespace, $interfaceNamespace));
            $this->info("Repository created.");
        }

        $this->appendBinding($className, $subPath);

        $this->info("Repository bound successfully.");
    }

    protected function interfaceTemplate($name, $namespace)
    {
        return <<<PHP
<?php

namespace {$namespace};

use App\Repositories\Contracts\BaseRepositoryInterface;

interface {$name}Interface extends BaseRepositoryInterface
{
    //
}
PHP;
    }

    protected function repositoryTemplate($name, $namespace, $interfaceNamespace)
    {
        return <<<PHP
<?php

namespace {$namespace};

use {$interfaceNamespace}\\{$name}Interface;
use App\Repositories\Eloquents\BaseRepository;

class {$name} extends BaseRepository implements {$name}Interface
{
    //
}
PHP;
    }

    protected function appendBinding($name, $subPath)
    {
        $providerPath = app_path('Providers/AppServiceProvider.php');
        $providerContent = File::get($providerPath);

        $contractNamespace = 'App\\Repositories\\Contracts';
        $eloquentNamespace = 'App\\Repositories\\Eloquents';

        if ($subPath) {
            $contractNamespace .= '\\' . str_replace('/', '\\', $subPath);
            $eloquentNamespace .= '\\' . str_replace('/', '\\', $subPath);
        }

        $interfaceClass = "{$name}Interface";
        $repositoryClass = $name;

        $contractUse = "use {$contractNamespace}\\{$interfaceClass};";
        $eloquentUse = "use {$eloquentNamespace}\\{$repositoryClass};";

        $bindingLine = "        \$this->app->bind({$interfaceClass}::class, {$repositoryClass}::class);";

        if (!str_contains($providerContent, $contractUse)) {
            $providerContent = preg_replace(
                '/namespace App\\\\Providers;/',
                "namespace App\\Providers;\n\n{$contractUse}\n{$eloquentUse}",
                $providerContent
            );
        }

        if (!str_contains($providerContent, $bindingLine)) {
            $providerContent = preg_replace(
                '/public function register\(\): void\s*{/',
                "public function register(): void\n    {\n{$bindingLine}",
                $providerContent
            );
        }

        File::put($providerPath, $providerContent);
    }
}
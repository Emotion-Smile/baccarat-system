<?php

namespace Tests\Feature;

use Illuminate\Console\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use KravanhEco\LaravelCmd\Console\MiddlewareMakeCommand;
use Orchestra\Testbench\TestCase;

class MiddlewareMakeCommandTest extends TestCase
{
    public function test_it_can_create_a_middleware()
    {
        Application::starting(function ($artisan) {
            $artisan->add(app(MiddlewareMakeCommand::class));
        });
        
        $filePath = 'Kravanh/Support/Middleware/EnsureValidClientId.php';
        
        $shouldOutputFilePath = app_path($filePath);
        
        if (File::exists($shouldOutputFilePath)) {
            unlink($shouldOutputFilePath);
        }

        $exitCode = Artisan::call('kravanh:middleware', [
            'name' => 'EnsureValidClientId'
        ]);
    
        $this->assertEquals(0, $exitCode);

        $this->assertStringContainsString('Middleware created successfully.', Artisan::output());

        $this->assertTrue(File::exists($shouldOutputFilePath));

        $contents = File::get($shouldOutputFilePath);

        $this->assertStringContainsString('namespace App\Kravanh\Support\Middleware;', $contents);
        
        $this->assertStringContainsString('class EnsureValidClientId', $contents);
    }
}

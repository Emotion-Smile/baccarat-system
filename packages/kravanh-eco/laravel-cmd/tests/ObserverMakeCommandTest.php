<?php

namespace Tests\Feature;

use Illuminate\Console\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use KravanhEco\LaravelCmd\Console\ObserverMakeCommand;
use Orchestra\Testbench\TestCase;

class ObserverMakeCommandTest extends TestCase
{
    public function test_it_can_create_a_observer()
    {
        Application::starting(function ($artisan) {
            $artisan->add(app(ObserverMakeCommand::class));
        });
        
        $filePath = 'Kravanh/Domain/Books/Observers/BookObserver.php';
        
        $shouldOutputFilePath = app_path($filePath);
        
        if (File::exists($shouldOutputFilePath)) {
            unlink($shouldOutputFilePath);
        }

        $exitCode = Artisan::call('kravanh:observer', [
            'name' => 'Books/Book'
        ]);
    
        $this->assertEquals(0, $exitCode);

        $this->assertStringContainsString('Observer created successfully.', Artisan::output());

        $this->assertTrue(File::exists($shouldOutputFilePath));

        $contents = File::get($shouldOutputFilePath);

        $this->assertStringContainsString('namespace App\Kravanh\Domain\Books\Observers;', $contents);
        
        $this->assertStringContainsString('class BookObserver', $contents);
    }
}

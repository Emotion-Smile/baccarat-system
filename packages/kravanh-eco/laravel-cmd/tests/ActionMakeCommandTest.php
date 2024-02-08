<?php

namespace Tests\Feature;

use Illuminate\Console\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use KravanhEco\LaravelCmd\Console\ActionMakeCommand;
use Orchestra\Testbench\TestCase;

class ActionMakeCommandTest extends TestCase
{
    public function test_it_can_create_an_action()
    {
        Application::starting(function ($artisan) {
            $artisan->add(app(ActionMakeCommand::class));
        });
        
        $filePath = 'Kravanh/Domain/Books/Actions/CreateBookAction.php';
        
        $shouldOutputFilePath = app_path($filePath);
        
        if (File::exists($shouldOutputFilePath)) {
            unlink($shouldOutputFilePath);
        }

        $exitCode = Artisan::call('kravanh:action', [
            'name' => 'Books/CreateBook'
        ]);
    
        $this->assertEquals(0, $exitCode);

        $this->assertStringContainsString('Action created successfully.', Artisan::output());

        $this->assertTrue(File::exists($shouldOutputFilePath));

        $contents = File::get($shouldOutputFilePath);

        $this->assertStringContainsString('namespace App\Kravanh\Domain\Books\Actions;', $contents);
        
        $this->assertStringContainsString('class CreateBookAction', $contents);
    }
}

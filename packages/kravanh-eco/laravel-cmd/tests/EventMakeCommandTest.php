<?php

namespace Tests\Feature;

use Illuminate\Console\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use KravanhEco\LaravelCmd\Console\EventMakeCommand;
use Orchestra\Testbench\TestCase;

class EventMakeCommandTest extends TestCase
{
    public function test_it_can_create_an_event()
    {
        Application::starting(function ($artisan) {
            $artisan->add(app(EventMakeCommand::class));
        });
        
        $filePath = 'Kravanh/Domain/Books/Events/BookSavingEvent.php';
        
        $shouldOutputFilePath = app_path($filePath);
        
        if (File::exists($shouldOutputFilePath)) {
            unlink($shouldOutputFilePath);
        }

        $exitCode = Artisan::call('kravanh:event', [
            'name' => 'Books/BookSaving'
        ]);
    
        $this->assertEquals(0, $exitCode);

        $this->assertStringContainsString('Event created successfully.', Artisan::output());

        $this->assertTrue(File::exists($shouldOutputFilePath));

        $contents = File::get($shouldOutputFilePath);

        $this->assertStringContainsString('namespace App\Kravanh\Domain\Books\Events;', $contents);
        
        $this->assertStringContainsString('class BookSavingEvent', $contents);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Console\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use KravanhEco\LaravelCmd\Console\SubscriberMakeCommand;
use Orchestra\Testbench\TestCase;

class SubscriberMakeCommandTest extends TestCase
{
    public function test_it_can_create_a_subscriber()
    {
        Application::starting(function ($artisan) {
            $artisan->add(app(SubscriberMakeCommand::class));
        });
        
        $filePath = 'Kravanh/Domain/Invoices/Subscribers/InvoiceSubscriber.php';
        
        $shouldOutputFilePath = app_path($filePath);
        
        if (File::exists($shouldOutputFilePath)) {
            unlink($shouldOutputFilePath);
        }

        $exitCode = Artisan::call('kravanh:subscriber', [
            'name' => 'Invoices/Invoice'
        ]);
    
        $this->assertEquals(0, $exitCode);

        $this->assertStringContainsString('Subscriber created successfully.', Artisan::output());

        $this->assertTrue(File::exists($shouldOutputFilePath));

        $contents = File::get($shouldOutputFilePath);

        $this->assertStringContainsString('namespace App\Kravanh\Domain\Invoices\Subscribers;', $contents);
        
        $this->assertStringContainsString('class InvoiceSubscriber', $contents);
    }
}

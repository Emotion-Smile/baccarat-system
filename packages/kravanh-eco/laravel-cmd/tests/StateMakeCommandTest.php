<?php

namespace Tests\Feature;

use Illuminate\Console\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use KravanhEco\LaravelCmd\Console\StateMakeCommand;
use Orchestra\Testbench\TestCase;

class StateMakeCommandTest extends TestCase
{
    public function test_it_can_create_a_state()
    {
        Application::starting(function ($artisan) {
            $artisan->add(app(StateMakeCommand::class));
        });
        
        $filePath = 'Kravanh/Domain/Invoices/States/InvoiceState.php';
        
        $shouldOutputFilePath = app_path($filePath);
        
        if (File::exists($shouldOutputFilePath)) {
            unlink($shouldOutputFilePath);
        }

        $exitCode = Artisan::call('kravanh:state', [
            'name' => 'Invoices/Invoice'
        ]);
    
        $this->assertEquals(0, $exitCode);

        $this->assertStringContainsString('State created successfully.', Artisan::output());

        $this->assertTrue(File::exists($shouldOutputFilePath));

        $contents = File::get($shouldOutputFilePath);

        $this->assertStringContainsString('namespace App\Kravanh\Domain\Invoices\States;', $contents);
        
        $this->assertStringContainsString('class InvoiceState extends State', $contents);
    }
}

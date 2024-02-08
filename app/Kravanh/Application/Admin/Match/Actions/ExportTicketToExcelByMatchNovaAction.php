<?php

namespace App\Kravanh\Application\Admin\Match\Actions;

use App\Kravanh\Application\Admin\Match\Exports\TicketsExport;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportTicketToExcelByMatchNovaAction extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Export To Excel';

    public $showOnTableRow = false;

    public function handle(ActionFields $fields, Collection $tickets)
    {
        $fileName = $fields->fileName . '.xlsx';

        $response = Excel::download(
            new TicketsExport($tickets),
            $fileName
        );

        if (!$response instanceof BinaryFileResponse || $response->isInvalid()) {
            return Action::danger(__('Resource could not be exported.'));
        }

        return Action::download(
            $this->getDownloadUrl($response->getFile()->getPathname(), $fileName),
            $fileName
        );
    }

    public function fields()
    {
        return [
            Text::make('File Name', 'fileName')
                ->rules([
                    'required',
                    'alpha_num'
                ]),
        ];
    }

    protected function getDownloadUrl(string $filePath, string $fileName): string
    {
        return URL::temporarySignedRoute('laravel-nova-excel.download', now()->addMinutes(1), [
            'path'     => encrypt($filePath),
            'filename' => $fileName,
        ]);
    }
}

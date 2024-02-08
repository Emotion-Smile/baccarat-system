<?php

namespace App\Kravanh\Application\Admin\User;

use App\Kravanh\Application\Admin\Resource;
use App\Kravanh\Application\Admin\User\Actions\SendMessageToMembersNovaAction;
use App\Kravanh\Domain\User\Models\Message as MessageModel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use OptimistDigital\NovaTranslatable\HandlesTranslatable;

class Message extends Resource
{
    use HandlesTranslatable;

    public static $model = MessageModel::class;

    public static $title = '';

    public static $search = ['message'];

    public function fields(Request $request): array
    {
        return [
            Trix::make('Message')
                ->rules([
                    'required'
                ])
                ->translatable(),

            Text::make('Message')
                ->onlyOnIndex()
                ->asHtml(),

            DateTime::make('Sent At')
                ->exceptOnForms(),

            DateTime::make('Created At')
                ->exceptOnForms(),
        ];
    }

    public function actions(Request $request): array
    {
        return [
            (new SendMessageToMembersNovaAction)
                ->confirmText('Are you sure you want to send this messsge?')
                ->confirmButtonText('Send')
                ->cancelButtonText('Cancel')
                ->showOnTableRow()
                ->canSee(fn() => ! (bool) $this->sent_at)
                ->canRun(fn() => ! (bool) $this->sent_at),
        ];
    }
}

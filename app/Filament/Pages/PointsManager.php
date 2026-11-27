<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Models\Wallet;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Radio;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class PointsManager extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationLabel = 'Points manager';
    protected static ?string $navigationGroup = 'Points';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.points-manager';

    public ?array $data = [];

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasRole('admin'), 403);
        $this->form->fill();
    }

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Account')
                    ->options(fn () => User::query()->orderBy('email')->pluck('email', 'id')->all())
                    ->searchable()
                    ->required(),
                Radio::make('action')
                    ->label('Operation')
                    ->options(['add' => 'Add points', 'deduct' => 'Deduct points'])
                    ->default('add')
                    ->required()
                    ->inline(),
                TextInput::make('amount')
                    ->label('Points')
                    ->numeric()
                    ->minValue(0.0001)
                    ->required(),
                Textarea::make('reason')
                    ->label('Reason')
                    ->maxLength(255)
                    ->required(),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        abort_unless(auth()->user()?->hasRole('admin'), 403);
        $state = $this->form->getState();

        $result = DB::transaction(function () use ($state) {
            $wallet = Wallet::where('user_id', $state['user_id'])
                ->where('active', 1)
                ->lockForUpdate()
                ->firstOrFail();

            $before = (float) $wallet->balance;
            $amount = round((float) $state['amount'], 4);
            $after = $state['action'] === 'add' ? $before + $amount : $before - $amount;

            if ($after < 0) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'data.amount' => 'The account does not have enough points.',
                ]);
            }

            $wallet->balance = $after;
            $wallet->save();

            DB::table('point_transactions')->insert([
                'user_id' => $wallet->user_id,
                'admin_id' => auth()->id(),
                'action' => $state['action'],
                'amount' => $amount,
                'balance_before' => $before,
                'balance_after' => $after,
                'reason' => $state['reason'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $after;
        });

        $this->form->fill();
        Notification::make()
            ->title('Points updated')
            ->body('New balance: ' . number_format($result, 4) . ' points')
            ->success()
            ->send();
    }
}

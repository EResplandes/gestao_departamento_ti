<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome Usuário')
                    ->placeholder('Digite o nome do usuário...')
                    ->required()
                    ->maxLength(100),
                forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->placeholder('Digite o email...')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('password')
                    ->label('Senha')
                    ->placeholder('Digite a senha...')
                    ->required()
                    ->maxLength(100)
                    ->password(),
                Forms\Components\TextInput::make('password_confirm')
                    ->label('Digite a senha novamente')
                    ->placeholder('Digite a senha...')
                    ->required()
                    ->maxLength(100)
                    ->password(),
                Forms\Components\Select::make('tecnico_id')
                    ->label('Técnico')
                    ->required()
                    ->columnSpan('full')
                    ->options(
                        ['Administrador', 'Convencional', 'Gerente', 'Técnico']
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo_usuario')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        switch ($state) {
                            case 'Ativo':
                                return '<span style="color: green;">' . htmlspecialchars($state) . '</span>';
                            case 'Desativado':
                                return '<span style="color: orange;">' . htmlspecialchars($state) . '</span>';
                            case 'Solucionado':
                                return '<span style="color: blue;">' . htmlspecialchars($state) . '</span>';
                            default:
                                return '<span>' . htmlspecialchars($state) . '</span>';
                        }
                    })
                    ->html()  // Permite HTML no valor da célula
                    ->searchable(),
            ])
            ->filters([
                Filter::make('name')
                    ->label('Nome'),
                Filter::make('email')
                    ->label('Email'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                // Metódo responsável por ativar ou desativar usuário
                Action::make('alterarStatusUsuario')
                    ->label(fn(User $record) => $record->status == 'Ativo' ? 'Desativar' : 'Ativar')
                    ->url(fn(User $record) => route('users.alterar-status', $record->id)) // Passa o ID do chamado na URL
                    ->requiresConfirmation()
                    ->color(fn(User $record) => $record->status == 'Ativo' ? 'danger' : 'success')
                    ->icon('heroicon-c-plus')
                    ->openUrlInNewTab(false),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function isNavigationVisible(): bool
    {
        $usuarioLogado = Auth::user();

        if ($usuarioLogado && $usuarioLogado->tipo_usuario == 'Administrador') {
            return Gate::allows('view-users');
        }

        // Retorna true se o usuário for administrador ou se a condição de permissão for satisfeita
        return true;
    }
}

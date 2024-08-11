<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChamadoResource\Pages;
use App\Models\Categoria;
use App\Models\Chamado;
use App\Models\Departamento;
use App\Models\User;
use App\Models\Status;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;

class ChamadoResource extends Resource
{
    protected static ?string $model = Chamado::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('solicitante')
                    ->label('Nome do Solicitante')
                    ->placeholder('Digite o nome do solicitante...')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('email')
                    ->label('E-mail')
                    ->placeholder('Digite o e-mail do solicitante...')
                    ->required()
                    ->maxLength(255),
                Grid::make(3)->schema([
                    Forms\Components\Select::make('departamento_id')
                        ->label('Departamento')
                        ->required()
                        ->options(
                            Departamento::all()->pluck('departamento', 'id')->toArray()
                        ),
                    Forms\Components\Select::make('categoria_id')
                        ->label('Categoria')
                        ->required()
                        ->options(
                            Categoria::all()->pluck('categoria', 'id')->toArray()
                        ),
                    Forms\Components\Select::make('tecnico_id')
                        ->label('Técnico')
                        ->required()
                        ->options(
                            User::whereIn('tipo_usuario', ['Administrador', 'Técnico'])
                                ->pluck('name', 'id')
                                ->toArray()
                        ),
                ]),
                Forms\Components\Textarea::make('descricao')
                    ->label('Descrição')
                    ->placeholder('Digite a descrição do chamado...')
                    ->required()
                    ->columnSpan('full'),
                FileUpload::make('anexo')
                    ->label('Arquivo')
                    ->acceptedFileTypes(['image/png', 'application/pdf', 'application/zip']) // Tipos de arquivos permitidos
                    ->maxSize(1024 * 10) // Tamanho máximo do arquivo em KB (10 MB)
                    ->directory('anexos') // Diretório para armazenar os arquivos
                    ->disk('public') // Disco de armazenamento
                    ->visibility('public'), // Visibilidade do arquivo
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status.status')
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        switch ($state) {
                            case 'Novo':
                                return '<span style="color: green;">' . htmlspecialchars($state) . '</span>';
                            case 'Em Andamento':
                                return '<span style="color: orange;">' . htmlspecialchars($state) . '</span>';
                            case 'Solucionado':
                                return '<span style="color: blue;">' . htmlspecialchars($state) . '</span>';
                            default:
                                return '<span>' . htmlspecialchars($state) . '</span>';
                        }
                    })
                    ->html(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dt. Abertura')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('solicitante')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('descricao')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('departamento.departamento')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tecnico.name')
                    ->sortable()
                    ->searchable()
            ])
            ->filters([
                Filter::make('status')
                    ->label('Status'),
                Filter::make('solicitante')
                    ->label('Solicitante'),
                Filter::make('descricao')
                    ->label('Descrição'),
                SelectFilter::make('departamento_id')
                    ->label('Departamento')
                    ->options([
                        Departamento::all()->pluck('departamento', 'id')->toArray()
                    ]),
                SelectFilter::make('status_id')
                    ->label('Status')
                    ->options([
                        Status::all()->pluck('status', 'id')->toArray()
                    ]),
                SelectFilter::make('tecnico_id')
                    ->label('Técnico')
                    ->options([
                        User::all()->pluck('name', 'id')->toArray()
                    ]),
            ])
            ->actions([

                // Metódo responsável por associar chamado a usuário
                Action::make('associarTecnico')
                    ->label('Associar')
                    ->url(fn(Chamado $record) => route('chamados.associar-tecnico', $record->id)) // Passa o ID do chamado na URL
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-c-plus')
                    ->openUrlInNewTab(false),


                // Metódo responsável por finalizar chamado
                Action::make('finalizarChamado')
                    ->label('Finalizar')
                    ->color('danger')
                    ->icon('heroicon-o-folder-arrow-down')
                    ->form([
                        Forms\Components\Textarea::make('observacao')
                            ->label('Observação')
                            ->required()
                            ->columnSpan('full'),
                    ]),
                // ->action(function (array $data, Post $record): void {
                //     $record->author()->associate($data['authorId']);
                //     $record->save();
                // })

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListChamados::route('/'),
            'create' => Pages\CreateChamado::route('/create'),
            'edit' => Pages\EditChamado::route('/{record}/edit'),
        ];
    }
}

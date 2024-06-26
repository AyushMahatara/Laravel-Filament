<?php

namespace App\Filament\Resources;

use App\Events\PromoteStudent;
use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Filament\Resources\StudentResource\RelationManagers\GuardiansRelationManager;
use App\Models\Certificate;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Components\Builder as ComponentsBuilder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;

use function Laravel\Prompts\table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Collection;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Personal Info')
                    ->description('Add Student Personal Information')
                    ->schema(
                        [
                            Wizard::make(
                                [
                                    Step::make('Personal Information')
                                        ->schema(
                                            [
                                                TextInput::make('name')
                                                    ->required()
                                                    ->minLength(5),
                                                TextInput::make('student_id'),
                                            ]
                                        )
                                        ->icon('heroicon-o-users'),
                                    Step::make('Address')
                                        ->schema(
                                            [
                                                TextInput::make('address_1')
                                                    ->label('Country')
                                                    ->minLength(3),
                                                TextInput::make('address_2')
                                                    ->label('street Address'),
                                            ]
                                        )
                                        ->icon('heroicon-o-home')
                                        ->description('enter correct address'),
                                    Step::make('Class')
                                        ->schema(
                                            [
                                                Select::make('standard_id')
                                                    ->required()->relationship('standard', 'name')->label('Class')
                                            ]
                                        )
                                        ->icon('heroicon-o-academic-cap'),

                                ]
                            )
                                ->skippable(),
                        ]
                    )
                    ->collapsible(),
                Section::make('Additional Info')
                    ->description('Some Information About Student')
                    ->schema(
                        [
                            Repeater::make('vitals')
                                ->schema(
                                    [
                                        Select::make('name')->options(config('sm_config.vitals'))
                                            ->required(),
                                        TextInput::make('value')
                                            ->required(),
                                    ]
                                )
                        ]
                    )
                    ->collapsible()
                    ->collapsed(),

                Section::make('Certificates')
                    ->description('Some Certificates of Student')
                    ->schema(
                        [
                            Repeater::make('certificates')
                                ->relationship('certificate')
                                ->schema(
                                    [
                                        Select::make('certificate_id')->options(Certificate::all()->pluck('name', 'id'))
                                            ->required()
                                            ->searchable(),
                                        TextInput::make('description')
                                            ->required(),
                                    ]
                                )
                        ]
                    )
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()
                    ->description(fn (Student $record): string => $record->address_1),
                TextColumn::make('address_1')
                    ->label('Country'),
                TextColumn::make('standard.name'),

            ])
            ->filters([
                Filter::make('Start')
                    ->query(fn (Builder $query): Builder => $query->where('standard_id', 1)),
                SelectFilter::make('standard_id')->options([
                    1 => 'standard 1',
                    3 => 'standard 3'
                ])
                    ->label('Select the class'),
                SelectFilter::make('All Standard')
                    ->relationship('standard', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ActionGroup::make(
                    [
                        Tables\Actions\Action::make('Promote')
                            ->action(function (Student $record) {
                                $record->standard_id = $record->standard_id + 1;
                                $record->save();
                            })
                            ->color('success')
                            ->requiresConfirmation(),

                        Tables\Actions\Action::make('Demote')
                            ->action(function (Student $record) {
                                if ($record->standard_id > 1) {
                                    $record->standard_id = $record->standard_id - 1;
                                    $record->save();
                                }
                            })
                            ->color('dangre')
                            ->requiresConfirmation(),


                    ]
                ),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('Promote All')
                        ->action(function (Collection $records) {
                            $records->each(function ($record) {
                                event(new PromoteStudent($record));
                            });
                        })
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            GuardiansRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class UserTable extends DataTableComponent
{
    protected $model = User::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setSortingEnabled();
        $this->setFiltersEnabled();
    }

    public function builder(): Builder
    {
        return User::query()
            ->with([
                'work_unit',
            ])->where('role', 'RESPONDENT');
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Tipe Unit Kerja')
                ->options(
                    array_merge(
                        [""             => "Semua"],
                        ["PELAKSANA"    => "Pelaksana"],
                        ["DARAT"        => "Darat"],
                        ["LAUT"         => "Laut"],
                        ["UDARA"        => "Udara"],
                        ["KERETA"       => "Kereta"],
                        ["BPSDMP(UP)"   => "BPSDMP(UP)"],
                    )
                )
                ->filter(function(Builder $builder, string $value) {
                    $builder->where('work_unit.category', $value);
                }),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make("Nama", "name")
                ->sortable(),
            Column::make("Username", "username")
                ->sortable(),
            Column::make("Email", "email")
                ->sortable(),
            Column::make("Telepom", "phone")
                ->sortable(),
            Column::make("WhatsApp", "whatsapp")
                ->sortable(),
            Column::make("Unit Kerja", "work_unit.name")
                ->sortable(),

            Column::make("Role", "role")
                ->hideIf(true)
                ->sortable(),
            Column::make("Id", "id")
                ->hideIf(true)
                ->sortable(),
        ];
    }
}

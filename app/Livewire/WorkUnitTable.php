<?php

namespace App\Livewire;

use App\Models\WorkUnit;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class WorkUnitTable extends DataTableComponent
{
    protected $model = WorkUnit::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder
    {
        return WorkUnit::query()
            ->whereNot('category','PUSAT');
    }

    public function columns(): array
    {
        return [
        
            Column::make("Id", "id")
                ->sortable()
                ->hideIf(true),
            Column::make("Nama", "name")
                ->sortable()
                ->searchable(),
            Column::make("Kategori", "category")
                ->sortable(),
            Column::make("Head name", "head_name")
                ->sortable(),
            Column::make("Telepon", "phone")
                ->sortable(),
            Column::make("Email", "email")
                ->sortable(),
        ];
    }
}

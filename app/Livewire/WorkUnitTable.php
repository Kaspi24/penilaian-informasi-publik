<?php

namespace App\Livewire;

use App\Models\WorkUnit;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

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
                    $builder->where('category', $value);
                }),
        ];
    }

    private function calculateRowNumber($rowIndex)
    {
        return $rowIndex + 1 + ($this->page - 1) * $this->perPage;
    }
    public function columns(): array
    {
        return [
            // DISPLAYED COLUMNS
            Column::make("Nama", "name")
                ->sortable()
                ->searchable(),
            Column::make("Kategori", "category")
                ->sortable(),
            // COLLAPSED COLUMNS
            // Column::make("Ditampilkan", "id")
            //     ->label(
            //         fn($row) => view('components.datatable.toggle-review-visibility')->withRow($row)
            //     ),
            Column::make("Detail Unit Kerja")
                ->label(
                    fn($row) => view('components.datatable.work-unit-details', compact('row'))
                )
                // ->view('components.datatable.work-unit-details', compact('row'))
                ->collapseAlways(),
            // Column::make("Telepon", "phone")
            //     ->view('components.datatable.work-unit-details')
            //     ->collapseAlways(),
            // Column::make("Email", "email")
            //     ->view('components.datatable.work-unit-details')
            //     ->collapseAlways(),
            // HIDDEN COLUMNS
            Column::make("Id", "id")
                ->hideIf(true),
            Column::make("Telepon", "phone")
                ->hideIf(true),
            Column::make("Email", "email")
                ->hideIf(true),
            Column::make("Nama Kepala", "head_name")
                ->hideIf(true),
        ];
    }
}

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
            ->with('user')
            ->whereNot('category','PUSAT');
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Tipe Unit Kerja')
                ->options([
                    ""             => "Semua",
                    "PELAKSANA"    => "Pelaksana",
                    "DARAT"        => "Darat",
                    "LAUT"         => "Laut",
                    "UDARA"        => "Udara",
                    "KERETA"       => "Kereta",
                    "BPSDMP(UP)"   => "BPSDMP(UP)"
                ])
                ->filter(function(Builder $builder, string $value) {
                    $builder->where('category', $value);
                }),
            SelectFilter::make('Status Responden')
                ->options([
                    ''      => 'Semua',
                    '=='    => 'Belum Mendaftar',
                    '>'     => 'Sudah Mendaftar'
                ])
                ->filter(function(Builder $builder, string $operator) {
                    $builder->has('user', $operator, 0);
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
            Column::make("Status Responden")
                ->label(
                    fn($row) => view('components.datatable.work-unit-respondent-status', compact('row'))
                ),
            Column::make("Nama", "name")
                ->sortable()
                ->searchable(),
            Column::make("Kategori", "category")
                ->sortable(),
            // COLLAPSED COLUMNS
            Column::make("Detail Unit Kerja dan Responden")
                ->label(
                    fn($row) => view('components.datatable.work-unit-details', compact('row'))
                )
                ->collapseAlways(),
            // HIDDEN COLUMNS
            Column::make("ID", "id")->hideIf(true),
            Column::make("Telepon", "phone")->hideIf(true),
            Column::make("Email", "email")->hideIf(true),
            Column::make("Kepala Unit Kerja", "head_name")->hideIf(true),
        ];
    }
}

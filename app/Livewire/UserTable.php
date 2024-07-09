<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\DB;
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
        // $this->setDebugEnabled();
    }

    public function builder(): Builder
    {
        return User::query()
            ->select('users.*')
            ->with([
                'work_unit',
                'score'
            ])->where('role', 'RESPONDENT');
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
                    $builder->where('work_unit.category', $value);
                }),
            SelectFilter::make('Status Penilaian')
                ->options([
                    0 => "Semua",
                    1 => "Belum Dikirimkan",
                    2 => "Menunggu Dinilai",
                    3 => "Sudah Dinilai",
                    4 => "Sudah Di-publish",
                ])
                ->filter(function(Builder $builder, string $value) {
                    switch ($value) {
                        case 1: $builder->where('score.is_done_filling', false); break;
                        case 2: $builder->where('score.is_done_filling', true)->where('score.is_done_scoring',false); break;
                        case 3: $builder->where('score.is_done_scoring', true)->where('score.is_published', false); break;
                        case 4: $builder->where('score.is_published', true); break;
                        default : $builder; break;
                    }
                }),
            SelectFilter::make('Kelengkapan Profil')
                ->options([
                    0 => "Semua",
                    1 => "Sudah Lengkap",
                    2 => "Belum Lengkap"
                ])
                ->filter(function(Builder $builder, string $value) {
                    switch ($value) {
                        case 1: 
                            $builder->whereNotNull('users.name')
                                ->whereNotNull('users.email')
                                ->whereNotNull('users.phone')
                                ->whereNotNull('users.whatsapp')
                                ->whereNotNull('work_unit.head_name')
                                ->whereNotNull('work_unit.email')
                                ->whereNotNull('work_unit.phone'); 
                            break;
                        case 2:
                            $builder->whereNull('users.name')
                                ->orWhere(function(Builder $query){
                                    $query->whereNull('users.phone')
                                        ->whereNull('users.email')
                                        ->whereNull('users.whatsapp')
                                        ->whereNull('work_unit.head_name')
                                        ->whereNull('work_unit.email')
                                        ->whereNull('work_unit.phone');
                                });
                            break;
                        default : $builder; break;
                    }
                }),
        ];
    }

    public function columns(): array
    {
        return [
            // DISPLAYED COLUMNS
            Column::make("Status Penilaian")
                ->label(
                    fn($row) => view('components.datatable.respondent-questionnaire-status', compact('row'))
                )
                ->sortable(),
            Column::make("Nama", "name")
                ->searchable()
                ->sortable(),
            Column::make("Username", "username")
                ->searchable()
                ->sortable(),
            Column::make("Email", "email")
                ->searchable()
                ->sortable(),
            Column::make("Telepon", "phone")
                ->searchable()
                ->sortable(),
            Column::make("WhatsApp", "whatsapp")
                ->searchable()
                ->sortable(),
            Column::make("Unit Kerja", "work_unit.name")
                ->searchable()
                ->sortable(),
            Column::make("Aksi",'id')
                ->label(
                    fn($row) => view('components.datatable.user-datatable-actions', compact('row'))
                ),
            Column::make("Foto Kartu Pegawai", "profile_picture")
                ->label(function($row){
                    return view('components.datatable.respondent-id-card',compact('row'));
                })
                ->collapseAlways(),
            // HIDDEN COLUMNS
            Column::make("hidden", "role")->hideIf(true),
            Column::make("hidden", "id")->hideIf(true),
            Column::make("hidden", "work_unit.head_name")->hideIf(true),
            Column::make("hidden", "work_unit.email")->hideIf(true),
            Column::make("hidden", "work_unit.phone")->hideIf(true),
            Column::make("hidden", "score.id")->hideIf(true),
            Column::make("hidden", "score.is_done_filling")->hideIf(true),
            Column::make("hidden", "score.is_done_scoring")->hideIf(true),
            Column::make("hidden", "score.is_published")->hideIf(true)
        ];
    }
}

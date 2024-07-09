<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Question;
use App\Models\RespondentScore;
use App\Models\WorkUnit;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class QuestionnaireTable extends DataTableComponent
{
    protected $model = User::class;

    protected $listeners = ['setJuryForAllWorkUnit','publishAllWorkUnit'];

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
            ->with([
                'work_unit',
                'score.jury',
                'answers.children'
            ])
            ->where('users.role', 'RESPONDENT');
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
        ];
    }

    public function bulkActions(): array
    {
        return [
            'setJury' => 'Tentukan Juri',
            'publish' => 'Publish Nilai',
        ];
    }

    public function setJury()
    {
        if (count($this->getSelected())) {
            $this->dispatch('setJuryForAll', [
                'work_units' => User::query()
                    ->select(
                        'work_units.name as work_unit_name',
                        'work_units.id as unit_id'
                    )
                    ->leftJoin('work_units', 'users.work_unit_id', '=', 'work_units.id')
                    ->whereIn('users.id',$this->getSelected())
                    ->pluck('work_unit_name','unit_id'),
                'users' => $this->getSelected(),
                'total' => count($this->getSelected())
            ]);
        }
        $this->clearSelected();
    }

    public function setJuryForAllWorkUnit($id_arr, $jury_id)
    {
        RespondentScore::whereIn('respondent_id', $id_arr)->update([
            'jury_id' => $jury_id
        ]);
    }

    public function publish()
    {
        if (count($this->getSelected())) {
            $this->dispatch('publishAll', [
                'work_units' => User::query()
                    ->select(
                        'work_units.name as work_unit_name',
                        'work_units.id as unit_id'
                    )
                    ->leftJoin('work_units', 'users.work_unit_id', '=', 'work_units.id')
                    ->whereIn('users.id',$this->getSelected())
                    ->pluck('work_unit_name','unit_id'),
                'users' => $this->getSelected(),
                'total' => count($this->getSelected())
            ]);
        }
        $this->clearSelected();
    }

    public function publishAllWorkUnit($id_arr)
    {
        RespondentScore::whereIn('respondent_id', $id_arr)->update([
            'is_published' => true
        ]);
    }

    public function columns(): array
    {
        return [
            // DISPLAYED COLUMNS
            Column::make("Unit Kerja", "work_unit.name")
                ->searchable()
                ->sortable(),
            Column::make("Responden")
                ->label(function($row) {
                    if (!$row->name) {
                        return "<p class=\"w-40 text-center text-xs font-extrabold p-1 px-2 rounded-md uppercase bg-gray-200 text-gray-500\">BELUM DILENGKAPI</p>";
                    } else {
                        return $row->name;
                    }
                })
                ->html()
                ->sortable(),
            Column::make("Juri", "score.jury.name")
                ->label(function($row) {
                    if (!$row->score->jury_id) {
                        return "<p class=\"w-40 text-center text-xs font-extrabold p-1 px-2 rounded-md uppercase bg-yellow-200 text-yellow-500\">BELUM DITENTUKAN</p>";
                    } else {
                        return $row->score->jury->name;
                    }
                })
                ->html(),
            Column::make("Nilai akhir",'id')
                ->label(function($row) {
                    if (!$row->score->is_done_scoring) {
                        if($row->score->is_done_filling){
                            return "<p class=\"w-32 text-center text-xs font-extrabold p-1 px-2 rounded-md uppercase bg-red-200 text-red-800\">BELUM DINILAI</p>";
                        } else {
                            return "<p class=\"w-32 text-center text-xs font-extrabold p-1 px-2 rounded-md uppercase bg-yellow-200 text-yellow-500\">BELUM DIKIRIM</p>";
                        }
                    } else {
                        return $row->score->total_score;
                    }
                })
                ->html(),
            Column::make("Aksi", "id")
                ->label(fn($row) => view('components.datatable.respondent-datatable-actions', compact('row'))),
            // COLLAPSED COLUMNS
            Column::make("Detail Pengerjaan Penilaian")
                ->label(
                    fn($row) => view('components.datatable.questionnaire-progress-details', compact('row'))
                )
                ->collapseAlways(),
            // HIDDEN COLUMNS
            Column::make("hidden", "id")->hideIf(true),
            Column::make("hidden", "name")->hideIf(true),
            Column::make("hidden", "role")->hideIf(true),
            Column::make("hidden", "email")->hideIf(true),
            Column::make("hidden", "phone")->hideIf(true),
            Column::make("hidden", "whatsapp")->hideIf(true),
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

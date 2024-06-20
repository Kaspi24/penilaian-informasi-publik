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

    protected $listeners = ['setJuryForAllWorkUnit'];

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
                'score.jury',
                'answers.children'
            ])
            ->where('role', 'RESPONDENT');
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
        ];
    }

    public function bulkActions(): array
    {
        return [
            'setJury' => 'Tentukan Juri'
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
                        return "<p class=\"w-40 text-center text-xs font-extrabold p-1 px-2 rounded-md uppercase bg-gray-200 text-gray-500\">BELUM DITENTUKAN</p>";
                    } else {
                        return $row->score->jury->name;
                    }
                })
                ->html(),
            Column::make("Status Kuesioner")
                ->label(
                    fn($row) => view('components.datatable.respondent-questionnaire-status', compact('row'))
                ),
            // COLLAPSED COLUMNS
            Column::make("Detail Pengerjaan Kuesioner")
                ->label(
                    fn($row) => view('components.datatable.questionnaire-progress-details', compact('row'))
                )
                ->collapseAlways(),
            // HIDDEN COLUMNS
            Column::make("hide", "id")->hideIf(true),
            Column::make("hide", "name")->hideIf(true),
            Column::make("hide", "role")->hideIf(true),
            Column::make("hide", "email")->hideIf(true),
            Column::make("hide", "phone")->hideIf(true),
            Column::make("hide", "whatsapp")->hideIf(true),
            Column::make("hide", "work_unit.head_name")->hideIf(true),
            Column::make("hide", "work_unit.email")->hideIf(true),
            Column::make("hide", "work_unit.phone")->hideIf(true)
        ];
    }
}

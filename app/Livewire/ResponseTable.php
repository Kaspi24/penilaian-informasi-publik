<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\RespondentScore;

class ResponseTable extends DataTableComponent
{
    protected $model = RespondentScore::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Responden", "respondent_id")
                ->sortable(),
            Column::make("Juri", "jury_id")
                ->sortable(),
            Column::make("Is done filling", "is_done_filling")
                ->sortable(),
            Column::make("Is done scoring", "is_done_scoring")
                ->sortable(),
            Column::make("Total score", "total_score")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }
}

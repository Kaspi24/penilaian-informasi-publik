<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\RespondentScore;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class JuryTable extends DataTableComponent
{
    protected $model = User::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder
    {
        return User::query()
            ->with([
                'work_unit',
                'score'
            ])->where('role', 'JURY');
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
            Column::make("Jumlah Unit Kerja Selesai/Ditugaskan",'id')
                ->label(
                    fn($row) => view('components.datatable.jury-evaluation-progress', compact('row'))
                ),
            // Column::make("Aksi",'id')
            //     ->label(
            //         fn($row) => view('components.datatable.jury-datatable-actions', compact('row'))
            //     ),
            Column::make("Id", "id")->hideIf(true),
            Column::make("Phone", "phone")->hideIf(true),
            Column::make("Whatsapp", "whatsapp")->hideIf(true),
            Column::make("Work unit id", "work_unit_id")->hideIf(true),
            Column::make("Role", "role")->hideIf(true),
            Column::make("Profile picture", "profile_picture")->hideIf(true),
            Column::make("Created at", "created_at")->hideIf(true),
            Column::make("Updated at", "updated_at")->hideIf(true),
        ];
    }
}

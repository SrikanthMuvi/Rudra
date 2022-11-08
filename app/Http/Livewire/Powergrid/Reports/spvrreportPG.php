<?php

namespace App\Http\Livewire\Powergrid\Reports;

use App\Models\Tables\supervisorModel;
use Illuminate\Support\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent};

final class spvrreportPG extends PowerGridComponent
{
    use ActionButton;

    public $userVal = 'Operation Team';

    public bool $showUpdateMessages = true;
    public $index = 0;
    public $perPage=10;

    public string $sortField = 'id';
    public string $sortDirection = 'desc';
  	public string $primaryKey = 'supervisor.id';

    /*
    |--------------------------------------------------------------------------
    |  Features Setup
    |--------------------------------------------------------------------------
    | Setup Table's general features
    |
    */
    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    |  Datasource
    |--------------------------------------------------------------------------
    | Provides data to your Table using a Model or Collection
    |
    */

    /**
    * PowerGrid datasource.
    *
    * @return Builder<\App\Models\Tables\supervisorModel>
    */
    public function datasource(): Builder
    {
        return supervisorModel::query();
    }

    /*
    |--------------------------------------------------------------------------
    |  Relationship Search
    |--------------------------------------------------------------------------
    | Configure here relationships to be used by the Search and Table Filters.
    |
    */

    /**
     * Relationship search.
     *
     * @return array<string, array<int, string>>
     */
    public function relationSearch(): array
    {
		$setups = (array) $this->setUp["footer"];
        $this->perPage = $setups["perPage"];
        $this->index = $this->page > 1 ? ($this->page - 1) * $this->perPage : 0;
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    |  Add Column
    |--------------------------------------------------------------------------
    | Make Datasource fields available to be used as columns.
    | You can pass a closure to transform/modify the data.
    |
    | ❗ IMPORTANT: When using closures, you must escape any value coming from
    |    the database using the `e()` Laravel Helper function.
    |
    */
    public function addColumns(): PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('id')
            ->addColumn('row',fn () => ++$this->index)
            ->addColumn('name')
            ->addColumn('spvr_id')
            ->addColumn('userType', fn() => $this->userVal)
            ->addColumn('gender', fn (supervisorModel $model) => ucfirst($model->gender))
            ->addColumn('status', fn (supervisorModel $model) => ucfirst($model->status))
            ->addColumn('name_lower', fn (supervisorModel $model) => strtolower(e($model->name)))
            ->addColumn('created_at', fn (supervisorModel $model) => Carbon::parse($model->created_at)->format('d-m-Y h:i:s A'))
            ->addColumn('created_at_formatted', fn (supervisorModel $model) => Carbon::parse($model->created_at)->format('d-m-Y h:i:s A'));
    }

    /*
    |--------------------------------------------------------------------------
    |  Include Columns
    |--------------------------------------------------------------------------
    | Include the columns added columns, making them visible on the Table.
    | Each column can be configured with properties, filters, actions...
    |
    */

     /**
     * PowerGrid Columns.
     *
     * @return array<int, Column>
     */
    public function columns(): array
    {
        return [
            Column::make('S.No', 'id')
                ->field('row')
                ->searchable()
                ->visibleInExport(false),

            Column::make('Created at', 'created_at_formatted', 'created_at')
                ->makeInputDatePicker('created_at')
                ->searchable(),

            Column::make('RCS EMP ID', 'spvr_id')
                ->makeInputText('spvr_id')
                ->searchable('spvr_id'),
                //->sortable(),

            Column::make('User Type', 'userType')
                ->makeInputText('userType')
                ->searchable('userType'),
                //->sortable(),

            Column::make('Name', 'name')
                ->makeInputText('name')
                ->searchable('name'),
                //->sortable(),

            Column::make('Mobile', 'mobile')
                ->makeInputText('mobile')
                ->searchable('mobile'),
                //->sortable(),

            Column::make('Email', 'email')
                ->makeInputText('email')
                ->searchable('email'),
                //->sortable(),
            Column::make('Gender', 'gender')
                ->makeInputText('gender')
                ->searchable('gender'),
                //->sortable(),
            Column::make('Status', 'status')
                ->makeInputText('status')
                ->searchable('status'),

            Column::make('Area', 'area')
                ->makeInputText('area')
                ->searchable('area'),
            Column::make('City', 'city')
                ->makeInputText('city')
                ->searchable('city'),

            Column::make('State', 'state')
                ->makeInputText('state')
                ->searchable('state'),

            Column::make('Address', 'address')
                ->makeInputText('address')
                ->searchable('address'),

            Column::make('Pin', 'pin')
                ->makeInputText('pin')
                ->searchable('pin'),

            Column::make('Adhaar No', 'adhaarno')
                ->makeInputText('adhaarno')
                ->searchable('adhaarno'),

            Column::make('Pan No', 'panno')
                ->makeInputText('panno')
                ->searchable('panno'),

            Column::make('Created at', 'created_at')
                ->hidden(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    | Enable the method below only if the Routes below are defined in your app.
    |
    */

     /**
     * PowerGrid supervisorModel Action Buttons.
     *
     * @return array<int, Button>
     */

    /*
    public function actions(): array
    {
       return [
           Button::make('edit', 'Edit')
               ->class('bg-indigo-500 cursor-pointer text-white px-3 py-2.5 m-1 rounded text-sm')
               ->route('supervisor-model.edit', ['supervisor-model' => 'id']),

           Button::make('destroy', 'Delete')
               ->class('bg-red-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
               ->route('supervisor-model.destroy', ['supervisor-model' => 'id'])
               ->method('delete')
        ];
    }
    */

    /*
    |--------------------------------------------------------------------------
    | Actions Rules
    |--------------------------------------------------------------------------
    | Enable the method below to configure Rules for your Table and Action Buttons.
    |
    */

     /**
     * PowerGrid supervisorModel Action Rules.
     *
     * @return array<int, RuleActions>
     */

    /*
    public function actionRules(): array
    {
       return [

           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($supervisor-model) => $supervisor-model->id === 1)
                ->hide(),
        ];
    }
    */
}

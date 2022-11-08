<?php

namespace App\Http\Livewire\Powergrid;

use DB;
use App\Models\Tables\payslip;
use Illuminate\Support\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent};

final class payslipPG extends PowerGridComponent
{
    use ActionButton;

    public bool $showUpdateMessages = true;
    public $index = 0;
    public $perPage=10;

    public string $sortField = 'id';
    public string $sortDirection = 'desc';
    public string $primaryKey = 'payslips.id';

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
    * @return Builder<\App\Models\Tables\payslip>
    */
    public function datasource(): Builder
    {
        return payslip::query();
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
    */
    public function addColumns(): PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('id')
            ->addColumn('row',fn () => ++$this->index)
            ->addColumn('name')
            ->addColumn('created_at')
            
            ->addColumn('logout_at_formatted', fn (payslip $model) => Carbon::parse($model->logout)->format('d/m/Y h:i:s A'))
            ->addColumn('created_at_formatted', fn (payslip $model) => Carbon::parse($model->login)->format('d/m/Y h:i:s A'));
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
				//->makeInputText()
				->visibleInExport(false),
                //->sortable(),

            Column::make('Month & Year', 'monthandyear')
                ->searchable()
                ->makeInputText(),

            Column::make('Employee Name', 'name')
                ->searchable()
                ->sortable()
                ->makeInputText(),

            Column::make('Employee Id', 'empid')
                ->searchable()
                ->sortable()
                ->makeInputText(),

            Column::make('Designation', 'designation')
                ->searchable()
                ->sortable()
                ->makeInputText(),

            Column::make('Client Name', 'clientname')
                ->searchable()
                ->sortable()
                ->makeInputText(),

            Column::make('Net Pay', 'net_pay')
                ->searchable()
                ->sortable()
                ->makeInputText(),

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
     * PowerGrid payslip Action Buttons.
     *
     * @return array<int, Button>
     */

    // /*
    public function actions(): array
    {
       return [

            //Button::make('edit', '<i class="fa fa-pencil"></i>')
            //   ->class('text-primary bg-white border-0 text-center mx-auto button-edit')
            //    ->emit('editPayslip', ['ps_id'=>'id']),

            Button::make('destroy', '<i class="fa fa-times"></i>')
               ->class('text-danger border-0 text-center mx-auto')
               ->emit('deletePayslip', ['ps_id' => 'id']),
            
            Button::make('download', '<i class="fa fa-download"></i>')
               ->class('text-primary border-0 text-center mx-auto')
               ->emit('downloadPayslip', ['ps_id' => 'id'])
        ];
    }
    // */

    /*
    |--------------------------------------------------------------------------
    | Actions Rules
    |--------------------------------------------------------------------------
    | Enable the method below to configure Rules for your Table and Action Buttons.
    |
    */

     /**
     * PowerGrid payslip Action Rules.
     *
     * @return array<int, RuleActions>
     */

    /*
    public function actionRules(): array
    {
       return [

           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($security-model) => $security-model->id === 1)
                ->hide(),
        ];
    }
    */
}

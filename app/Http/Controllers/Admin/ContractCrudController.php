<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ContractRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ContractCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ContractCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Contract::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/contract');
        CRUD::setEntityNameStrings('contract', 'contracts');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setModel(\App\Models\Contract::class);

        CRUD::column('id')->label('ID');
        CRUD::column('job_id')->label('Job');
        CRUD::column('user.name')->label('Freelancer Name');
        CRUD::column('agreed_amount')->label('Agreed Amount');
        CRUD::column('start_date')->label('Project Start Date')
            ->type('datetime')
            ->format('DD MMM YYYY');
        // CRUD::column('end_date')->label('Project End Date')
        //     ->type('datetime')
        //     ->format('DD MMM YYYY');
        CRUD::column('status')->label('Status');
        CRUD::column('projectManager.name')->label('Project Manager Name');


        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ContractRequest::class);

        CRUD::field('job_id')
        ->type('select')
        ->entity('job')
        ->attribute('title') // Assuming the job title is stored in the 'title' column
        ->model('App\Models\Job');

        CRUD::field('user_id');
        CRUD::field('agreed_amount')->type('number');
        CRUD::field('start_date')->type('date');
        CRUD::field('end_date')->type('date');
        CRUD::field('status')->type('select_from_array')
            ->options(['active' => 'Active', 'completed' => 'Completed', 'cancelled' => 'Cancelled']);

        CRUD::field('project_manager_id');
        

    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}

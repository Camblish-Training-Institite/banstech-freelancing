<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MilestoneRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MilestoneCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MilestoneCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Milestone::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/milestone');
        CRUD::setEntityNameStrings('milestone', 'milestones');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setModel(\App\Models\Milestone::class);

        CRUD::column('id')->label('ID');
        CRUD::column('project.job.title')->label('Job Title');
        CRUD::column('title')->label('Milestone Title');
        CRUD::column('amount')->label('Amount');
        CRUD::column('due_date')->label('Due Date');
        CRUD::column('created_at')
        ->lable('Created')
        ->type('datetime')
        ->format('DD MMM YYYY, HH:mm');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(MilestoneRequest::class);

        CRUD::field('title')->type('text');
        CRUD::field('description')->type('textarea');
        CRUD::field('amount')->type('number');
        CRUD::field('status')->type('select_from_array')
            ->options(['pending' => 'pending', 'requested' => 'requested', 'approved' => 'approved', 'released' => 'released']);

        CRUD::field('due_date')->type('date');

        CRUD::field('project_id')
        ->type('select')
        ->label('Project')
        ->entity('project')
        ->attribute('id') // Now tell Backpack to display job.name from the model
        ->placeholder('Select a Project...');

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

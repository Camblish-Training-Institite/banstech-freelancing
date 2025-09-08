<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ContestSubmissionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ContestSubmissionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ContestSubmissionCrudController extends CrudController
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
        CRUD::setModel(\App\Models\ContestSubmission::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/contest-submission');
        CRUD::setEntityNameStrings('contest submission', 'contest submissions');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setModel(\App\Models\ContestSubmission::class);

        CRUD::column('id')->label('ID');
        CRUD::column('freelancer.name')->label('Freelancer Name');
        CRUD::column('contest.title')->label('Contest Title');
        CRUD::column('created_at')->label('Submitted at')
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
        CRUD::setValidation(ContestSubmissionRequest::class);
        
        CRUD::field('freelancer_id')->placeholder('Select User...');

        CRUD::field('contest_id')
        // ->type('select')
        // ->entity('contest')
        // ->attribtue('id')
        ->placeholder('Select Contest...');

        CRUD::field('title')->type('text');
        CRUD::field('description')->type('textarea');
        CRUD::field('file_path')->type('text');
        // CRUD::field('status')->type('select_from_array')
        // ->options(['pending' => 'pending', 'accepted' => 'accepted', 'rejected' => 'rejected']);
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

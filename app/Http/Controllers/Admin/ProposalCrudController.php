<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProposalRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProposalCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProposalCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Proposal::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/proposal');
        CRUD::setEntityNameStrings('proposal', 'proposals');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setModel(\App\Models\Proposal::class);

        CRUD::column('id')->label('ID');
        CRUD::column('user.name')->label('Freelancer Name');
        CRUD::column('job.title')->label('Job Title');
        CRUD::column('bid_amount')->label('Bid Amount');
        CRUD::column('status')->label('Status');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ProposalRequest::class);
        
        CRUD::field('user_id')->placeholder('Select User...');

        CRUD::field('job_id')
        // ->type('select')
        // ->entity('job')
        // ->attribtue('id')
        ->placeholder('Select Job...');

        CRUD::field('bid_amount')->type('number');
        CRUD::field('cover_letter')->type('textarea');
        CRUD::field('status')->type('select_from_array')
        ->options(['pending' => 'pending', 'accepted' => 'accepted', 'rejected' => 'rejected']);
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

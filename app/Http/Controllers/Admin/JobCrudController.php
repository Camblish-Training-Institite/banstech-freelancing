<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\JobRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class JobCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class JobCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Job::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/job');
        CRUD::setEntityNameStrings('job', 'jobs');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setModel(\App\Models\Job::class);

        // Define the columns you want to see
        CRUD::column('id')->label('ID');
        // This is the key part!
        // 'user' is the relationship method name in your Job model.
        // 'name' is the attribute on the User model.
        CRUD::column('user.name')->label('User Name');
        CRUD::column('title')->label('Title');

        CRUD::column('deadline')->label('Deadline')
            ->label('Deadline')
            ->type('datetime')
            ->format('DD MMM YYYY, HH:mm');
        CRUD::column('created_at')->label('Created At');

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
        CRUD::setValidation(JobRequest::class);

        CRUD::field('title')->type('text');
        CRUD::field('description')->type('textarea');
        CRUD::field('budget')->type('number');
        CRUD::field('status')->type('select_from_array')
            ->options(['pending' => 'Pending', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'open' => 'Open']);

        /*->type('select_from_array')
            ->options(['freelancer-client' => 'freelancer-client', 'admin' => 'admin', 'project-manager' => 'project-manager'])
            ->default('freelancer-client');*/
        CRUD::field('deadline')->type('datetime');

        // Configure the user field as a dropdown
        // CRUD::field('user_id') // The foreign key in the jobs table
        //     ->type('select_from_array')
        //     ->label('User')
        //     ->entity('user') // The relationship name in the Job model
        //     ->attribute('name') // The attribute to display (e.g., "John Doe")
        //     ->model("App\Models\User"); // The User model

        // Optional: Add placeholder text
        CRUD::field('user_id')->placeholder('Select a user...');

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
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

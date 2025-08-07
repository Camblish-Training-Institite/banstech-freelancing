<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
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
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'users');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('id')->label('ID');
        CRUD::column('name')->label('Name');
        CRUD::column('email')->label('Email');
        CRUD::column('user_type')->label('User Type'); // Show user_type
        // Display the custom attribute from the model
        CRUD::column('role_names')->label('Roles');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(UserRequest::class);
        
        CRUD::field('name')->label('Name')->type('text');
        CRUD::field('email')->label('Email')->type('email');
        CRUD::field('password')->label('Password')->type('password')
            ->attributes(['placeholder' => 'Enter password (leave blank to keep current)'])
            ->hint('Leave blank to keep the current password on update.');
        CRUD::field('user_type')->label('User Type') // Add user_type field
            ->type('select_from_array')
            ->options(['freelancer-client' => 'freelancer-client', 'admin' => 'admin', 'project-manager' => 'project-manager'])
            ->default('freelancer-client');

        // For Spatie Roles
        CRUD::field('roles')
            ->type('checklist') // or 'select2_multiple'
            ->name('roles') // The db column for the relationship
            ->entity('roles') // The relationship function on your User model
            ->model("Spatie\\Permission\\Models\\Role") // The related model
            ->attribute('name') // The column to show from the related model
            ->pivot(true); // Is this a pivot table relationship? Yes, for Spatie

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

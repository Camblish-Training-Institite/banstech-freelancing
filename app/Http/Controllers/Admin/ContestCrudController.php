<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ContestRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ContestCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ContestCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Contest::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/contest');
        CRUD::setEntityNameStrings('contest', 'contests');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setModel(\App\Models\Contest::class);

        // Define the columns you want to see
        CRUD::column('id')->label('ID');
        // This is the key part!
        // 'user' is the relationship method name in your Job model.
        // 'name' is the attribute on the User model.
        CRUD::column('client.name')->label('Client Name');
        CRUD::column('title')->label('Title');

        CRUD::column('closing_date')->label('Closing Date')
            ->type('datetime')
            ->format('DD MMM YYYY, HH:mm');
        CRUD::column('status')->label('Status');
        CRUD::column('created_at')->label('Created At');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ContestRequest::class);
        
        CRUD::field('client_id')
        ->placeholder('Select client id');

        CRUD::field('title')->type('text');
        CRUD::field('description')->type('textarea');
        CRUD::field('prize_money')->type('number');
        CRUD::field('closing_date')->type('date');
        CRUD::field('status')->type('select_from_array')
            ->options(['open' => 'open', 'closed' => 'closed', 'completed' => 'completed']);
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

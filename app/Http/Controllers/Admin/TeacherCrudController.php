<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TeacherRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\User;
use App\Models\Userable;
/**
 * Class TeacherCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TeacherCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Teacher::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/teacher');
        CRUD::setEntityNameStrings('teacher', 'teachers');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // columns

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
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
        CRUD::setValidation(TeacherRequest::class);
        CRUD::setFromDb(); // fields
        CRUD::field('teacher_phone')
               ->type('text')
               ->label('Teacher Phone')
               ->afterField('name');
        CRUD::field('email')
               ->type('email')
               ->label('Teacher Email')
               ->afterField('teacher_phone');
        CRUD::field('dob')
              ->type('date')
              ->label('Date of Birth');
        CRUD::field('photo')
              ->type('base64_image')
              ->src(null)
              ->crop(true)
              ->filename(uniqid("_image"));
              $this->crud->replaceSaveActions([
                'name'=>'save_and_back',
                'name' => 'Save',
                'redirect'=> function($crud, $request, $itemId) {
                    $user = new User;
                    $user->name=$_POST['name'];
                    $user->phone=$_POST['teacher_phone'];
                    $user->email=$_POST['email'];
                    $password = strtoUpper(substr($_POST['name'],0,4));
                    $password = $password . substr($_POST['dob'],0,4);
                    $user->password = bcrypt($password);
                    $user->assignRole('teacher');
                    $user->save();
                    $userable=new Userable;
                    $userable->user_id=$user->id;
                    $userable->userable_id=$itemId;
                    $userable->userable_type="App\Models\Teacher";
                    $userable->save();
                    return $crud->route;
                },
            ]);
        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
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
        function get_phone($id){
            $userable=Userable::where('userable_id','=',$id)->get('user_id');
            $user=User::find($userable[0]['user_id']);
            $phone=$user['phone'];
            CRUD::field('student_phone')->default($user['phone']);
            CRUD::field('email')->default($user['email']);

        };
        get_phone(request()->route('id'));
        $this->crud->replaceSaveActions([
            'name'=>'save_and_back',
            'name' => 'Save',
            'redirect'=> function($crud, $request, $itemId) {
                $user = User::find($itemId);
                $user->phone=$_POST['teacher_phone'];
                $user->email=$_POST['email'];
                $user->save();
                return $crud->route;
            },
        ]);
    }
}

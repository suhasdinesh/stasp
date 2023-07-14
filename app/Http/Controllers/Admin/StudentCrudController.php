<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StudentRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\User;
use App\Models\Userable;
use App\Models\Student;
use App\Models\Classes as Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class StudentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StudentCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Student::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/student');
        CRUD::setEntityNameStrings('student', 'students');
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
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
        CRUD::column('course_id')
              ->label('Course')
              ->type('relationship')
              ->attribute('course_name');
        CRUD::column('p_g_address')->label("Parent/Guardian Address");
        CRUD::column('photo')->type('image');
        CRUD::column('p_g_name')->label("Parent/Guardian Name");
        CRUD::column('p_g_phone')->label("Parent/Guardian Phone");
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(StudentRequest::class);
        CRUD::setFromDb(); // fields
        CRUD::field('id')->label("Student ID");
        CRUD::field('student_phone')
              ->label('Student Phone')
              ->type('text')
              ->afterField('name');
        CRUD::field('email')
              ->label('Student Email')
              ->type('email')
              ->beforeField('photo');
        CRUD::field('course_id')
                ->type('select')
                ->model('App\Models\Course')
                ->entity('course')
                ->attribute('course_name')
                ->options((function ($query) {
                    return $query->orderBy('course_name', 'ASC')->get();
                }));
        CRUD::field('photo')
              ->type('base64_image')
              ->crop(true)
              ->src(null)
              ->filename(uniqid('image_'));
        CRUD::field('p_g_name')->label("Parent/Guardian Name");
        CRUD::field('p_g_phone')->label("Parent/Guardian Number");
        CRUD::field('p_g_address')->label("Parent/Guardian Address");
        $this->crud->replaceSaveActions([
            'name'=>'Save and Back',
            'redirect'=> function($crud, $request, $itemId) {
                $user = new User;
                $user->name=$_POST['name'];
                $user->phone=$_POST['student_phone'];
                $user->email=$_POST['email'];
                $password = strtoUpper(substr($_POST['name'],0,4));
                $password = $password . substr($_POST['dob'],0,4);
                $user->password = bcrypt($password);
                $user->assignRole('student');
                $user->save();
                $userable=new Userable;
                $userable->user_id=$user->id;
                $userable->userable_id=$_POST['id'];
                $userable->userable_type="App\Models\Student";
                $userable->save();
                return $crud->route;
            },
        ]);
        CRUD::field('Class')
              ->type('select')
              ->label('Assign the Current Class')
              ->model('App\Models\Classes')
              ->entity('classes')
              ->attribute('course_n')
              ->options(function($query){
                    return($query->join('course','course.id','=','class.course_id')->select('class.id',DB::raw("CONCAT(class.semester,' Sem ',course_name) as course_n"))->get());
                });
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
        CRUD::setFromDb(); // fields
        CRUD::field('id')->label("Student ID");
        CRUD::field('student_phone')
              ->label('Student Phone')
              ->type('text')
              ->afterField('name');
        CRUD::field('email')
              ->label('Student Email')
              ->type('email')
              ->beforeField('photo');
        CRUD::field('course_id')
                ->type('select')
                ->model('App\Models\Course')
                ->entity('course')
                ->attribute('course_name')
                ->options((function ($query) {
                    return $query->orderBy('course_name', 'ASC')->get();
                }));
        CRUD::field('photo')
              ->type('base64_image')
              ->crop(true)
              ->src(null)
              ->filename(uniqid('image_'));
        CRUD::field('p_g_name')->label("Parent/Guardian Name");
        CRUD::field('p_g_phone')->label("Parent/Guardian Number");
        CRUD::field('p_g_address')->label("Parent/Guardian Address");
        function get_phone($id){
            $userable=Userable::where('userable_id','=',$id)->get('user_id');
            $user=User::find($userable[0]['user_id']);
            $phone=$user['phone'];
            CRUD::field('student_phone')->default($user['phone']);
            CRUD::field('email')->default($user['email']);

        };
        get_phone(request()->route('id'));
        CRUD::field('class_id')
        ->type('select')
        ->label('Assign the Current Class')
        ->model('App\Models\Classes')
        ->entity('classes')
        ->attribute('course_n')
        ->options(function($query){
              return($query->join('course','course.id','=','class.course_id')->select('class.id',DB::raw("CONCAT(class.semester,' Sem ',course_name) as course_n"))->get());
        });
        
        $this->crud->replaceSaveActions([
            'name'=>'Save and Back',
            'redirect'=> function($crud, $request, $itemId) {
                $user_id = Student::find($itemId)->users;
                $user_id=$user_id[0]['id'];
                $user=User::find($user_id);
                $user->phone=$_POST['student_phone'];
                $user->email=$_POST['email'];
                $user->save();
                $student=Student::find($itemId);
                $student->class_id=$_POST['class_id'];
                $student->save();
                return $crud->route;
            },
        ]);
    }
}

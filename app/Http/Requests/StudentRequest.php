<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Student;
use App\Models\User;
class StudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {   
        $student = new Student();
        $student_table=$student->getTable();
        $user = new User();
        $user_table=$user->getTable();
        return [
            'id' => 'required',
            'name' => 'required|min:4|max:255',
            'course_id' => 'required',
            'university_reg_no' => 'required|unique:'.$student_table,
            'dob' => 'required',
            'address' => 'required',
            'p_g_name' => 'required',
            'p_g_address' => 'required',
            'p_g_phone' => 'required|numeric|min:10',
            'email' => 'required|unique:'.$user_table,
            'student_phone' => 'required|numeric|min:10',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
            'name' => 'Full Student Name',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}

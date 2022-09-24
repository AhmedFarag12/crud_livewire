<?php

namespace App\Http\Livewire;

use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;

class Crud extends Component
{
    public $selectData = true;
    public $createData = false;
    public $updateData = false;
    public $name, $email, $country;
    public $edit_name, $edit_email, $edit_country, $ids;
    public $total_students;
    public $search;


    use WithPagination;

    public function render()
    {
        if ($this->search != "") {
            $searchTerm = '%'.$this->search.'%';
            $student = Student::orderBy('id', 'DESC')->where('name','LIKE',$searchTerm)->paginate(5);

        }else{

            $this->total_students = Student::get();
            $student = Student::orderBy('id', 'DESC')->paginate(5);
         }   

          return view('livewire.crud', ['students' => $student])->layout('layouts.app');
    }

    public function showForm()
    {
        $this->createData = true;
        $this->selectData = false;
    }

    public function resetField()
    {
        $this->name = "";
        $this->email = "";
        $this->country = "";

        $this->edit_name  = "";
        $this->edit_email = "";
        $this->edit_country = "";
        $this->ids = "";
    }

    public function create()
    {
        $student = new Student();
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'country' => 'required'
        ]);

        $student->name = $this->name;
        $student->email = $this->email;
        $student->country = $this->country;
        $result = $student->save();
        $this->resetField();

        $this->selectData = true;
        $this->createData = false;
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $this->ids = $student->id;
        $this->edit_name = $student->name;
        $this->edit_email = $student->email;
        $this->edit_country = $student->country;

        $this->selectData = false;
        $this->updateData = true;
    }

    public function update($id)
    {
        $student = Student::findOrFail($id);
        // $this->validate([
        //     'edit_name' =>'required',
        //     'edit_email ' =>'required|email',
        //     'edit_country' =>'required'
        // ]);

        $student->name = $this->edit_name;
        $student->email = $this->edit_email;
        $student->country = $this->edit_country;
        $result = $student->save();
        $this->resetField();

        $this->selectData = true;
        $this->updateData = false;
    }

    public function delete($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();
    }
}

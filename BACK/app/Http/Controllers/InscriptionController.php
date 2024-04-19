<?php

namespace App\Http\Controllers;
use App\Models\Inscription;
use Illuminate\Http\Request;

class InscriptionController extends Controller
{
    //

    public function index(){
        $inscriptions= Inscription::query()
        ->select('id', 'name','email','phone','course')
        ->orderBy('id', 'desc')
        ->paginate(5);

        return view('inscriptions.index',[
            'inscriptions' => $inscriptions]);
    }

    public function create(){


        $inscriptions = Inscription::query()
        ->select(['id', 'name'])
        ->get();

    return view('inscriptions.create',[
        'inscriptions' => $inscriptions
    ]);
    // Article::create($validatedData);
    return view('inscriptions.create',[
        'inscriptions' => $inscriptions
    ]);

        return view('inscriptions.create');
    }



    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'name' => ['required'],
            'email' => ['required'],
            'phone' => ['required'],
            'course' => ['required'],
            'created_at' => ['required', 'date'],            
        ]);
     

        // $inscriptions = new Inscription();
        // $inscriptions-> name = $validatedData['name'];
        // $inscriptions-> email = $validatedData['email'];
        // $inscriptions-> phone = $validatedData['phone'];
        // $inscriptions-> course = $validatedData['course'];
        // $inscriptions-> created_at = $validatedData['created_at'];
        // $inscriptions->save();
       

        Inscription::create($validatedData);

         return redirect()->route('inscriptions.index')
            ->with('success','new user has been succesfully added');
    }
}

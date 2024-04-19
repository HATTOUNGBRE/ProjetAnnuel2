@extends('Layouts.app')
@section('content') 



<div class="relative m-10 overflow-x-auto shadow-md rounded-lg">
<table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400" >
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400" >
            <tr>
                <th scope="col" class="px-6 py-3">ID</th>
                <th scope="col" class="px-6 py-3">Name</th>
                <th scope="col" class="px-6 py-3">Email</th>
                <th scope="col" class="px-6 py-3">Phone</th>
                <th scope="col" class="px-6 py-3">Course</th>
                <!-- <th scope="col" class="px-6 py-3">🗑️</th> -->
            </tr>
        </thead>
        <tbody>
            @foreach($inscriptions as $inscription)
                <tr class="bg-white border-b dark:bg-gray-400 dark:border-gray-700">
                    <td scope="row" class="px-6 py-3 font-medium text-white whitespace-nowrap ">{{ $inscription->id }}</td>
                    <td scope="row" class="px-6 py-3 font-medium text-white whitespace-nowrap ">{{ $inscription->name }}</td>
                    <td scope="row" class="px-6 py-3 font-medium text-white whitespace-nowrap ">{{ $inscription->email }}</td>
                    <td scope="row" class="px-6 py-3 font-medium text-white whitespace-nowrap ">{{ $inscription->phone }}</td>
                    <td scope="row" class="px-6 py-3 font-medium text-white whitespace-nowrap ">{{ $inscription->course }}</td>

                   
                </tr>
            @endforeach
       
    </table> </tbody> {{$inscriptions -> links() }}
    @endsection
</div>


   

@extends('layouts.app')

@section('content') 
<h1 class="uppercase">Bonjour</h1>
<form action="{{route('inscriptions.store')}}" method="POST">

    @csrf
    <div class="mb-6 mx-[10%]">
        <div class="mt-5">
            <h1 class="uppercase text-3xl mb-10">Nouvel Utilisateur</h1>
            <label for="title">Nom</label>
            <input class="bg-gray-300 border border-gray-300 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-200 dark:border-gray-600 dark:placeholder-gray-200  " type="text" name="name" id="">


        </div>

        <div class="mt-5">
            <label for="content" for="name" class="block mb-2 text-sm font-medium text-gray-900 ">Email</label>
            <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-200 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" type="textarea" name="email" id="">

            
        </div>
        <div class="mt-5">
            <label for="content" for="name" class="block mb-2 text-sm font-medium text-gray-900 ">Phone</label>
            <input class=" border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-200 dark:border-gray-600 placeholder-gray-400  focus:ring-blue-500 dark:focus:border-blue-500" type="textarea" name="phone" id="">

            
        </div>
        <div class="mt-5">
            <label for="content" for="name" class="block mb-2 text-sm font-medium text-gray-900 ">Course</label>
            <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-200 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" type="textarea" name="course" id="">

            
        </div>
        <div class="mt-5">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 " for="published_at">date de publication</label>
            <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-200 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" type="datetime-local" name="created_at" id="">

        </div>

        <div class="mt-5">
            <label  for="name" class="block mb-2 text-sm font-medium text-gray-900 " for=""></label>
          
        </div>

        <div class="mt-5 flex justify-center w-full">
            <button type="submit" class=" uppercase text-dark !bg-blue-300 !hover:bg-blue-700 w-[30%] font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                Submit
            </button>
        </div>
    </div>
    </form>
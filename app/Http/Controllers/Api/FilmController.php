<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\Film;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class FilmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        return Film::paginate($perPage);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = $this->doValidation();

        // if not valid
        if($validation !== true) {
            return $validation;
        }

        try
        {
            $film = Film::create($request->all());
            return response()->json(['success' => true, 'film' => $film]);
        }
        catch(Exception $e)
        {
            return response()->json(['success' => false, 'errors' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $film = Film::find($id);
        $film->photo = Storage::disk('public')->url($film->photo);
        if(!$film) return response()->json(['success' => false, 'errors' => 'Invalid resource'], 404);

        return $film;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validation = $this->doValidation();

        // if not valid
        if($validation !== true) {
            return $validation;
        }

        $film = Film::find($id);
        if(!$film) return response()->json(['success' => false, 'errors' => 'Invalid resource'], 404);

        try
        {
            if($request->hasFile('photo')) {
                $extension = $request->file('photo')->extension();
                $path = $request->file('photo')->storeAs('photos/films', $film->id.".".$extension, 'public');
                $film->photo = $path;
            }
            $film->fill($request->input())->save();
            return response()->json(['success' => true, 'film' => $film]);
        }
        catch(Exception $e)
        {
            return response()->json(['success' => false, 'errors' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $film = Film::find($id);
        if(!$film) return response()->json(['success' => false, 'errors' => 'Invalid resource'], 404);

        try
        {
            $film->delete();
            return response()->json(['success' => true]);
        }
        catch(Exception $e)
        {
            return response()->json(['success' => false, 'errors' => $e->getMessage()]);
        }
    }

    /**
     * Perform validation.
     *
     * @return \Illuminate\Http\Response or boolean
     */
    private function doValidation()
    {
        $rules = $this->getValidationRules();
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false]);
        }
        return true;
    }

    /**
     * Set validation rules.
     *
     * @return array
     */
    protected function getValidationRules()
    {
        // validate
        return $rules = array(
            'name'          => 'required',
            'description'   => 'required',
            'rating'        => 'required|numeric|min:1|max:5',
            'ticket_price'  => 'required|numeric',
            'country'       => 'required',
            'release_date'  => 'required|date_format:Y-m-d'
        );
    }
}

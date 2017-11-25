<?php

namespace App\Http\Controllers\Frontend;

use Validator;
use App\Models\Film;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FilmController extends Controller
{

	/**
     * Display the resources.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('films.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $film = Film::where('slug', $slug)->with('comments.user')->first();

        if(!$film) {
        	abort(404, 'Not found');
        }

        return view('films.view', ['film' => $film]);
    }

    /**
     * Store a comment for resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storeComment(Request $request)
    {
        $validator = Validator::make($request->input(), ['body' => 'required', 'film' => 'required']);

        // process the login
        if ($validator->fails()) {
        	$request->session()->flash('message', 'Comment cannot be empty');
            return redirect()->back()->withInput();
        }

        // check if resource available
        $slug = $request->input('film');
        $film = Film::where('slug', $slug)->first();

        if(!$film) {
        	abort(404, 'Not found');
        }

        try 
        {
        	$film->comments()->create([
				'user_id' => $request->user()->id,
				'body' => $request->input('body')
			]);
			$request->session()->flash('message', 'Your comment has been added');
        }
        catch(Exception $e) 
        {
        	$request->session()->flash('message', $e->getMessage());
        }

        return redirect()->route('films.view', $film->slug);
    }
}

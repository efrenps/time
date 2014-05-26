<?php

class InfosController extends BaseController {

	/**
	 * Info Repository
	 *
	 * @var Info
	 */
	protected $info;

	public function __construct(Info $info)
	{
		$this->info = $info;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$infos = $this->info->all();

		return View::make('infos.index', compact('infos'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('infos.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
		$validation = Validator::make($input, Info::$rules);

		if ($validation->passes())
		{
			$this->info->create($input);

			return Redirect::route('infos.index');
		}

		return Redirect::route('infos.create')
			->withInput()
			->withErrors($validation)
			->with('message', 'There were validation errors.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$info = $this->info->findOrFail($id);

		return View::make('infos.show', compact('info'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$info = $this->info->find($id);

		if (is_null($info))
		{
			return Redirect::route('infos.index');
		}

		return View::make('infos.edit', compact('info'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$input = array_except(Input::all(), '_method');
		$validation = Validator::make($input, Info::$rules);

		if ($validation->passes())
		{
			$info = $this->info->find($id);
			$info->update($input);

			return Redirect::route('infos.show', $id);
		}

		return Redirect::route('infos.edit', $id)
			->withInput()
			->withErrors($validation)
			->with('message', 'There were validation errors.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->info->find($id)->delete();

		return Redirect::route('infos.index');
	}

}

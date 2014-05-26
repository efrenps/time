<?php

class EmployeesAttendancesController extends BaseController {

	/**
	 * EmployeesAttendance Repository
	 *
	 * @var EmployeesAttendance
	 */
	protected $EmployeesAttendance;

	public function __construct(EmployeesAttendance $EmployeesAttendance)
	{
		$this->EmployeesAttendance = $EmployeesAttendance;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$EmployeesAttendances = $this->EmployeesAttendance->all();

		return View::make('EmployeesAttendances.index', compact('EmployeesAttendances'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('EmployeesAttendances.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
		$validation = Validator::make($input, EmployeesAttendance::$rules);

		if ($validation->passes())
		{
			$this->EmployeesAttendance->create($input);

			return Redirect::route('EmployeesAttendances.index');
		}

		return Redirect::route('EmployeesAttendances.create')
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
		$EmployeesAttendance = $this->EmployeesAttendance->findOrFail($id);

		return View::make('EmployeesAttendances.show', compact('EmployeesAttendance'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$EmployeesAttendance = $this->EmployeesAttendance->find($id);

		if (is_null($EmployeesAttendance))
		{
			return Redirect::route('EmployeesAttendances.index');
		}

		return View::make('EmployeesAttendances.edit', compact('EmployeesAttendance'));
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
		$validation = Validator::make($input, EmployeesAttendance::$rules);

		if ($validation->passes())
		{
			$EmployeesAttendance = $this->EmployeesAttendance->find($id);
			$EmployeesAttendance->update($input);

			return Redirect::route('EmployeesAttendances.show', $id);
		}

		return Redirect::route('EmployeesAttendances.edit', $id)
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
		$this->EmployeesAttendance->find($id)->delete();

		return Redirect::route('EmployeesAttendances.index');
	}

}

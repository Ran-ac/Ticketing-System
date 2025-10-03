<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\User;
use Illuminate\Http\Request;

class ClinicController extends Controller
{

// For GAOC
public function fetchGaocClinicsData(Request $request)
{
    return $this->fetchClinicsDataByCompany($request, 'gaoc');
}

// For Novodental
public function fetchNovodentalClinicsData(Request $request)
{
    return $this->fetchClinicsDataByCompany($request, 'novodental');
}

    // For JentleDerm
    public function fetchJentleDermClinicsData(Request $request)
    {
        return $this->fetchClinicsDataByCompany($request, 'jentlederm');
    }

// Shared method
private function fetchClinicsDataByCompany(Request $request, $company)
{
    $columns = ['id', 'company', 'name', 'contact_number', 'email', 'address', 'created_at'];

    $orderColumnIndex = $request->input('order.0.column', 0);
    $orderDir = $request->input('order.0.dir', 'asc');
    $searchValue = $request->input('search.value', '');
    $perPage = $request->input('length', 10);
    $start = $request->input('start', 0);

    $query = Clinic::query()
        ->select($columns)
        ->where('company', $company);

    if ($searchValue) {
        $query->where(function ($q) use ($searchValue) {
            $q->where('id', 'LIKE', "%{$searchValue}%")
              ->orWhere('name', 'LIKE', "%{$searchValue}%")
              ->orWhere('email', 'LIKE', "%{$searchValue}%")
              ->orWhere('address', 'LIKE', "%{$searchValue}%")
              ->orWhere('created_at', 'LIKE', "%{$searchValue}%")
              ->orWhere('contact_number', 'LIKE', "%{$searchValue}%");
        });
    }

    $query->orderBy($columns[$orderColumnIndex] ?? 'id', $orderDir);

    $recordsTotal = Clinic::where('company', $company)->count();
    $recordsFiltered = $query->count();

    $clinics = $query->skip($start)->take($perPage)->get();

    return response()->json([
        'draw' => intval($request->input('draw', 1)),
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data' => $clinics
    ]);
}

// public function error()
// {
//     // Optional: redirect or show a 404
//     return abort(404); // Or redirect somewhere
// }

    public function index ()
    {
       $clinic = Clinic::all();
            return view('admin.clinics.novodental.index', compact('clinic'));

    }

    public function gaocIndex() 
    {
        $clinic = Clinic::all();
            return view('admin.clinics.gaoc.index', compact('clinic'));
    }

    public function JentleDermIndex() 
    {
        $clinic = Clinic::all();
            return view('admin.clinics.jentlederm.index', compact('clinic'));
    }
    
    public function create()
    {
        return view('admin.clinics.gaoc.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
               // Validate input
        $request->validate([
            'company' => 'required',
            'name' => 'required|string|max:255|unique:clinics,name',
            'email' => 'required',
            'contact_number' => 'required',
            'address' => 'required',
        ],
        [
           'name.unique' => 'The clinic name already exists!' 
        ]);

        // Create the category
        $clinic = Clinic::create([
            'company' => $request->company,
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'contact_number' => $request->contact_number,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Clinic added successfully!',
            'dentist' => $clinic,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Clinic $clinic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $clinic = Clinic::find($id);

        return view('admin.clinics.gaoc.edit', compact('clinic'));
    }

    /**
     * Update the specified resource in storage.
     */
      public function update(Request $request)
    {
        $request->validate([
            'id'      => 'required|exists:clinics,id',
            'name'    => 'required',
            'address' => 'required',
            'contact_number' => 'required',
            'email' => 'required',
        ]);

        $clinic = Clinic::find($request->id);


        // Update procedure details
        $clinic->update([
            'name'           => $request->name,
            'address'        => $request->address,
            'contact_number' => $request->contact_number,
            'email'          => $request->email,

        ]);
    
        return response()->json([
            'success'   => true,
            'message'   => 'Clinic updated successfully',
            'category' => $clinic
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $clinic = Clinic::find($id);

        if (!$clinic) {
            return response()->json(['error' => 'Category not found'], 404);
        }
    
        $clinic->delete();

        return response()->json(['success' => 'Clinic deleted successfully!']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Clinic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;


use Illuminate\Http\Request;

class UserController extends Controller
{

    public function fetchUserData(Request $request)
    {

        $columns = ['id', 'name', 'email','address','contact_number','department','role','branch','created_at'];
    
        // Get sorting parameters
        $orderColumnIndex = $request->input('order.0.column', 10);
        $orderDir = $request->input('order.0.dir', 'asc');
        $searchValue = $request->input('search.value', '');
        $perPage = $request->input('length', 10);
        $start = $request->input('start', 0); // Start index for pagination 
    
        $query = User::query()->select($columns);
    
        // Apply search filter
        if ($searchValue) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('id', 'LIKE', "%{$searchValue}%")
                ->orWhere('name', 'LIKE', "%{$searchValue}%")
                ->orWhere('email', 'LIKE', "%{$searchValue}%")
                ->orWhere('department', 'LIKE', "%{$searchValue}%")
                ->orWhere('role', 'LIKE', "%{$searchValue}%")
                ->orWhere('created_at', 'LIKE', "%{$searchValue}%");
            });
        }

        // Apply sorting
        $query->orderBy($columns[$orderColumnIndex] ?? 'id', $orderDir);
    
        // Get total records count before applying pagination
        $recordsTotal = User::count();
        $recordsFiltered = $query->count();
    
        // Apply pagination using skip() and take()
        $user = $query->skip($start)->take($perPage)->get();
    
        // Return response in DataTables format
        return response()->json([
            'draw' => intval($request->input('draw', 1)),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $user
        ]);

    }


    public function index()
    {
        $users = User::with('clinic')->get();
        $clinics = Clinic::all();

        return view('admin.user.index', compact('users', 'clinics'));
    }


    public function create()
    {
        $clinics = Clinic::all();
        return view('admin.user.create', compact('clinics'));
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'department' => 'required',
            'contact_number' => 'required',
            'address' => 'required',
            'branch' => 'required', // this can be array
            'role' => 'required',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Convert branch array to comma-separated string
        $branchString = is_array($request->branch) ? implode(',', $request->branch) : $request->branch;

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'department' => $request->department,
            'contact_number' => $request->contact_number,
            'address' => $request->address,
            'branch' => $branchString,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User added successfully!',
            'user' => $user,
        ], 201);
    }


    public function show(cr $cr)
    {
        //
    }


public function edit(String $id)
{
    $user = User::find($id);

    if (!$user) {
        abort(404, 'User not found.');
    }

    $user = User::findOrFail($id);
    $clinics = Clinic::all(); // or however you're fetching it

    // Convert comma-separated string to array
    $user->branch = explode(',', $user->branch); // "1,2,3" => [1,2,3]

    return view('admin.user.edit', compact('user', 'clinics'));
}


    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'department' => 'required',
            'contact_number' => 'required',
            'address' => 'required',
            'branch' => 'required', // this can be array
            'role' => 'required',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        

        $user = User::find($request->id);

        $branchString = is_array($request->branch) ? implode(',', $request->branch) : $request->branch;


        // Update procedure details
        $user->update([
            'name'              => $request->name,
            'email'             => $request->email,
            'department'        => $request->department,
            'contact_number'    => $request->contact_number,
            'address'           => $request->address,
            'branch'            => $branchString,
            'role'              => $request->role,
            'password'          => Hash::make($request->password),


        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'User updated successfully',
            'category' => $user
        ]);
    }


    public function destroy(String $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Category not found'], 404);
        }
    
        $user->delete();

        return response()->json(['success' => 'User deleted successfully!']);
    }

public function getSignOut() {
		
	Auth::logout();
	return redirect('/');
	
}

}

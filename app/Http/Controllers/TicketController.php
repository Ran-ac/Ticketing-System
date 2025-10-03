<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Clinic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TicketController extends Controller
{

public function fetchTicketData(Request $request)
{
    $columns = [
        'tickets.id',
        'tickets.title',
        'tickets.description',
        'tickets.status',
        'tickets.priority',
        'users.name as user_id',
        'assigned.name as assigned_to',
        'clinics.name as branch_id',
        'tickets.department',
        'tickets.due_date',
        'tickets.resolved_at',
        'tickets.created_at'
    ];

    $orderColumnIndex = $request->input('order.0.column', 0);
    // $orderDir = $request->input('order.0.dir', 'asc');
    $searchValue = $request->input('search.value', '');
    $perPage = $request->input('length', 10);
    $start = $request->input('start', 0);

    // Join tables to get names directly
    $query = Ticket::select($columns)
        ->leftJoin('users', 'tickets.user_id', '=', 'users.id')
        ->leftJoin('users as assigned', 'tickets.assigned_to', '=', 'assigned.id')
        ->leftJoin('clinics', 'tickets.branch_id', '=', 'clinics.id');

    // Search
    if ($searchValue) {
        $query->where(function($q) use ($searchValue){
            $q->where('tickets.title','like',"%{$searchValue}%")
              ->orWhere('tickets.description','like',"%{$searchValue}%")
              ->orWhere('tickets.status','like',"%{$searchValue}%")
              ->orWhere('tickets.priority','like',"%{$searchValue}%")
              ->orWhere('tickets.department','like',"%{$searchValue}%")
              ->orWhere('users.name','like',"%{$searchValue}%")
              ->orWhere('assigned.name','like',"%{$searchValue}%")
              ->orWhere('clinics.name','like',"%{$searchValue}%");
        });
    }

    // Sorting
    $orderColumn = $columns[$orderColumnIndex] ?? 'tickets.id';
    $query->orderBy($orderColumn, 'desc');

    $recordsTotal = Ticket::count();
    $recordsFiltered = $query->count();

    // Pagination
    $ticket = $query->skip($start)->take($perPage)->get();

    return response()->json([
        'draw' => intval($request->input('draw',1)),
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data' => $ticket
    ]);
}



    public function index()
    {
        $ticket = Ticket::all();

        return view('admin.ticket.index', compact('ticket'));
    }


    public function create()
    {
        $ticket = Ticket::all();
        $clinic = Clinic::all();
        $user = User::all();

        return view('admin.ticket.create', compact('ticket', 'clinic','user'));
    }


    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'priority' => 'required',
            'user_id' => 'required',
            'assigned_user' => 'required', 
            'branch_id' => 'nullable|exists:clinics,id',
            'department' => 'required',
            'due_date' => 'required',
            'resolve_at' => 'required',

        ]);


        // Create Ticket
        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'user_id' => auth::id(),
            'assigned_to' => $request->assigned_user, 
            'branch_id' => $request->branch_id,
            'department' => $request->department,
            'due_date' => $request->due_date,
            'resolved_at' => $request->resolve_at,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ticket added successfully!',
            'ticket' => $ticket,
        ], 201);
    }


    public function show(Ticket $ticket)
    {
        //
    }



    public function edit(String $id)
    {
        $ticket = Ticket::find($id);
        $user = User::all();
        $clinic = Clinic::all();

        return view('admin.ticket.edit', compact('ticket', 'user','clinic'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

               // Validate input
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'status' => 'required',
            'priority' => 'required',
            'user_id' => 'required',
            'assigned_user' => 'required', 
            'branch_id' => 'nullable|exists:clinics,id',
            'department' => 'required',
            'due_date' => 'required',
            'resolve_at' => 'required',

        ]);



        $ticket = Ticket::find($request->id);
   


        // Create the user
        $ticket->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'priority' => $request->priority,
            'user_id' => auth::id(),
            'assigned_to' => $request->assigned_user, 
            'branch_id' => $request->branch_id,
            'department' => $request->department,
            'due_date' => $request->due_date,
            'resolved_at' => $request->resolve_at,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ticket added successfully!',
            'ticket' => $ticket,
        ], 201);
    }


    public function destroy(String $id)
    {

                $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found'], 404);
        }
    
        $ticket->delete();

        return response()->json(['success' => 'Ticket deleted successfully!']);
    }
    
}

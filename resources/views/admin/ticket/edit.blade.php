<form id="update_ticket" method="POST" action="{{ route('admin.ticket.update') }}" enctype="multipart/form-data">
    
        @csrf
         @method('PUT')

         <input type="hidden" name="id" value="{{ $ticket->id }}">


        <!-- Name -->
        <div class="mb-6">
            <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" name="title" value="{{ $ticket->title }}" class="form-control">
                    @error('title')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
        </div>

        <!-- Description-->
        <div class="mb-3">
            <label for="description" class="form-label">Description / Remarks</label>
           <textarea name="description" class="form-control" id="description">{{ $ticket->description }}</textarea>
            @error('description')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- status -->
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" name="status" id="status">
                <option value="">Please select status</option>
                <option value="new" {{ $ticket->status == 'new' ? 'selected' : '' }} >New</option>
                <option value="On going" {{ $ticket->status == 'On going' ? 'selected' : '' }} > On going</option>
                <option value="On hold" {{ $ticket->status == 'On hold' ? 'selected' : '' }} >On hold</option>
                <option value="On Pause" {{ $ticket->status == 'On Pause' ? 'selected' : '' }} >On Pause</option>
                <option value="Done" {{ $ticket->status == 'Done' ? 'selected' : '' }}>Done</option>
            </select>
            @error('status')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Priority Level -->
        <div class="mb-3">
            <label for="priority" class="form-label">Priority Level</label>
            <select class="form-control" name="priority" id="priority">
                <option value="">Please select priority level</option>
                <option value="Low" {{ $ticket->priority == 'Low' ? 'selected' : '' }} >Low</option>
                <option value="Middle" {{ $ticket->priority == 'Middle' ? 'selected' : '' }}>Middle</option>
                <option value="High"{{ $ticket->priority == 'High' ? 'selected' : '' }} >High</option>
            </select>
            @error('priority')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
        
        <!-- User  -->
        <input type="hidden" name="user_id" value="{{ Auth::id() }}">

        <!-- Assigned to -->
        <div class="mb-3">
            <label for="assigned_user" class="form-label">Assigned to: </label>
            <select class="form-control" name="assigned_user" id="assigned_user">
                <option value="">Please select IT assinged to:</option>
                @foreach ($user as $users)
                    <option value="{{ $users->id }}">{{ $users->name }}</option>
                        {{ $users->name }}
                    </option>
                @endforeach
            </select>

            @error('assigned_user')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Branch  -->
        <div class="mb-3">
            <label for="branch_id" class="form-label">Branch:</label>
            <select class="form-control" name="branch_id" id="branch_id">
                <option value="">Please select branch</option>
                @foreach ($clinic as $clinics)
                    <option value="{{ $clinics->id }}">{{ $clinics->name }}</option>
                        {{ $clinics->name }}
                    </option>
                @endforeach
            </select>

            @error('branch_id')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Department -->
        <div class="mb-3">
            <label for="department" class="form-label">Department</label>
            <select class="form-control" name="department" id="department">
                <option value="">Please select department</option>
                <option value="IT">IT Department</option>
                <option value="Maintenance">Maintenance</option>
            </select>
            @error('status')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Due date -->
        <div class="mb-3">
            <label for="due_date" class="form-label">Due Date</label>
            <input type="date" class="form-control" name="due_date">
        </div>

         <!-- Resolved date -->
        <div class="mb-3">
            <label for="resolve_at" class="form-label">Resolved at</label>
            <input type="date" class="form-control" name="resolve_at">
        </div>


        <!-- Button save and cancel -->
        <div class="flex items-center justify-end mt-4">
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>



<script>
    
    $(document).ready(function () {



        // var tagSelector = new MultiSelectTag('branch', {
        //     maxSelection: 5,     
        //     required: true,          
        //     placeholder: 'Please select branch',
        //     onChange: function(selected) { 
        //         console.log('Selection changed:', selected);
        //     }
        // });

        // console.log(tagSelector);


        $('#create_ticket').on('submit', function(e){
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('admin.ticket.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    console.log(response.success);
                    if (response.success) {
                        alert('Clinic added successfully!');
                        $('#formModal').modal('hide'); // Hide modal after success
                        location.reload();
                    } else {
                        alert('Something went wrong!');
                    }
                },
                error: function(xhr){
                    let errors = xhr.responseJSON.errors;
                    $('.alert-danger').remove(); // Remove old error messages

                    $.each(errors, function(key, value){
                        $('#' + key).after('<div class="alert alert-danger mt-2">' + value + '</div>');
                    });
                }
            });
        });
    });

</script>
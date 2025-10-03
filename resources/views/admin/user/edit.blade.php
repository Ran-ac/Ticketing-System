
<form id="update_user" method="POST" action="{{ route('user.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

     <input type="hidden" name="id" value="{{ $user->id }}">
        <!-- Name -->
        <div class="mb-6">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" value="{{ $user->name }}" class="form-control" placeholder="Enter name">
            @error('name')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" value="{{ $user->email }}" class="form-control" placeholder="Enter email">
            @error('email')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Address -->
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" name="address" id="address" value="{{ $user->address }}" class="form-control" placeholder="Enter address">
            @error('address')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Contact Number -->
        <div class="mb-3">
            <label for="contact_number" class="form-label">Contact Number</label>
            <input type="number" name="contact_number" id="contact_number" value="{{ $user->contact_number }}"   class="form-control" placeholder="Enter contact number">
            @error('contact_number')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Department -->
        <div class="mb-3">
            <label for="department" class="form-label">Department</label>
            <select class="form-select" name="department" id="department">
                <option value="">Please Select Department</option>
                <option value="IT" {{ $user->department == 'IT' ? 'selected' : '' }}>IT</option>
                <option value="Maintenance" {{ $user->department == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>
            @error('department')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Branch -->
        <div class="mb-3">
            <label for="branch" class="form-label">Branch:</label>
            <select class="form-control" name="branch[]" id="branch" multiple>
                @foreach ($clinics as $clinic)
                    <option value="{{ $clinic->id }}" @if(in_array($clinic->id, $user->branch ?? [])) selected @endif>
                        {{ $clinic->name }}
                    </option>
                @endforeach
            </select>

            @error('branch')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>


        <!-- Role -->
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" name="role" id="role">
                <option value="">Please Select Role</option>
                <option value="superadmin" {{ $user->role == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                <option value="head" {{ $user->role == 'head' ? 'selected' : '' }}>Head</option>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
            </select>
            @error('role')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required autocomplete="new-password">
            @error('password')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm password" required autocomplete="new-password">
            @error('password_confirmation')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
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

        var tagSelector = new MultiSelectTag('branch', {
            maxSelection: 5,
            required: true,
            placeholder: 'Please select branch',
            onChange: function(selected) { 
                console.log('Selection changed:', selected);
            }
        });


        console.log(tagSelector);


            $('#update_user').on('submit', function(e){
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('user.update') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        if (response.success) {
                            alert('Updated clinic successfully!');
                            $('#formModalEdit').modal('hide'); // Hide modal after success
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
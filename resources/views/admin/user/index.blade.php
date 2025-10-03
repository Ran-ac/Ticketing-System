<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GAOC TICKETING SYSTEM</title>
    <link rel="shortcut icon" type="image/png" href="/flexy-template/assets/images/logos/gaoc.png" />
    <link rel="stylesheet" href="/flexy-template/assets/css/styles.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- head: below existing links -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@4.0.1/dist/css/multi-select-tag.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/multiselect-tag@latest/dist/css/multi-select-tag.css">
        <script src="https://cdn.jsdelivr.net/npm/multiselect-tag@latest/dist/js/multi-select-tag.js"></script>
</head>


<body>
  <!-- Sidebar Start -->
    @include('admin.partials.sidebar')
  <!-- Sidebar End -->
  <!-- Main wrapper -->
    <div class="body-wrapper">
        <!-- Header Start -->
            @include('admin.partials.navbar')
        <!-- Header End -->
        <div>
            <main>
                <div class="container">
                    <h1 class="mt-4">Users</h1>
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item active">i see you</li>
                    </ol>

                    <div class="d-flex justify-content-between mb-3">
                        <button class="btn btn-success" id="openModalCreate">Create New User</button>
                    </div>
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="usersTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Contact Number</th>
                                    <th>Department</th>
                                    <th>Branch</th>
                                    <th>Role</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <!--table populate here -->
                                </tbody>
                                </table>
                            </div> 
                        </div>
                    </div> 
                </div> 
            </main>
        </div>

                <!-- Create User Modal -->
        <div class="modal fade" id="formModalCreate" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitle">Add new user</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    <div class="modal-body" id="modalContentCreate"></div>
                </div>
            </div>
        </div>

                <!-- Edit User Modal -->
        <div class="modal fade" id="formModalEdit" tabindex="-1" aria-labelledby="modalTitleEdit" aria-hidden="true">
            <div class="modal-dialog modal-l">
                <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitleEdit">Edit User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    <div class="modal-body" id="modalContentEdit"></div>
                </div>
            </div>
        </div>
        <!-- Footer -->
            @include('admin.partials.footer')
        <!-- End Footer -->
    </div> <!-- /.body-wrapper -->

        <!-- Scripts -->
        <script src="{{ asset('flexy-template/assets/libs/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ asset('flexy-template/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('flexy-template/assets/js/sidebarmenu.js') }}"></script>
        <script src="{{ asset('flexy-template/assets/js/app.min.js') }}"></script>
        <script src="{{ asset('flexy-template/assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
        <script src="{{ asset('flexy-template/assets/libs/simplebar/dist/simplebar.js') }}"></script>
        <script src="{{ asset('flexy-template/assets/js/dashboard.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>

        <!-- End of <body> -->
<script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@4.0.1/dist/js/multi-select-tag.min.js"></script>


        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        
</body>

</html>

<script>

$(document).ready(function () {
    
        let table = $("#usersTable").DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.user.fetchUserData') }}", // Fetching data via AJAX
            columns: [
                { data: "id", name: "id" },
                { data: "name", name: "name" },
                { data: "email", name: "email" },
                { data: "address", name: "address" },
                { data: "contact_number", name: "contact_number" },
                { data: "department", name: "department" },
                { data: "branch", name: "branch" },
                { data: "role", name: "role" },
                { data: "created_at", name: "created_at",
                    render: function(data, type, row) {
                        return new Date(data).toLocaleString('en-US', {
                            month: 'long',
                            day: '2-digit',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: true
                        });
                    }
                },
                { 
                    data: "id",
                    render: function (data, type, row) {
                        return `
                            <div class="d-flex m-2">
                                <button class="btn btn-info me-2 openEditModal" data-id="${data}">Edit</button>
                                <button class="btn btn-danger deleteUser" data-id="${data}">Delete</button>
                            </div>
                        `;
                    },
                    orderable: false
                }
            ]
        });

        $('#openModalCreate').click(function () {
            $.get("{{ route('user.create') }}", function (data) {
                $('#modalContentCreate').html(data);
            $('#formModalCreate').modal('show');
            });
        });


        $(document).on('click', '.openEditModal', function (event) {
			event.preventDefault();
			let clinicId = $(this).data('id');

	   		$.get("{{ route('user.edit', ':id') }}".replace(':id', clinicId), function (data) {
                $('#modalContentEdit').html(data);
                $('#formModalEdit').modal('show');
            }).fail(function(xhr) {
                console.error("Error loading edit modal:", xhr.responseText);
            });
		});


        $(document).on("click", ".deleteUser", function (event) {
			event.preventDefault();

			let userId = $(this).data("id");

			if (!confirm("Are you sure you want to delete this user?")) return;

			$.ajax({
				type: "DELETE",
				url: "/admin/user/delete-user/" + userId,
				data: {
					_token: "{{ csrf_token() }}"
				},
				success: function (response) {
					alert("User deleted successfully!");
					location.reload();
				},
				error: function (xhr, status, error) {
					console.error("Error deleting user:", xhr.responseText);
					alert("Failed to delete user.");
				}
			});
		});


    });

</script>

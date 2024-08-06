@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Markups</h1>
    <button id="addMarkupButton" class="btn btn-primary">Add Markup</button>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Type</th>
                <th>Value</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="markupTableBody">
            @foreach($markups as $markup)
            <tr id="markupRow-{{ $markup->id }}">
                <td>{{ $markup->type }}</td>
                <td>{{ $markup->value }}</td>
                <td>
                    <button class="btn btn-warning editMarkupButton" data-id="{{ $markup->id }}">Edit</button>
                    <button class="btn btn-danger deleteMarkupButton" data-id="{{ $markup->id }}">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Markup Modal -->
<div class="modal fade" id="addMarkupModal" tabindex="-1" role="dialog" aria-labelledby="addMarkupModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMarkupModalLabel">Add Markup</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="addMarkupError" class="alert alert-danger d-none"></div>
                <form id="addMarkupForm">
                    @csrf
                    <div class="form-group">
                        <label for="type">Type</label>
                        <input type="text" name="type" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="value">Value</label>
                        <input type="number" name="value" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Markup Modal -->
<div class="modal fade" id="editMarkupModal" tabindex="-1" role="dialog" aria-labelledby="editMarkupModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMarkupModalLabel">Edit Markup</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="editMarkupError" class="alert alert-danger d-none"></div>
                <form id="editMarkupForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="editMarkupId">
                    <div class="form-group">
                        <label for="editType">Type</label>
                        <input type="text" name="type" id="editType" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="editValue">Value</label>
                        <input type="number" name="value" id="editValue" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this markup?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let deleteMarkupId = null;

        // Show Add Markup Modal
        $('#addMarkupButton').click(function() {
            $('#addMarkupModal').modal('show');
        });

        // Clear error message when the Add Markup modal is closed
        $('#addMarkupModal').on('hidden.bs.modal', function () {
            $('#addMarkupError').addClass('d-none').text('');
            $('#addMarkupForm')[0].reset();
        });

        // Handle Add Markup Form Submission
        $('#addMarkupForm').submit(function(event) {
            event.preventDefault();
            $('#addMarkupError').addClass('d-none').text(''); // Hide previous error messages
            $.ajax({
                url: '{{ route('markups.store') }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#markupTableBody').append(`
                        <tr id="markupRow-${response.id}">
                            <td>${response.type}</td>
                            <td>${response.value}</td>
                            <td>
                                <button class="btn btn-warning editMarkupButton" data-id="${response.id}">Edit</button>
                                <button class="btn btn-danger deleteMarkupButton" data-id="${response.id}">Delete</button>
                            </td>
                        </tr>
                    `);
                    $('#addMarkupModal').modal('hide');
                },
                error: function(response) {
                    if (response.status === 409) {
                        $('#addMarkupError').removeClass('d-none').text(response.responseJSON.message); // Show error message
                    } else {
                        console.error('Error:', response);
                    }
                }
            });
        });

        // Show Edit Markup Modal
        $(document).on('click', '.editMarkupButton', function() {
            const id = $(this).data('id');
            $.ajax({
                url: `/markups/${id}`,
                method: 'GET',
                success: function(response) {
                    $('#editMarkupId').val(response.id);
                    $('#editType').val(response.type);
                    $('#editValue').val(response.value);
                    $('#editMarkupModal').modal('show');
                },
                error: function(response) {
                    console.error('Error:', response);
                }
            });
        });

        // Clear error message when the Edit Markup modal is closed
        $('#editMarkupModal').on('hidden.bs.modal', function () {
            $('#editMarkupError').addClass('d-none').text('');
            $('#editMarkupForm')[0].reset();
        });

        // Handle Edit Markup Form Submission
        $('#editMarkupForm').submit(function(event) {
            event.preventDefault();
            $('#editMarkupError').addClass('d-none').text(''); // Hide previous error messages
            const id = $('#editMarkupId').val();
            $.ajax({
                url: `/markups/${id}`,
                method: 'PUT',
                data: $(this).serialize(),
                success: function(response) {
                    $(`#markupRow-${response.id}`).replaceWith(`
                        <tr id="markupRow-${response.id}">
                            <td>${response.type}</td>
                            <td>${response.value}</td>
                            <td>
                                <button class="btn btn-warning editMarkupButton" data-id="${response.id}">Edit</button>
                                <button class="btn btn-danger deleteMarkupButton" data-id="${response.id}">Delete</button>
                            </td>
                        </tr>
                    `);
                    $('#editMarkupModal').modal('hide');
                },
                error: function(response) {
                    if (response.status === 409) {
                        $('#editMarkupError').removeClass('d-none').text(response.responseJSON.message); // Show error message
                    } else {
                        console.error('Error:', response);
                    }
                }
            });
        });

        // Show Delete Confirmation Modal
        $(document).on('click', '.deleteMarkupButton', function() {
            deleteMarkupId = $(this).data('id');
            $('#deleteConfirmationModal').modal('show');
        });

        // Handle Confirm Delete Button Click
        $('#confirmDeleteButton').click(function() {
            $.ajax({
                url: `/markups/${deleteMarkupId}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $(`#markupRow-${deleteMarkupId}`).remove();
                        $('#deleteConfirmationModal').modal('hide');
                    }
                },
                error: function(response) {
                    console.error('Error:', response);
                }
            });
        });

        // Restrict input to numbers only for value fields
        $('input[name="value"]').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Handle Cancel buttons
        $('#cancelAdd').click(function() {
            $('#addMarkupModal').modal('hide');
            $('#addMarkupForm')[0].reset();
        });

        $('#cancelEdit').click(function() {
            $('#editMarkupModal').modal('hide');
            $('#editMarkupForm')[0].reset();
        });
    });
</script>
@endsection

@extends('layouts.app')

@section('page_title') Admin - View User Accounts @endsection

@section('content')
<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
    @include('common.validationErrors')
    <div class="row text-center">
        <div class="btn-group pageButtons" role="group">
            <a class="btn btn-default" href="/members" role="button">Home</a>
        </div>
    </div>
    <table class="table table-hover table-condensed text-center">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Account Type</th>
                <th>Banned</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>
                    {{ $user->id }}
                </td>
                <td>
                    {{ $user->name }}
                </td>
                <td>
                    {{ $user->email }}
                </td>
                <td>
                    {{ $user->accountType->type }}
                </td>
                <td>
                    {{ readableEntry($user->banned) }}
                </td>
                <td>
                    <small>
                        <a data-toggle="modal" data-target="#editUserModal" data-user-email="{{ $user->email }}" data-user-type="{{ $user->account_type_id }}" data-user-banned="{{ $user->banned }}" href="javascript:void();">Edit</a>
                    </small>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editUserModalLabel">Edit User</h4>
            </div>
            <div class="modal-body text-center">
                <p>Email: <span id="user-email-span"></span></p>
                <form method="POST" action="" id="editUserForm">
                    {!! csrf_field() !!}
                    <input type="hidden" id="userEmail" name="userEmail" value="">
                    <div class="form-group">
                        <label for="accountType">Account Type:</label>
                        <select id="accountType" name="accountType" class="form-control text-center">
                            <option id="adminOption" value="2">Administrator</option>
                            <option id="userOption" value="3">User</option>
                        </select>
                    </div>
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-primary">
                            <input type="radio" id="activeRadio" name="editBanned" value="0" autocomplete="off"> Active
                        </label>
                        <label class="btn btn-primary">
                            <input type="radio" id="bannedRadio" name="editBanned" value="1" autocomplete="off"> Banned
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="editUserSubmit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additional_js')
<script type="text/javascript">

    $('#editUserSubmit').click(function () {
        $('#editUserForm').submit();
    });

    $('#editUserModal').on('show.bs.modal', function (event) {
        var link = $(event.relatedTarget);
        var email = link.data('user-email');
        var accountType = link.data('user-type');
        var banned = link.data('user-banned');

        var modal = $(this);
        document.getElementById('user-email-span').innerHTML = email;
        modal.find('#userEmail').val(email);
        if (accountType == '2') {
            var select = document.getElementById('adminOption');
            (select).selected = true;
        } else if (accountType = '3') {
            var select = document.getElementById('userOption');
            (select).selected = true;
        }
        if (banned == '1') {
            var radio = document.getElementById('bannedRadio');
            (radio).checked = true;
            $(radio).parent('label').addClass('active');

        } else if (banned == '0') {
            var radio = document.getElementById('activeRadio');
            (radio).checked = true;
            $(radio).parent('label').addClass('active');
        }
    });
</script>
@endsection
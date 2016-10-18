@extends('layouts.app')

@section('page_title') Admin - All Appointments @if (isset($statusName)) - {{ $statusName }} @endif @endsection

@section('additional_css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/t/bs/jq-2.2.0,dt-1.10.11/datatables.min.css"/>
@endsection

@section('content')
<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
    <div class="row text-center">
        <div class="btn-group pageButtons" role="group">
            <a class="btn btn-default dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">View by Status<span class="caret"></span></a>
            <a class="btn btn-default" href="/members/admin/appointments" role="button">All Appointments</a>
            <a class="btn btn-default" href="/members" role="button">Home</a>
            <ul class="dropdown-menu">
                @foreach($allStatus as $status)
                <li><a href="?status={{ $status->id }}">{{ $status->status_name }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
    @if (count($appointments) == 0)
    <p class="text-center">No appointments/quotes found.</p>
    @else
    <table id="appointmentsTable" class="table table-hover table-condensed text-center">
        <thead>
            <tr>
                <th>Quote</th>
                <th>Client Name</th>
                <th>Appointment</th>
                <th>Date Added</th>
                <th>Last Update</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appointment)
            <tr>
                <td>
                    {{ $appointment->id }}
                </td>
                <td>
                    {{ $appointment->client_name }}
                </td>
                <td>
                    @if ($appointment->appointment_made == '0')
                    {{ readableEntry($appointment->appointment_made) }}
                    @else
                    {{ $appointment->appointment_date }}
                    @endif
                </td>
                <td>
                    {{ stripDate($appointment->created_at) }}
                </td>
                <td>
                    @if ($appointment->updated_at == '-0001-11-30 00:00:00')
                    Not Updated
                    @else
                    {{ stripDate($appointment->updated_at) }}
                    @endif
                </td>
                <td>
                    <span class="label {{ colourLabel($appointment->appointment_status_id) }}">{{ $appointment->appointment_status->status_name }}</span>
                </td>
                <td><a href="/members/details/{{ $appointment->id }}"><small>Details</small></a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection

@section('additional_js')
<script type="text/javascript" src="https://cdn.datatables.net/t/bs/jq-2.2.0,dt-1.10.11/datatables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#appointmentsTable').DataTable( {
            searching: false
        });
    });
</script>
@endsection
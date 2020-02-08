@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Reembolsos</div>

                <div class="card-body">
                    <table class="table table-hover">
                        <tr>
                            <td>ID</td>
                            <td>Status</td>
                            <td>Employee</td>
                            <td>Date</td>
                            <td>Description</td>
                            <td>Value</td>
                            <td>Action</td>
                        </tr>
                        @foreach ($refunds as $refund)
                        <tr>
                            <td>{{$refund->id}}</td>
                            <td>{{$refund->status}}</td>
                            <td>{{$refund->employee->name}}</td>
                            <td>{{$refund->date}}</td>
                            <td>{{$refund->description}}</td>
                            <td>{{$refund->value}}</td>
                            <td><div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Status Action
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                  <a class="dropdown-item" href="{{route('change_status')}}?refund={{$refund->id}}&&status=0">Canceled</a>
                                  <a class="dropdown-item" href="{{route('change_status')}}?refund={{$refund->id}}&&status=2">Approved</a>                                </div>
                              </div>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

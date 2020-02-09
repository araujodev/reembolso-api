@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Reembolsos</div>

                <div class="card-body">
                    <div class="text-center">
                        <h3>Total Refunds: ${{$totalRefunds}}</h3>
                    </div>
                    <table class="table table-hover">
                        <tr>
                            <td>ID</td>
                            <td>Status</td>
                            <td>Employee</td>
                            <td>Date</td>
                            <td>Description</td>
                            <td>Value</td>
                            <td>Action</td>
                            <td>Remove</td>
                        </tr>
                        @foreach ($refunds as $refund)
                        <tr>
                            <td>{{$refund->id}}</td>
                            <td>{{$refund->status_label}}</td>
                            <td>{{$refund->employee->name}}</td>
                            <td>{{$refund->date}}</td>
                            <td><small>{{$refund->description}}</small></td>
                            <td>{{$refund->value}}</td>
                            <td><div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Status Action
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                  <a class="dropdown-item" href="{{route('change_status', [$refund->id, 0])}}">Canceled</a>
                                  <a class="dropdown-item" href="{{route('change_status', [$refund->id, 2])}}">Approved</a>
                                  <a class="dropdown-item" href="{{route('change_status', [$refund->id, 1])}}">Opened</a>
                                </div>
                              </div>
                            </td>
                            <td>
                                <form class="d-inline-block" action="{{ url('/refunds/remove', ['refund_id' => $refund->id]) }}" method="post">
                                    <button class="btn btn-danger" type="submit"> Remove </button>
                                    {!! method_field('delete') !!}
                                    {!! csrf_field() !!}
                                </form>
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

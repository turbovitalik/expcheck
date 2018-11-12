@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <th>#</th>
                                <td>Action</td>
                            </thead>
                            <tbody>
                            @foreach ($queueList as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>Details</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
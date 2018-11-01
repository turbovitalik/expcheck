@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{ __('.txt file info') }}</div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if ($fileName)
                            <p><span class="text-success">File .txt for parsing is available on server</span> ({{ $fileName }})</p>
                            <a href="{{ route('parsing_start') }}" class="btn btn-primary">{{ __('Start Export') }}</a>
                        @else
                            <p><span class="text-danger">Could not find relevant file .txt for parsing</span></p>
                        @endif
                    </div>
                </div>
                <p></p>
                <div class="card">
                    <div class="card-header">{{ __('History') }}</div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <th>Started</th>
                                <th>File</th>
                                <th>Status</th>
                                <th>Info</th>
                            </thead>
                            <tbody>
                                @foreach ($history as $record)
                                    <tr>
                                        <td></td>
                                        <td>{{ $record->getFileName() }}</td>
                                        <td>{{ $record->getStatus() }}</td>
                                        <td>{{ $record->getDescription() }}</td>
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
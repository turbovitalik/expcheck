@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Domains List') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <table class="table">
                            <thead>
                                <th>#</th>
                                <th>Domain Name</th>
                                <th>Expires</th>
                                <th>Parsed</th>
                            </thead>
                            <tbody>
                            @foreach ($domains as $domain)
                                <tr>
                                    <td>{{ $domain->id }}</td>
                                    <td>{{ $domain->name }}</td>

                                    <td>
                                        @if ($domain->expires_at)
                                            {{ date_format($domain->expires_at, 'd-m-Y H:i:s') }}
                                        @else
                                            {{ __('Undefined') }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ date_format($domain->created_at, 'd-m-Y H:i:s') }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $domains->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
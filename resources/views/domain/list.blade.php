@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form method="get">
                            <div class="form-group">
                                <label class="col-form-label" for="trust_flow">{{ __('Trust flow') }}</label>
                                <input class="form-control" id="trust_flow" name="trust_flow" type="text" value="{{ request('trust_flow') }}" />
                            </div>
                            <div class="form-group">
                                <label class="col-form-label" for="citation_flow">{{ __('Citation flow') }}</label>
                                <input class="form-control" id="citation_flow" name="citation_flow" type="text" value="{{ request('citation_flow') }}" />
                            </div>
                            <div class="form-group">
                                @foreach($tlds as $tld)
                                    <div class="form-check">
                                        <input
                                            @if(request('tld') && in_array($tld->tld, request('tld')))
                                                checked=checked
                                            @endif
                                            class="form-check-input" type="checkbox" name="tld[]" value="{{ $tld->tld }}" id="tld_{{ $tld->tld }}" />
                                        <label class="form-check-label" for="tld_com">.{{ $tld->tld }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit">{{ __('Filter') }}</button>
                                <a href="{{ route('domains_list') }}" class="btn btn-primary">{{ __('Show all') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
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
                                <th>Trust Flow</th>
                                <th>Citation Flow</th>
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
                                    <td>{{ $domain->trust_flow }}</td>
                                    <td>{{ $domain->citation_flow }}</td>
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
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <p>Undefined Majestic stats: {{ $undefined }}</p>
                        <a href="{{ route('get_majestic_chunk') }}" class="btn btn-primary">{{ __('Get Majestic data (100 items)') }}</a>
                        <a href="{{ route('get_majestic_all') }}" class="btn btn-success">{{ __('Get Majestic data (full)') }}</a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form method="get">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="col-form-label" for="trust_flow_min">{{ __('Trust flow (from):') }}</label>
                                    <input class="form-control" id="trust_flow_min" name="trust_flow_min" type="text" value="{{ request('trust_flow_min') }}" />
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-form-label" for="trust_flow_max">{{ __('Trust flow (to):') }}</label>
                                    <input class="form-control" id="trust_flow_max" name="trust_flow_max" type="text" value="{{ request('trust_flow_max') }}" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="col-form-label" for="citation_flow_min">{{ __('Citation flow (min)') }}</label>
                                    <input class="form-control" id="citation_flow_min" name="citation_flow_min" type="text" value="{{ request('citation_flow_min') }}" />
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-form-label" for="citation_flow_max">{{ __('Citation flow (max)') }}</label>
                                    <input class="form-control" id="citation_flow_max" name="citation_flow_max" type="text" value="{{ request('citation_flow_max') }}" />
                                </div>
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
                                <th>Updated</th>
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
                                    <td>{!! !is_null($domain->trust_flow) ? $domain->trust_flow : '<span class="bg-warning">Undefined</span>' !!}</td>
                                    <td>{!! !is_null($domain->citation_flow) ? $domain->citation_flow : '<span class="bg-warning">Undefined</span>' !!}</td>
                                    <td>
                                        @if ($domain->updated_at)
                                            {{ date_format($domain->updated_at, 'd-m-Y H:i:s') }}
                                        @else
                                            {{ __('Undefined') }}
                                        @endif
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
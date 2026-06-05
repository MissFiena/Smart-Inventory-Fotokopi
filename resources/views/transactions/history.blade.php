@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Transaction History</h1>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>User</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->id }}</td>
                <td>{{ $transaction->product->name ?? 'N/A' }}</td>
                <td>{{ $transaction->user->name ?? 'N/A' }}</td>
                <td>{{ $transaction->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $transactions->links() }}
</div>
@endsection
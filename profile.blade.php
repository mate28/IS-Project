@extends('main')
@section('title',' | Profile')
@section('content')
<div class="container-fluid" style="background-color:orange;text-align:center;margin-top:-30px">
<h3>User Profile</h3>
<h5>Username: {{ Auth::user()->name }} <br>
Email: {{ Auth::user()->email }}<br>
Phone Number: {{ Auth::user()->number }} 
Location: {{ Auth::user()->location }} 
  </h5>

</div>
<div class="container" style="background-color:red;text-align:center;">
<div class="row">
    <div class="col-sm">
    <h5>Recent Orders</h5>
    @foreach($orders as $order)
                <p> {{ $order->id }}</p>
                    {{ $order->quantity }}
                    {{ $order->delivery_location}}
                    {{ $order->number}}
                    @endforeach 
    </div>
    <div class="col-sm">
    <h5>Delivery Address<h5>

    </div>
  </div>
</div>
         
@endsection 

@extends('layouts.app')

@section('content')
<?php
$webshops = \App\Models\Webshop::query()->get();

?>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    function giveAlert(id) {
        swal({
            title: "Are you sure?",
            text: "Deze actie is niet terug te draaien.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    swal("Succesvol verwijderd!", {
                        icon: "success",
                    });
                    window.location.href = "/delete/webshop/"+id;
                } else {
                    swal("Deze webshop is niet verwijderd.");
                }
            });
    }
</script>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6 pt-2">
                            {{ __('Webshops') }}
                        </div>
                        <div class="col-md-6">
                            <a href="/create/product" class="btn btn-success float-right">
                                Create
                            </a>
                        </div>
                    </div>
                </div>


                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                        <table id="customer" class="table">
                            <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">Url</th>
                                <th class="text-center">Type</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($webshops as $webshop)
                                <tr>
                                    <td class="text-center"><strong>{!! $webshop->id !!}</strong></td>
                                    <td class="text-center"><strong>{!! $webshop->url !!}</strong></td>
                                    <td class="text-center"><strong>{!! $webshop->type !!}</strong></td>
                                    <td class="text-center">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <a class="btn btn-success" href="{!! route('webshop.edit',$webshop->id) !!}">Update</a>
                                            </div>
                                            <div class="col-md-6">
                                                <a class="btn btn-danger" onclick="giveAlert({{$webshop->id}})">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                    <!-- End:modal Add Product -->
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

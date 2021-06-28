@extends('layouts.app')

@section('content')
    <?php
    $webshop = \App\Models\Webshop::query()->get();

    ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Webshops') }}</div>

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
                            @foreach($webshop as $product)
                                <tr>
                                    <td class="text-center"><strong>{!! $product->id !!}</strong></td>
                                    <td class="text-center"><strong>{!! $product->url !!}</strong></td>
                                    <td class="text-center"><strong>{!! $product->type !!}</strong></td>
                                    <td class="text-center">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <a class="btn btn-success" href="{!! route('webshop.edit',$product->id) !!}">Update</a>
                                            </div>
                                            <div class="col-md-6">
                                                <a class="btn btn-danger" href="{!! route('webshop.destroy',$product->id) !!}">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                    <!-- End:modal Add Product -->
                                    <div id="delete_product" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content" >
                                                <center>
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h3 class="modal-title">Delete Product Confirmation</h3><br>
                                                    </div>
                                                </center>
                                                <div class="modal-body" >
                                                    <p>
                                                        Are you sure want to Detete this product?
                                                    </p>
                                                    <form class="form-horizontal" method="get" action="/product/delete/{{$product->id}}">
                                                        {{csrf_field()}}
                                                        <br>
                                                        <div class="row">
                                                            <div class="col-md-9">
                                                                <button type="button" class="btn btn-hover btn-primary btn-sm" data-dismiss="modal">Cancel</button>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input class="btn btn btn-hover btn-danger btn-sm" type="submit" value="Yes, Delete">
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="agile-grids">
        <div class="table-heading">
            <h2>Create Webshop</h2>
        </div>
        <!-- Form start Start -->
        <div class="panel panel-widget forms-panel">
            <div class="forms" >
                <div class=" form-grids form-grids-right">
                    <div class="widget-shadow " data-example-id="basic-forms">
                        <div class="form-body">
                            <form class="form-horizontal" method="POST" action="{{route('webshop.create')}}">
                                @csrf
                                <div class="form-group">
                                    <label for="url" class="col-sm-2 control-label">Website url</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="url" class="form-control" id="url" value="" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="customer_key" class="col-sm-2 control-label">Customer Key</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="customer_key" class="form-control" id="customer_key" value="" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="customer_secret" class="col-sm-2 control-label">Customer Secret</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="customer_secret" class="form-control" id="customer_secret" value="" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="type" class="col-sm-2 control-label">Type</label>
                                    <div class="col-sm-9">
                                        <select name="type">
                                            <option value="WooCommerce">WooCommerce</option>
                                            <option value="Magento">Magento</option>
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <input class="btn btn-primary" type="submit" value="Create">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

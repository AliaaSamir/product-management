
@extends('../layout')

@section('content')
<h2 class="display-6 text-center mb-4">Products Listing</h2>
<div class="card border-light mb-3">
  <div class="card-header">Filter</div>
  <div class="card-body">
        <form action="/products">
        <div class="row">
        <div class="col-md-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" value="{{old('title', request()->input('title'))}}" class="form-control" id="title" name="title" placeholder="Title">
        </div>
        <div class="col-md-3">
            <label for="category" class="form-label">Category</label>
            <input type="text" value="{{old('category', request()->input('category'))}}" class="form-control" id="category" name="category" placeholder="Category">
        </div>
        <div class="col-md-3">
            <label for="weight" class="form-label">Weight</label>
            <input type="text" value="{{old('weight', request()->input('weight'))}}" class="form-control" id="weight" name="weight" placeholder="Weight">
        </div>
        <div class="col-md-3">
            <label for="description" class="form-label">Description</label>
            <input type="text" value="{{old('description', request()->input('description'))}}" class="form-control" id="description" name="description" placeholder="Description">
        </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <label for="sort" class="form-label">Sort By</label>
                <select class="form-control" name="sort" id="sort">
                    <option value="">Sort By</option>
                    <option value="title" {{ request()->input('sort') == 'title'? 'selected' : '' }} >Title</option>
                    <option value="weight" {{ request()->input('sort') == 'weight'? 'selected' : '' }} >Weight</option>
                    <option value="category" {{ request()->input('sort') == 'category'? 'selected' : '' }} >Category</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="dir" class="form-label">Sort Direction</label>
                <select class="form-control" name="dir" id="dir">
                    <option value="asc" {{ request()->input('dir') == 'asc' ? 'selected' : '' }} >Ascending</option>
                    <option value="desc" {{ request()->input('dir') == 'desc' ? 'selected' : '' }} >Descending</option>
                </select>
            </div>
            <div class="col-md-6 mt-2 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
        </form>
    </div>
</div>
<div class="card border-light mb-3">
  <div class="card-header">
    <div class="row">
        <div class="col-md-9 m-auto">
            <i class="bi bi-list"></i> Products
        </div>
        <div class="col-md-3 d-flex justify-content-end">
            {{$products->links()}}
        </div>
    </div>
    </div>
  <div class="card-body">
    <div class="table-responsive">
        <table class="table"> 
            <thead>
                <tr>
                    <th class="text-nowrap">
                        Title 
                        @if ( request()->input('sort') == 'title' )
                        @if ( request()->input('dir') == 'desc' )
                        <i class="bi bi-sort-alpha-up"></i>
                        @else
                        <i class="bi bi-sort-alpha-down"></i>
                        @endif
                        @endif
                    </th>
                    <th class="text-nowrap">Description</th>
                    <th class="text-nowrap">
                        Weight
                        @if ( request()->input('sort') == 'weight' )
                        @if ( request()->input('dir') == 'desc' )
                        <i class="bi bi-sort-alpha-up"></i>
                        @else
                        <i class="bi bi-sort-alpha-down"></i>
                        @endif
                        @endif
                    </th>
                    <th class="text-nowrap">
                        Category
                        @if ( request()->input('sort') == 'category' )
                        @if ( request()->input('dir') == 'desc' )
                        <i class="bi bi-sort-alpha-up"></i>
                        @else
                        <i class="bi bi-sort-alpha-down"></i>
                        @endif
                        @endif
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr>
                    <td>{{ $product->title }}</td>
                    <td>{{ $product->description }}</td>
                    <td>{{ $product->weight }}</td>
                    <td>{{ $product->category }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
  </div>
</div>
@endsection
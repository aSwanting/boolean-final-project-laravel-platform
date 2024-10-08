@extends('layouts.app')

@section('content')
<div class="container">
    <div class="py-3">
        <h1 class="display-6 text-center">Edit Apartment Information</h1>
        <form class="mb-4 apartment-form" action="{{ route('admin.apartments.update', $apartment) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <section class="container card py-3 my-3 shadow">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                        value="{{ old('name', $apartment->name) }}">
                    <div class="is-invalid" value="">@error('name') The name field is required @enderror</div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" class="form-control  @error('description') is-invalid @enderror" id="description"
                        name="description" value="{{ old('description', $apartment->description) }}">
                    <div class="is-invalid" value="">@error('description') The description field is required @enderror</div>
                </div>
                <div class="mb-3">
                    <label for="rooms" class="form-label">Rooms</label>
                    <input type="text" class="form-control @error('rooms') is-invalid @enderror" id="rooms" name="rooms"
                        value="{{ old('rooms', $apartment->rooms) }}">
                    <div class="is-invalid" value="">@error('rooms') The rooms field is required and must be greater than 0
                        @enderror</div>
                </div>
                <div class="mb-3">
                    <label for="beds" class="form-label">Beds</label>
                    <input type="text" class="form-control @error('beds') is-invalid @enderror" id="beds" name="beds"
                        value="{{ old('beds', $apartment->beds) }}">
                    <div class="is-invalid" value="">@error('beds') The beds field is required and must be greater than 0
                        @enderror</div>
                </div>
                <div class="mb-3">
                    <label for="bathrooms" class="form-label">Bathrooms</label>
                    <input type="text" class="form-control @error('bathrooms') is-invalid @enderror" id="bathrooms"
                        name="bathrooms" value="{{ old('bathrooms', $apartment->bathrooms) }}">
                    <div class="is-invalid" value="">@error('bathrooms') The bathrooms field is required and must be greater
                        than
                        0 @enderror</div>
                </div>
                <div class="mb-3">
                    <label for="square_meters" class="form-label">Square meters</label>
                    <input type="text" class="form-control @error('square_meters') is-invalid @enderror" id="square_meters"
                        name="square_meters" value="{{ old('square_meters', $apartment->square_meters) }}">
                    <div class="is-invalid" value="">@error('square_metres') The square meters field is required and must be
                        greater
                        than 0 @enderror</div>
                </div>
            </section>    

            <section class="container card py-3 my-3 shadow">
                <div class="form-group mb-3">
                    <p>Choose apartment services:</p>
                    <div class="d-flex flex-wrap gap-4 ">
                        @foreach ($services as $service)
                        <div class="form-check">
                            <input name="services[]"
                                class="form-check-input @error('services') is-invalid @enderror service" type="checkbox"
                                value="{{ $service->id }}" id="service-{{ $service->id }}" @checked(in_array($service->id,
                            old('services',
                            $apartment->services->pluck('id')->all())))>
                            <label class="form-check-label" for="service-{{ $service->id }}">
                                {{ $service->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                    <div class="is-invalid" id="service-error" value="">@error('services') The services field is required
                        choose at least one
                        service @enderror</div>
                </div>
            </section>
            
            <section class="container card py-3 my-3 shadow">
                <div class="mb-3">
                    <label for="cover_image" class="form-label">Upload new cover Image</label>
                    <input class="form-control" type="file" id="cover_image" name="cover_image">
                </div>

                <div class="form-group mb-3">
                    <p>Select images to delete:</p>
                    <div class="container flex-wrap gap-4 ">
                        <div class="row">
                            @foreach ($images as $image)
                            <div class="form-check col-12 col-lg-4">
                                <input name="old_images[]" class="form-check-input" type="checkbox" value="{{ $image }}" id="old_image-{{ $image->id }}">                           
                                <label class="form-check-label" for="old_image-{{ $image }}">
                                    <img class="images" src="{{ asset('storage/images') . '/' . $image->link }}" alt="">
                                </label>
                            </div>
                                @endforeach
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="images" class="form-label">Add more images</label>
                    <input class="form-control" type="file" id="images" name="images[]" multiple>
                </div>
            </section>

            <section class="container card py-3 my-3 shadow">
                <div class="mb-3">
                    <div id="radio-buttons" class="d-flex flex-wrap gap-4 ">
                        <div class="form-check">
                            <input type="radio" name="visible" class="form-check-input" id="visible" value="1" checked>
                            <label for="1">Make your apartment visible</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="visible" class="form-check-input" id="invisible" value="0">
                            <label for="0">Make your apartment invisible</label>
                        </div>
                    </div>
                    <div class="is-invalid"></div>
                </div>
            </section> 
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary px-4" type="submit">Edit</button>
            </form>
                <form action="{{ route('admin.apartments.destroy', $apartment) }}" method="POST"
                    id="deletionForm">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger modal-trigger" id="deletion" type="submit"
                    name="{{ $apartment->name }}" address="{{ $apartment->address }}">Delete</button>
                </form>
            </div>      
                
            
        <x-delete-modal />
        @push('scripts')
        <script src="{{asset('./js/apartmentValidation.js')}}"></script>
        <script src="{{ asset('js/deleteModal.js') }}"></script>
        @endpush
    </div>
</div>
<style>
    .is-invalid {
        border-color: red;
        color: red;
    }
    .images {
        max-width: 100%;
        aspect-ratio: 14/9;
    }

    @media (min-width: 768px) { 
    .optional-images {
        /* max-width: 100%; */
    }
    }
</style>

@endsection
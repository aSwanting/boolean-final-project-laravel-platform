@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <h1 class="text-center">{{ $apartment->name }}</h1>
    </div>
    <div class="container mt-4">
        @if ($apartment->cover_image)
            <figure class="d-flex justify-content-center">
                @if (str_contains($apartment->cover_image, 'cover_images'))
                    <img class="cover-image" src="{{ asset('storage/' . $apartment->cover_image) }}">
                @else
                    <img class="cover-image" src="{{ asset('storage/cover_images/' . $apartment->cover_image) }}">
                @endif
            </figure>
        @endif
        <h3>{{ $apartment->description }}</h3>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="my-3">
                    <ul class="list-group apt-info shadow mb-4 text-capitalize">
                        <li class="list-group-item p-3 "><span class="fw-bold">Rooms: </span>{{ $apartment->rooms }}
                        </li>
                        <li class="list-group-item p-3 "><span class="fw-bold">Beds: </span>{{ $apartment->beds }}
                        </li>
                        <li class="list-group-item p-3 "><span class="fw-bold">Bathrooms:
                            </span>{{ $apartment->bathrooms }}</li>
                        <li class="list-group-item p-3 ">
                            <span class="fw-bold">Size:</span>
                            <span>{{ $apartment->square_meters }}sqm</span>
                        </li>
                        <li class="list-group-item p-3 ">
                            <span class="fw-bold">Address:</span>
                            {{ $apartment->address }}, {{ $apartment->country }}
                        </li>
                        <li class="list-group-item p-3">
                            <span class="fw-bold">Services:
                                @forelse ($apartment->services as $service)
                                    <span class="badge rounded-pill text-bg-primary">{{ $service->name }}</span>
                                @empty
                                    <span class="list-group-item p-2">Your apartment has no services!</span>
                                @endforelse
                        </li>
                        <li class="list-group-item p-3 ">
                            <span class="fw-bold">Visible: </span>
                            @if ($apartment->visible)
                                <span class="green">Yes</span>
                            @else
                                <span class="red">No</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <section class="chart-container card shadow my-3 p-3">
                    <canvas id="visits"></canvas>
                </section>
            </div>
        </div>

        <section class="card shadow">
            <div class="accordion accordion-flush">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                            <h5>Buy new promotion for your apartment!</h5>
                        </button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body z-3 position-absolute">
                            <div class="container card py-2 my-3 shadow font-size-small">
                                <form action="{{ route('admin.processPayment', $apartment) }}" method="POST"
                                    id="payment-form">
                                    @csrf
                                    <label class="form-label" for="floatingSelect">Choose promotion</label>
                                    <div class="form-floating mb-3">
                                        <select class="form-select font-size-small" id="promo"
                                            aria-label="Floating label select example" name="pay_method">
                                            <option class="font-size-small ps-2" value="1" name="1"> Gold
                                                <span class="amount">-- &euro;2.99 --One day</span>
                                            </option>
                                            <option class="font-size-small" value="2" name="2">Diamond
                                                <span class="amount">-- &euro;5.99 --Two days</span>
                                            </option>
                                            <option class="font-size-small" value="3" name="3">Platinum
                                                <span class="amount">-- &euro;9.99 --Six days</span>
                                            </option>
                                        </select>
                                        <div class="red"></div>
                                    </div>

                                    <div class="mb-3" id="card-name-father">
                                        <input type="hidden" name="apartment_id" value="{{ $apartment->id }}">
                                        <label for="cardholder-name" class="form-label">Cardholder's Name</label>
                                        <input type="text" class="form-control" id="cardholder-name"
                                            name="cardholder_name" placeholder="Kevin Smith">
                                        <div class="red"></div>
                                    </div>

                                    <div class="mb-3" id="card-number-father">
                                        <label for="cardholder-name" class="form-label">Card Number</label>
                                        <div class="input-group flex-nowrap mb-3">
                                            <span class="input-group-text credit-card" id="addon-wrapping">
                                                <img class="creditcard width-card" id="image-credit"
                                                    src="/images/creditcard.png" alt="" value="e">
                                            </span>
                                            <input type="text" class="form-control" id="number-card" name="cc-card"
                                                placeholder="xxxx-xxxx-xxxx-xxxx" maxlength="19">
                                        </div>
                                        <div class="mb-3 d-flex gap-4">
                                            <div>
                                                <label for="" class="form-label mb-1">Data</label>
                                                <input type="text" id="data" class="form-control"
                                                    placeholder="01/25" maxlength="5">
                                                <div class="red"></div>
                                            </div>
                                            <div>
                                                <label for="" class="form-label mb-1">CVV</label>
                                                <input type="text" name="" id="cvv" class="form-control"
                                                    placeholder="xxx" maxlength="3">
                                                <div class="red"></div>
                                            </div>
                                        </div>
                                        <div id="error-card" class="red"></div>
                                    </div>
                                    <input type="hidden" id="promotion" name="promotion_hidden">
                                    <input type="hidden" id="nonce" name="payment_method_nonce">
                                    <button type="submit" class="btn btn-primary mb-2 font-size-small"
                                        id="submit-button">Make Payment</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        </section>

        <section>
            <h3 class=" my-4 text-center">Messages</h3>
            <div class="card p-4 shadow">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Sender</th>
                                <th>Subject</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($messages as $message)
                                <tr>
                                    <td class="message-date">
                                        {{ $message->created_at }}
                                    </td>
                                    <td>
                                        {{ $message->sender }}
                                    </td>

                                    <td>{{ $message->subject }} </td>

                                    <td>
                                        <button class="btn btn-sm btn-outline-primary modal-trigger" id="show-messages"
                                            message="{{ $message }}" apartment="{{ $apartment }}">
                                            Show
                                        </button>
                                    </td>
                                </tr>
                                <div class="modal" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <ul>
                                                    <li>
                                                        <span class="fw-bold">Sender : </span>
                                                        <span id="message-sender">{{ $message->sender }}</span>
                                                    </li>
                                                    <li>
                                                        <span class="fw-bold">Date : </span>
                                                        <span id="message-sender"
                                                            class="message-date">{{ $message->created_at }}</span>
                                                    </li>
                                                    <li>
                                                        <span class="fw-bold message-email">Email : </span>
                                                        <span id="message-email">{{ $message->email }}</span>
                                                    </li>
                                                    <li>
                                                        <span class="fw-bold message-subject">Subject : </span>
                                                        <span id="message-subject">{{ $message->subject }}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="modal-body">
                                                <p id="message-content">{{ $message->content }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary close-modal"
                                                    data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            @empty
                                <tr>
                                    <td colspan="5">
                                        <p class="fs-4 text-center p-4 opacity-75">
                                            No Messages
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <div class="">
            <div class="mb-4 row">
                @foreach ($images as $image)
                    <figure class="d-flex justify-content-center col-lg-4 col-12 mt-4">
                        <img class="images" src="{{ asset('storage/images') . '/' . $image->link }}" alt="">
                    </figure>
                @endforeach
            </div>
        </div>


        <div class="d-flex justify-content-center gap-4">
            <a class="btn btn-outline-primary flex-grow-1 shadow"
                href="{{ route('admin.apartments.edit', $apartment) }}">Edit</a>
            <form class="flex-grow-1 shadow" action="{{ route('admin.apartments.destroy', $apartment) }}" method="POST"
                id="deletionForm">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger w-100 modal-trigger" name="{{ $apartment->name }}"
                    address="{{ $apartment->address }}" id="deletion" type="submit"
                    name="{{ $apartment }}">Delete</button>
            </form>
        </div>


    </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const visitsJS = @php echo json_encode($visits); @endphp
    </script>
    @push('scripts')
        <script type="module" src="{{ asset('/js/visitsGraph.js') }}"></script>
    @endpush
    @push('scripts')
        <script src="{{ asset('./js/payment.js') }}"></script>
    @endpush
    @push('scripts')
        <script src="{{ asset('js/deleteModal.js') }}"></script>
    @endpush
    @push('scripts')
        <script src="{{ asset('js/messagesModal.js') }}"></script>
        <script src="{{ asset('js/messagesDateFormat.js') }}"></script>
    @endpush

    <x-delete-modal />
    <style>
        .red {
            color: red;
            border-color: red;
        }

        .green {
            color: green;
        }

        .amount {
            color: grey !important;
            font-size: 7px !important;
        }

        .creditcard {
            width: 30px;
        }

        #data {
            width: 70px;
        }

        #cvv {
            width: 70px
        }

        .font-size-small {
            font-size: 16px;
        }

        .form-control,
        .form-select,
        .input-group-text {
            padding: 10px !important;
            font-size: 18px;
            min-height: 0 !important;
            height: 40px !important;
        }



        .graph-container {
            height: 100%;
        }

        .accordion-body {
            width: 100%;
        }

        .modal-body {
            overflow-x: auto;
        }

        .message-cell {
            max-width: 100px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            cursor: pointer;
        }

        .message-cell:hover {
            text-decoration: aquamarine;
        }

        .apartment-cell {
            display: none;
        }

        .images,
        .cover-image {
            max-width: 100%;
            aspect-ratio: 14/9;
        }

        @media (min-width: 576px) {}

        @media (min-width: 768px) {
            .apartment-cell {
                display: table-cell;
            }
        }

        @media (min-width: 992px) {
            .chart-container {
                height: 424px;
            }

            .cover-image {
                max-width: 50%;
            }
        }

        @media (min-width: 1200px) {
            .chart-container {
                height: 398px;
            }

            .apt-info {
                height: 398px;
            }
        }
    </style>
@endsection

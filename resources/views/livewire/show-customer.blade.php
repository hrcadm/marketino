<head>
    @livewireStyles
</head>
<body>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
      integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

<div class="container py-4">
    <div class="card">

        <div class="card-header">
            <h2 class="absolute">{{ $customer->getFullNameAttribute() }}</h2>
            <div class="float-right">
                <a href="{{ route('customers.edit',$customer)}}" class="btn btn-primary" role="button">Edit</a>
            </div>
        </div>

        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

                <table class="table table-hover">
                <tbody>
                <tr>
                    <td class>Name</td>
                    <td>{{ $customer->first_name }}</td>
                </tr>

                <tr>
                    <td>Last name</td>
                    <td>{{ $customer->last_name }}</td>
                </tr>
                <tr>
                    <td>Company name</td>
                    <td>{{ $customer->company_name }}</td>
                </tr>
                <tr>
                    <td>Vat number</td>
                    <td>{{ $customer->vat_number }}</td>
                </tr>
                <tr>
                    <td>Fiscal number</td>
                    <td>{{ $customer->fiscal_number }}</td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td>{{ $customer->address }}</td>
                </tr>
                @if($deliveryAddress)
                <tr class="toogle" style= "background: lightblue ">
                    <td>
                        Additional address
                    </td>
                    <td>
                        {{ $deliveryAddress->address ?? '' }}
                    </td>
                </tr>

                <tr class="toogle" style="background: lightblue ">
                    <td>
                        Additional address City
                    </td>
                    <td>
                        {{ $deliveryAddress->city ?? '' }}
                    </td>
                </tr>

                <tr class="toogle" style="background: lightblue ">
                    <td>
                        Additional address Zip
                    </td>
                    <td>
                        {{ $deliveryAddress->zip ?? '' }}
                    </td>
                </tr>

                <tr class="toogle" style="background: lightblue ">
                    <td>
                        Additional address Region
                    </td>
                    <td>
                        {{ $deliveryAddress->region ?? '' }}
                    </td>
                </tr>

                <tr class="toogle" style=" background: lightblue ">
                    <td>
                        Additional address name
                    </td>
                    <td>
                        {{ $deliveryAddress->company_name ?? '' }}
                    </td>
                </tr>
                <tr class="toogle" style=" background: lightblue ">
                    <td>
                        Additional address note
                    </td>
                    <td>
                        {{ $deliveryAddress->note ?? '' }}
                    </td>
                </tr>

                @endif
                <tr>
                    <td>Zip</td>
                    <td>{{ $customer->zip }}</td>
                </tr>
                <tr>
                    <td>City</td>
                    <td>{{ $customer->city }}</td>
                </tr>
                <tr>
                    <td>Region</td>
                    <td>{{ $customer->region }}</td>
                </tr>
                <tr>
                    <td>Country code</td>
                    <td>{{ $customer->country_code }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{ $customer->email()->email ?? ''}}</td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td>{{ $customer->phone()->phone ?? '' }}</td>
                </tr>
                <tr>
                    <td>Note</td>
                    <td>{{ $customer->note }}</td>
                </tr>
                <tr>
                    <td>Gdpr</td>
                    <td>{{ $customer->gdpr ? "YES" : "NO" }}</td>
                </tr>
                <tr>
                    <td>Newsletter</td>
                    <td>{{ $customer->newsletter ? "YES" : "NO" }}</td>
                </tr>
                <tr>
                    <td>Sale channel</td>
                    <td>{{ $customer->sale_channel }}</td>
                </tr>
                <tr>
                    <td>Activity type</td>
                    <td>{{ $customer->activity->name ?? ''}}</td>
                </tr>
                <tr>
                    <td>Customer status</td>
                    <td>{{ $customer->customer_status}}</td>
                </tr>
                <tr>
                    <td>PEC / SDI</td>
                    <td>{{ $customer->einvoice_code}}</td>
                </tr>
                <tr>
                    <td>Iban</td>
                    <td>{{ $customer->iban}}</td>
                </tr>
                <tr>
                    <td>Iban name</td>
                    <td>{{ $customer->iban_name}}</td>
                </tr>
                <tr>
                    <td>Company type</td>
                    <td>{{ $customer->company_type}}</td>
                </tr>
                <tr>
                    <td>Company date</td>
                    <td>{{$customer->company_date ? Carbon\Carbon::parse($customer->company_date)->format('d.m.Y') : ''}}</td>
                </tr>
                <tr>
                    <td>Legal contact</td>
                    <td>{{ $customer->legal_contact}}</td>
                </tr>

                <tr>
                    <td>
                        Created by:
                    </td>
                    <td>
                        {{$customer->user->name ?? ''}}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

</body>

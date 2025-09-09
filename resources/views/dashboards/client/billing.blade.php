@extends('dashboards.client.dashboard')

@section('body')

<div class="billing-wrapper">

    {{-- Dropdown Section Selector --}}
    <div class="section-selector">
        <label for="section-switcher">Select a View</label>
        <select id="section-switcher">
            <option value="payment-section" selected>Proceed To Payments</option>
            <option value="history-section">Payment History</option>
        </select>
    </div>

    {{-- Section 1: Proceed To Payments (Default View) --}}
    <div id="payment-section" class="content-section active">
        <div class="payment-container">
            <h2>Proceed To Payments</h2>
            <form action="#" method="post">
                @csrf

                <div class="form-group">
    <label for="job_id">Select Job:</label>
    <select id="job_id" name="job_id" class="payment-method">
        <option value="">-- Select job --</option>
        @foreach (Auth::user()->jobs as $job)
            <option value="{{ $job->id }}" @selected(old('job_id') == $job->id)>
                {{ $job->title }} (Ref: {{ $job->id }})
            </option>
        @endforeach
    </select>
</div>
                <div class="form-group">
                    <label for="payment-method">Choose Payment Method:</label>
                    <select id="payment-method" name="payment-method" class="payment-method">
                        <option value="paypal" selected>PayPal</option>
                        <option value="credit-card">Credit Card</option>
                        <option value="bank-transfer">Bank Transfer</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="cardholder-name">Cardholder Name:</label>
                    <input type="text" id="cardholder-name" name="cardholder-name" placeholder="John M. Doe">
                </div>

                <div class="form-group">
                    <label for="card-number">Card Number:</label>
                    <input type="text" id="card-number" name="card-number" placeholder="1234 5678 9101 1121">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="expiry-date">Expiry date:</label>
                        <input type="text" id="expiry-date" name="expiry-date" placeholder="MM/YY">
                    </div>
                    <div class="form-group">
                        <label for="cvv">CVV:</label>
                        <input type="text" id="cvv" name="cvv" placeholder="123">
                    </div>
                </div>

                <div class="form-group">
                    <label for="amount">Amount:</label>
                    <input type="text" id="amount" name="amount" placeholder="R0.00">
                </div>

                <div class="form-group">
                    <label for="email">Proof of Payment E-mail:</label>
                    <input type="email" id="email" name="email" placeholder="email@example.com">
                </div>
                <div class="form-row button-row">
                    <button type="submit" class="action-btn">Pay Now</button>
                    <a href="{{route('client.dashboard')}}" class="action-btn go-back-link">Go Back</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Section 2: Payment History --}}
    <div id="history-section" class="content-section">
        <div class="payment-container">
            <h2>Payment History</h2>
             <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td data-label="Date">2025-08-15</td>
                            <td data-label="Amount">R1500.00</td>
                            <td data-label="Method">Credit Card</td>
                            <td data-label="Status"><span class="status pending">Pending</span></td>
                        </tr>
                        <tr>
                            <td data-label="Date">2025-07-22</td>
                            <td data-label="Amount">R850.50</td>
                            <td data-label="Method">PayPal</td>
                            <td data-label="Status"><span class="status completed">Paid</span></td>
                        </tr>
                        <tr>
                            <td data-label="Date">2025-07-05</td>
                            <td data-label="Amount">R1200.00</td>
                            <td data-label="Method">Credit Card</td>
                            <td data-label="Status"><span class="status failed">Rejected</span></td>
                        </tr>
                         <tr>
                            <td data-label="Date">2025-06-18</td>
                            <td data-label="Amount">R2500.00</td>
                            <td data-label="Method">Bank Transfer</td>
                            <td data-label="Status"><span class="status pending">Pending</span></td>
                        </tr>
                    </tbody>
                 </table>
             </div>
        </div>
    </div>
</div>

<style>
    *, *::before, *::after {
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f4f4f4;
        color: #333;
        margin: 0;
    }
    
    .welcome-message {
        text-align: center;
        padding: 20px;
        font-size: 1.2em;
    }

    .billing-wrapper {
        width: 100%;
        max-width: 600px;
        margin: 20px auto;
        padding: 0 15px;
    }
    
    .section-selector {
        background-color:  #999;
        padding: 20px;
        border-radius: 12px 12px 0 0;
    }

    .section-selector label {
        display: block;
        font-weight: 500;
        margin-bottom: 8px;
        font-size: 14px;
        color: #fff;
    }

    #section-switcher {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 8px;
        background-color: #ece6e5;
        font-size: 16px;
    }

    .content-section {
        display: none;
    }

    .content-section.active {
        display: block;
    }
   
    .payment-container {
        background-color: #f0f2f5;
        padding: 30px 40px;
        border-radius: 0 0 12px 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        width: 100%;
    }
    
    .payment-method {
        font-size: 14px;
    }

    h2 {
        text-align: center;
        font-weight: bold;
        margin-top: 0;
        margin-bottom: 25px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        font-weight: 500;
        margin-bottom: 5px;
        font-size: 14px;
    }

    input[type="text"],
    input[type="email"],
    select {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 8px;
        background-color: #ece6e5;
        font-family: 'Poppins', sans-serif;
        font-size: 16px;
    }

    input::placeholder {
        color: #999;
    }

    input:focus,
    select:focus {
        outline: 2px solid #ece6e5;
    }

    .form-row {
        display: flex;
        gap: 20px;
    }

    .form-row .form-group {
        flex: 1;
    }
    
    .button-row {
        margin-top: 25px;
        gap: 15px;
    }

    .action-btn {
        flex: 1;
        text-align: center;
        padding: 12px;
        border: none;
        border-radius: 25px;
        background-color: #999;
        color: white;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s ease;
        text-decoration: none;
    }

    .action-btn:hover {
        background-color: #af9797;
    }

    /* Table Styles */
    .table-wrapper {
        overflow-x: auto; /* Ensures table scrolls on very wide content if needed */
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    table th, table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }
    table th {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    table td {
        font-size: 14px;
    }

    /* Status Badge Styles */
    .status {
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 500;
        color: #333;
        display: inline-block;
        white-space: nowrap;
    }
    .completed { background: #d4edda; color: #155724; }
    .pending { background: #fff3cd; color: #856404; }
    .failed { background: #f8d7da; color: #721c24; }

    /* =================================== */
    /* == RESPONSIVE STYLES (MOBILE) == */
    /* =================================== */
    @media screen and (max-width: 768px) {
        .payment-container {
            padding: 25px 20px;
        }

        .form-row {
            flex-direction: column;
            gap: 0; /* Let form-group margin handle the gap */
        }
        
        .button-row {
            gap: 10px; /* Add a small gap between stacked buttons */
        }

        /* Responsive Table to Card Transformation */
        .table-wrapper table {
            border: 0;
        }
        .table-wrapper thead {
            border: none;
            clip: rect(0 0 0 0);
            height: 1px;
            margin: -1px;
            overflow: hidden;
            padding: 0;
            position: absolute;
            width: 1px;
        }
        .table-wrapper tr {
            display: block;
            margin-bottom: 1rem;
            border-radius: 8px;
            background: rgba(0,0,0,0.05);
            border-bottom: 2px solid #79325e;
        }
        .table-wrapper td {
            display: block;
            text-align: right;
            border-bottom: 1px dotted rgba(255, 255, 255, 0.3);
            font-size: 15px;
        }
        .table-wrapper td::before {
            content: attr(data-label);
            float: left;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 13px;
        }
        .table-wrapper td:last-child {
            border-bottom: 0;
        }
        .table-wrapper .status {
            float: right;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const switcher = document.getElementById('section-switcher');
        const sections = document.querySelectorAll('.content-section');

        switcher.addEventListener('change', function () {
            const selectedValue = this.value;
            sections.forEach(section => {
                section.classList.remove('active');
            });
            const activeSection = document.getElementById(selectedValue);
            if (activeSection) {
                activeSection.classList.add('active');
            }
        });
    });
</script>
@endsection
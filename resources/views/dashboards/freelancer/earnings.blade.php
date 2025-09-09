@extends('dashboards.freelancer.dashboard')
@section('body')
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
        }

        body {
            margin: 0;
        }

        .earnings-container {
            font-family: 'Poppins', sans-serif;
            background: #fff;
            color: #333;
            padding: 15px;
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .section-selector {
            margin-bottom: 30px;
            max-width: 100%;
        }

        .section-selector label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 16px;
        }

        #section-switcher {
            width: 30%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            background-color: #f8f9fa;
        }

        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }

        .earnings-cards {
            display:flex; 
           flex-wrap: wrap;           
            gap: 20px;
            margin-bottom: 40px;
        }

        .card {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px;
            background: #f8f9fa;
            border: 2px solid #111;
            border-radius: 12px;
        }

        .card .icon {
            font-size: 24px;
            color: #333;
        }

        .card-content h3 {
            margin: 0;
            font-size: 14px;
            font-weight: 500;
            color: #555;
        }

        .card-content p {
            margin: 5px 0 0 0;
            font-weight: 400;
            font-size: 15px;
        }

        .withdraw-box {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 25px;
            max-width: 500px;
            margin-bottom: 40px;
        }

        .withdraw-box label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .withdraw-box input,
        .withdraw-box select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        .withdraw-box button {
            margin: 0 auto;
            display: block;
            width: 100%;
            max-width: 280px;
            padding: 12px;
            background: #5A31F4;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 400;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .withdraw-box button:hover {
            background: #4A25D4;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table th,
        table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        table th {
            font-size: 12px;
            font-weight: 600;
            color: #666;
            text-transform: uppercase;
        }

        table td {
            font-size: 15px;
        }

        .status {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            white-space: nowrap;
        }

        .completed { background: #e6f7ec; color: #0d803c; }
        .pending { background: #fff8e1; color: #f59e0b; }
        .In-progress { background: rgb(174, 206, 233); color: rgb(20, 75, 112); }
        .failed { background: #fdecea; color: #c53030; }

        /* =================================== */
        /* == RESPONSIVE STYLES (MOBILE) == */
        /* =================================== */
        @media screen and (max-width: 768px) {
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
                background: #f8f9fa;
                border-bottom: 2px solid #5A31F4;
            }
            .table-wrapper td {
                display: block;
                text-align: right;
                border-bottom: 1px dotted #ccc;
                font-size: 14px;
            }
            .table-wrapper td::before {
                content: attr(data-label);
                float: left;
                font-weight: bold;
                text-transform: uppercase;
                font-size: 12px;
                color: #555;
            }
            .table-wrapper td:last-child {
                border-bottom: 0;
            }
            .table-wrapper .status {
                float: right;
            }
        }
    </style>

    <div class="earnings-container">

        <div class="section-selector">
            <label for="section-switcher">Select a View</label>
            <select id="section-switcher">
                <option value="overview-section" selected>Overview & Withdraw</option>
                <option value="history-section">Transaction History</option>
                <option value="pending-payouts-section">Pending Payouts</option>
            </select>
        </div>
{{-- this is for overview-section --}}
        <div id="overview-section" class="content-section active">
            <h2 class="section-title">My Earnings</h2>
            <div class="earnings-cards">
                <div class="card">
                    <i class="fas fa-file-invoice-dollar icon"></i>
                    <div class="card-content">
                        <h3>Total Earnings</h3>
                        <p>R12540.50</p>
                    </div>
                </div>
                <div class="card">
                    <i class="fas fa-calendar-days icon"></i>
                    <div class="card-content">
                        <h3>This Month</h3>
                        <p>R1850.75</p>
                    </div>
                </div>
                <div class="card">
                    <i class="fas fa-wallet icon"></i>
                    <div class="card-content">
                        <h3>Available for Withdrawal</h3>
                        <p>R7320.00</p>
                    </div>
                </div>
            </div>

            <div class="withdraw-box">
                <h3 class="section-title" style="font-size: 20px;">Withdraw Funds</h3>
                <form>
                    @csrf
                    <div>
                        <label for="amount">Amount (USD)</label>
                        <input type="number" id="amount" name="amount" placeholder="0.00">
                    </div>
                    <div>
                        <label for="method">Method</label>
                        <select id="method" name="method">
                            <option value="paypal">PayPal</option>
                            <option value="bank">Bank Transfer</option>
                        </select>
                    </div>
                    <button type="submit">Request Withdrawal</button>
                </form>
            </div>
        </div>
{{-- This is for history-section --}}
        <div id="history-section" class="content-section">
            <h3 class="section-title">Transaction History</h3>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Project</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td data-label="Date">2024-07-15</td>
                            <td data-label="Client"><b>Creative Inc.</b></td>
                            <td data-label="Project">Logo Design</td>
                            <td data-label="Amount">R500.00</td>
                            <td data-label="Status"><span class="status completed">Completed</span></td>
                        </tr>
                        <tr>
                            <td data-label="Date">2025-06-25</td>
                            <td data-label="Client"><b>Eco World</b></td>
                            <td data-label="Project">Blog Content Creation</td>
                            <td data-label="Amount">R420.00</td>
                            <td data-label="Status"><span class="status pending">Pending</span></td>
                        </tr>
                        <tr>
                            <td data-label="Date">2025-07-12</td>
                            <td data-label="Client"><b>Tech Solutions</b></td>
                            <td data-label="Project">Website Redesign</td>
                            <td data-label="Amount">R2500.00</td>
                            <td data-label="Status"><span class="status completed">Completed</span></td>
                        </tr>
                        <tr>
                            <td data-label="Date">2025-08-12</td>
                            <td data-label="Client"><b>Innovate LLC</b></td>
                            <td data-label="Project">Mobile App UI/UX</td>
                            <td data-label="Amount">R3500.00</td>
                            <td data-label="Status"><span class="status In-progress">In Progress</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

{{-- This is for pending-payouts-section --}}
        <div id="pending-payouts-section" class="content-section">
            <h3 class="section-title">Pending Payouts</h3>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Date Requested</th>
                            <th>Project</th>
                            <th>milestone</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payouts as $payout)
                            <tr>
                                <td data-label="Date">{{$payout->requested_at}}</td>
                                <td data-label="Project">{{$payout->contract->job->title}}</</td>
                                <td data-label="milestone">frontpage</td>
                                <td data-label="Amount">{{$payout->amount}}</td>
                                <td data-label="Status"><span class="status pending">{{$payout->status}}</span></td>
                            </tr>
                        @empty
                            <p>
                                No pending payouts found.
                            </p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        
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
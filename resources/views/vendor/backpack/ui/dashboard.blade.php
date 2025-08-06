@extends(backpack_view('blank'))

{{-- @php
    if (backpack_theme_config('show_getting_started')) {
        $widgets['before_content'][] = [
            'type'        => 'view',
            'view'        => backpack_view('inc.getting_started'),
        ];
    } else {
        $widgets['before_content'][] = [
            'type'        => 'jumbotron',
            'heading'     => trans('backpack::base.welcome'),
            'heading_class' => 'display-3 '.(backpack_theme_config('layout') === 'horizontal_overlap' ? ' text-white' : ''),
            'content'     => trans('backpack::base.use_sidebar'),
            'content_class' => backpack_theme_config('layout') === 'horizontal_overlap' ? 'text-white' : '',
            'button_link' => backpack_url('logout'),
            'button_text' => trans('backpack::base.logout'),
        ];
    }
@endphp --}}

@section('content')
<div>
      @php
         use Backpack\CRUD\app\Library\Widget;
         Widget::add()
             ->to('before_content')
             ->type('div')
             ->class('row')
             ->content([
                 // Widget 1
                 Widget::make()
                     ->type('progress')
                     ->class('card shadow rounded p-3 m-2')  //('col-md-4 mb-2')
                     ->value(0)
                     ->description('Total Users')
                     ->progress(5)
                     ->progressClass('progress-bar bg-primary'),
 
                 // Widget 2
                 Widget::make()
                     ->type('progress')
                     ->class('card shodow rounded p-3 m-2')
                     ->value('0')
                     ->description('Active Jobs')
                     ->progress(5)
                     ->progressClass('progress-bar bg-success'),
 
                 // Widget 3
                 Widget::make()
                     ->type('progress')
                     ->class('card shodow rounded p-3 m-2')
                     ->value('$0.00')
                     ->description('Total Revenue')
                     ->progress(5)
                     ->progressClass('progress-bar bg-warning'),

                  // Widget 4
                 Widget::make()
                     ->type('progress')
                     ->class('card shodow rounded p-3 m-2')
                     ->value('0')
                     ->description('Open Disputes')
                     ->progress(5)
                     ->progressClass('progress-bar bg-danger'),
             ]);
             @endphp
     <div>
        <hr>
        <h3>Analytics</h3>
              <div class="row">
            <div class="col-md-3">
                <div class="card p-3 shadow-sm">
                    <h5>Users (Last 7 Days)</h5>
                    <canvas id="usersChart" height="100"></canvas>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 shadow-sm">
                    <h5>Jobs</h5>
                    <canvas id="jobsChart" height="100"></canvas>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 shadow-sm">
                    <h5>Revenue</h5>
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 shadow-sm">
                    <h5>Disputes</h5>
                    <canvas id="disputesChart" height="100"></canvas>
                </div>
            </div>
        </div>
        @endsection
        
        @section('after_scripts')

        {{-- javascript for chart and graphs --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            new Chart(document.getElementById('usersChart'), {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Users',
                        data: [3, 5, 7, 4, 6, 8, 10],
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: '#36A2EB',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                    }]
                }
            });
        
            new Chart(document.getElementById('jobsChart'), {
                type: 'bar',
                data: {
                    labels: ['Active', 'Closed', 'New'],
                    datasets: [{
                        label: 'Jobs',
                        data: [12, 5, 9],
                        backgroundColor: ['#28a745', '#ffc107', '#17a2b8'],
                    }]
                }
            });
        
            new Chart(document.getElementById('revenueChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Jan', 'Feb', 'Mar'],
                    datasets: [{
                        label: 'Revenue',
                        data: [2000, 3000, 2500],
                        backgroundColor: ['#007bff', '#6610f2', '#e83e8c'],
                    }]
                }
            });
        
            new Chart(document.getElementById('disputesChart'), {
                type: 'radar',
                data: {
                    labels: ['Open', 'Resolved', 'Escalated'],
                    datasets: [{
                        label: 'Disputes',
                        data: [5, 8, 2],
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: '#dc3545',
                        borderWidth: 2
                    }]
                }
            });
        </script>      

@endsection

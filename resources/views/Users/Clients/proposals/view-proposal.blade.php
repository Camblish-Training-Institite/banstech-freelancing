{{-- resources/views/proposal.blade.php --}}
@extends('dashboards.client.dashboard')

@section('body')


<!-- Font Awesome Icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<div class="container-fluid">
    <div class="flex w-full justify-start">
        <a href="{{ url()->previous() }}" class="back-link">← Back to Proposals</a>
    </div>
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 main-content-proposal-view">
            <div class="proposal-card">
                <div class="header">
                    <div class="user-profile">
                        <img src="{{ asset('storage/'. $proposal->user->profile->avatar) }}" alt="{{$proposal->user->name}}" class="avatar">
                        <h4>{{$proposal->user->name}}</h4>
                    </div>
                    <div class="action-buttons">
                        <a href="{{ route('client.proposals.accept', $proposal) }}" class="btn btn-accept">
                            <i class="fas fa-check-circle"></i> Accept Proposal
                        </a>
                        <a href="{{ route('client.freelancer.profile', $proposal->user->id) }}" class="btn btn-view">
                            <i class="fas fa-user"></i> View Profile
                        </a>
                        <a href="" class="btn btn-message">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </a>
                    </div>
                </div>

                <div class="proposal-body">
                    <div class="section">
                        <h5>Proposal</h5>
                        @if ($proposal->cover_letter)
                            <p>{{$proposal->cover_letter}}</p>
                        @else
                            <p>no message sent</p>
                        @endif
                    </div>

                    <div class="section">
                        <h5>Skills</h5>
                        <div class="skills-container">
                            @php
                                $jobSkills = $proposal->job->skills ? explode(',', $proposal->job->skills) : [];
                                // dd($jobSkills);
                                $jobSkills = str_replace('\"', '"', $jobSkills); // Replace escaped quotes
                            @endphp
                            @foreach($jobSkills as $skill)
                                <span class="skill-tag">{{ trim($skill) }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="section">
                        <h5>Past Projects</h5>
                        <div class="projects-list">
                            <a href="#" class="project-link">Oplanla Front Page...</a>
                            <a href="#" class="project-link">Camblish Website...</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Style Section -->
<style>
    /* Reset and Base Styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
        color: #333;
    }

    .back-link {
        color: #516aae;
        text-decoration: none;
        font-weight: bold;
        font-size: 16px;
    }
    .back-link:hover {
        text-decoration: underline;
    }

    .container-fluid {
        padding: 0;
    }

    /* proposal_Sidebar Styles */
    .proposal_sidebar {
        background-color: #2c3e50;
        height: 100vh;
        position: fixed;
        width: 250px;
        transition: all 0.3s ease;
        z-index: 1000;
        overflow-y: auto;
    }

    .proposal_sidebar-content {
        padding: 20px;
    }

    .user-info {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
    }

    .profile-pic {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 15px;
    }

    .user-details h5 {
        color: white;
        font-size: 1.1rem;
        margin-bottom: 5px;
    }

    .user-details .email {
        color: #bdc3c7;
        font-size: 0.9rem;
    }

    .menu-section {
        margin-top: 20px;
    }

    .menu-title {
        color: #bdc3c7;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 15px;
    }

    .nav-menu {
        list-style: none;
    }

    .nav-menu li {
        margin-bottom: 15px;
    }

    .nav-menu a {
        color: white;
        text-decoration: none;
        font-size: 1rem;
        padding: 10px 0;
        display: block;
        transition: all 0.3s ease;
        border-radius: 5px;
    }

    .nav-menu a:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: #ecf0f1;
    }

    /* Main Content Styles */
    .main-content-proposal-view {
        padding: 20px;
        width:100%;
    }

    .proposal-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }

    .user-profile {
        display: flex;
        align-items: center;
    }

    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 15px;
    }

    .user-profile h4 {
        font-size: 1.1rem;
        color: #333;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-accept {
        background-color: #28a745;
        color: white;
    }

    .btn-accept:hover {
        background-color: #218838;
    }

    .btn-view {
        background-color: #856404;
        color: white;
    }

    .btn-view:hover {
        background-color: #6d5103;
    }

    .btn-message {
        background-color: #495057;
        color: white;
    }

    .btn-message:hover {
        background-color: #343a40;
    }

    .proposal-body {
        padding: 20px;
    }

    .section {
        margin-bottom: 25px;
    }

    .section h5 {
        font-size: 1rem;
        margin-bottom: 10px;
        color: #333;
    }

    .section p {
        line-height: 1.6;
        color: #555;
    }

    .skills-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }

    .skill-tag {
        background-color: #e9ecef;
        color: #495057;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .skill-tag:hover {
        background-color: #dee2e6;
    }

    .projects-list {
        margin-top: 10px;
    }

    .project-link {
        display: block;
        color: #6c757d;
        text-decoration: none;
        margin-bottom: 5px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .project-link:hover {
        color: #007bff;
        text-decoration: underline;
    }


    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .proposal_sidebar {
            width: 100%;
            height: auto;
            position: relative;
            margin-bottom: 20px;
        }
        
        .main-content-proposal-view {
            margin-left: 0;
            padding: 20px;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 10px;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection

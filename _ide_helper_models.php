<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $client_id
 * @property string $title
 * @property string $description
 * @property string $prize_money
 * @property \Illuminate\Support\Carbon $closing_date
 * @property array<array-key, mixed> $required_skills
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $client
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ContestEntry> $entries
 * @property-read int|null $entries_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ContestSubmission> $submissions
 * @property-read int|null $submissions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contest whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contest whereClosingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contest whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contest wherePrizeMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contest whereRequiredSkills($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contest whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contest whereUpdatedAt($value)
 */
	class Contest extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $contest_id
 * @property int $freelancer_id
 * @property string $title
 * @property string $description
 * @property bool $is_original
 * @property string|null $sell_price
 * @property bool $is_highlighted
 * @property bool $is_sealed
 * @property array<array-key, mixed>|null $files
 * @property bool $is_winner
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Contest $contest
 * @property-read \App\Models\User $freelancer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestEntry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestEntry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestEntry query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestEntry whereContestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestEntry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestEntry whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestEntry whereFiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestEntry whereFreelancerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestEntry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestEntry whereIsHighlighted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestEntry whereIsOriginal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestEntry whereIsSealed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestEntry whereIsWinner($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestEntry whereSellPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestEntry whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestEntry whereUpdatedAt($value)
 */
	class ContestEntry extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $contest_id
 * @property int $freelancer_id
 * @property string $title
 * @property string|null $description
 * @property string|null $file_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $freelancer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestSubmission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestSubmission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestSubmission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestSubmission whereContestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestSubmission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestSubmission whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestSubmission whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestSubmission whereFreelancerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestSubmission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestSubmission whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContestSubmission whereUpdatedAt($value)
 */
	class ContestSubmission extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $job_id
 * @property int $user_id
 * @property string $agreed_amount
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $project_manager_id
 * @property-read \App\Models\User $freelancer
 * @property-read \App\Models\Job $job
 * @property-read \App\Models\User|null $projectManager
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\ContractFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contract newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contract newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contract query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contract whereAgreedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contract whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contract whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contract whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contract whereJobId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contract whereProjectManagerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contract whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contract whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contract whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contract whereUserId($value)
 */
	class Contract extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $contract_id
 * @property int $reporter_id
 * @property string $reason
 * @property string $status
 * @property int|null $admin_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\DisputeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dispute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dispute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dispute query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dispute whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dispute whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dispute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dispute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dispute whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dispute whereReporterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dispute whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dispute whereUpdatedAt($value)
 */
	class Dispute extends \Eloquent {}
}

namespace App\Models{
/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Earning newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Earning newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Earning query()
 */
	class Earning extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $description
 * @property string|null $budget
 * @property string $status
 * @property array<array-key, mixed>|null $skills
 * @property string|null $deadline
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\JobFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereSkills($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereUserId($value)
 */
	class Job extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $client_id
 * @property int $contract_id
 * @property int|null $project_manager_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $client
 * @property-read \App\Models\Contract|null $project
 * @property-read \App\Models\User|null $projectManager
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementRequest whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementRequest whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementRequest whereProjectManagerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementRequest whereUpdatedAt($value)
 */
	class ManagementRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $freelancer_id
 * @property string $amount
 * @property string $status
 * @property string|null $requested_at
 * @property string|null $processed_at
 * @method static \Database\Factories\PayoutFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payout newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payout newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payout query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payout whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payout whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payout whereFreelancerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payout whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payout whereProcessedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payout whereRequestedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payout whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payout whereUpdatedAt($value)
 */
	class Payout extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string|null $description
 * @property string|null $file_url
 * @property string|null $imageURL
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\PortfolioFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereFileUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereImageURL($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portfolio whereUserId($value)
 */
	class Portfolio extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $bio
 * @property string|null $address
 * @property string|null $city
 * @property string|null $zip_code
 * @property string|null $state
 * @property string|null $country
 * @property string|null $company
 * @property string|null $location
 * @property string|null $timezone
 * @property string|null $avatar
 * @property string|null $hourly_rate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $full_name
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereHourlyRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereZipCode($value)
 */
	class Profile extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $job_id
 * @property string $bid_amount
 * @property string|null $cover_letter
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ProposalFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Proposal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Proposal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Proposal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Proposal whereBidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Proposal whereCoverLetter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Proposal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Proposal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Proposal whereJobId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Proposal whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Proposal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Proposal whereUserId($value)
 */
	class Proposal extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string|null $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ReportFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereUserId($value)
 */
	class Report extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $contract_id
 * @property int $job_id
 * @property int $user_id
 * @property int $rating
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ReviewFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereJobId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereUserId($value)
 */
	class Review extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\SkillFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skill newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skill newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skill query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skill whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skill whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skill whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skill whereUpdatedAt($value)
 */
	class Skill extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @property string|null $start_date
 * @property string|null $end_date
 * @property int $is_active
 * @property string $payment_method
 * @method static \Database\Factories\SubscriptionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereUserId($value)
 */
	class Subscription extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $contract_id
 * @property string $amount
 * @property string $payment_gateway
 * @property string|null $gateway_transaction_id
 * @property string $status
 * @method static \Database\Factories\TransactionsFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transactions newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transactions newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transactions query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transactions whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transactions whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transactions whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transactions whereGatewayTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transactions whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transactions wherePaymentGateway($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transactions whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transactions whereUpdatedAt($value)
 */
	class Transactions extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $user_type
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Contract> $assignedProjects
 * @property-read int|null $assigned_projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Contract> $contractsAsFreelancer
 * @property-read int|null $contracts_as_freelancer_count
 * @property-read mixed $role_names
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Job> $jobsPosted
 * @property-read int|null $jobs_posted_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Contract> $managedProjects
 * @property-read int|null $managed_projects_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ManagementRequest> $pendingManagementRequests
 * @property-read int|null $pending_management_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \App\Models\Profile|null $profile
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Proposal> $proposals
 * @property-read int|null $proposals_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $skill_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $skills
 * @method static \Database\Factories\UserSkillFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSkill newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSkill newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSkill query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSkill whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSkill whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSkill whereSkillId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSkill whereSkills($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSkill whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSkill whereUserId($value)
 */
	class UserSkill extends \Eloquent {}
}

namespace App\Models{
/**
 * @method static \Database\Factories\UserTypeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserType query()
 */
	class UserType extends \Eloquent {}
}


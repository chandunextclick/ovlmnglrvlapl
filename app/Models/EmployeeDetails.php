<?php

namespace App\Models;

use App\Scopes\ActiveScope;
use App\Traits\CustomFieldsTrait;
use App\Traits\HasCompany;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\EmployeeDetails
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $employee_id
 * @property string|null $address
 * @property float|null $hourly_rate
 * @property string|null $slack_username
 * @property int|null $department_id
 * @property int|null $designation_id
 * @property string|null $guardian
 * @property string|null $aadhar
 * @property string|null $accountno
 * @property string|null $panno
 * @property string|null $esino
 * @property string|null $nominee
 * @property string|null $nomineerelation
 * @property string|null $nomineemobile
 * @property string|null $nomineeaadhar
 * @property string|null $nomineeaddress
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon $joining_date
 * @property \Illuminate\Support\Carbon|null $last_date
 * @property int|null $added_by
 * @property int|null $last_updated_by
 * @property-read \App\Models\Team|null $department
 * @property-read \App\Models\Designation|null $designation
 * @property-read mixed $extras
 * @property-read mixed $icon
 * @property-read \App\Models\User $user
 * @method static Builder|EmployeeDetails newModelQuery()
 * @method static Builder|EmployeeDetails newQuery()
 * @method static Builder|EmployeeDetails query()
 * @method static Builder|EmployeeDetails whereAddedBy($value)
 * @method static Builder|EmployeeDetails whereAddress($value)
 * @method static Builder|EmployeeDetails whereCreatedAt($value)
 * @method static Builder|EmployeeDetails whereDepartmentId($value)
 * @method static Builder|EmployeeDetails whereDesignationId($value)
 * @method static Builder|EmployeeDetails whereEmployeeId($value)
 * @method static Builder|EmployeeDetails whereHourlyRate($value)
 * @method static Builder|EmployeeDetails whereId($value)
 * @method static Builder|EmployeeDetails whereJoiningDate($value)
 * @method static Builder|EmployeeDetails whereLastDate($value)
 * @method static Builder|EmployeeDetails whereLastUpdatedBy($value)
 * @method static Builder|EmployeeDetails whereSlackUsername($value)
 * @method static Builder|EmployeeDetails whereUpdatedAt($value)
 * @method static Builder|EmployeeDetails whereUserId($value)
 * @mixin \Eloquent
 * @property string|null $attendance_reminder
 * @method static Builder|EmployeeDetails whereAttendanceReminder($value)
 * @property \Illuminate\Support\Carbon|null $date_of_birth
 * @method static Builder|EmployeeDetails whereDateOfBirth($value)
 * @property int|null $company_id
 * @property string|null $calendar_view
 * @property string|null $about_me
 * @property int|null $reporting_to
 * @property-read \App\Models\Company|null $company
 * @property-read mixed $upcoming_birthday
 * @property-read \App\Models\User|null $reportingTo
 * @method static Builder|EmployeeDetails whereAboutMe($value)
 * @method static Builder|EmployeeDetails whereCalendarView($value)
 * @method static Builder|EmployeeDetails whereCompanyId($value)
 * @method static Builder|EmployeeDetails whereReportingTo($value)
 */
class EmployeeDetails extends BaseModel
{

    use CustomFieldsTrait, HasCompany;

    protected $table = 'employee_details';

    protected $dates = ['joining_date', 'last_date', 'date_of_birth'];

    protected $with = ['designation', 'company', 'department'];

    protected $appends = ['upcoming_birthday'];

    const CUSTOM_FIELD_MODEL = 'App\Models\EmployeeDetails';

    protected $casts = [
        'calendar_view	' => 'array'
    ];

    public function getUpcomingBirthdayAttribute()
    {
        if (is_null($this->date_of_birth)) {
            return null;
        }

        $dob = Carbon::parse(now($this->company->timezone)->year . '-' . $this->date_of_birth->month . '-' . $this->date_of_birth->day);

        if ($dob->isPast()) {
            $dob->addYear();
        }

        return $dob->toDateString();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScope(ActiveScope::class);
    }

    public function reportingTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporting_to')->withoutGlobalScope(ActiveScope::class);
    }

    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'department_id');
    }
    public static function getMaxEmployeeId()
    {
        return static::max('employee_id');
    }

}

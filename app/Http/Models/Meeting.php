<?php

namespace App\Http\Models;

use App\Mail\MeetingUpdate;
use App\User;
use marcusvbda\vstack\Models\DefaultModel;
use marcusvbda\vstack\Models\Scopes\TenantScope;
use marcusvbda\vstack\Models\Observers\TenantObserver;
use Auth;
use Spatie\CalendarLinks\Link;
use Spatie\GoogleCalendar\Event;

class Meeting extends DefaultModel
{
	protected $table = "meetings";
	protected $dates = ["created_at", "updated_at", "starts_at", "ends_at", "customer_url_attendance"];
	public $appends = ["code", "f_starts_at", "f_ends_at"];

	public static function boot()
	{
		$user = Auth::user();
		parent::boot();
		if (Auth::check()) {
			if (Auth::user()->hasRole(["admin", "user"])) {
				static::observe(new TenantObserver());
				static::addGlobalScope(new TenantScope());
			}
		}
		self::creating(function ($model) use ($user) {
			$model->user_id = $user->id;
		});

		self::created(function ($model) {
			$model->customer->appendToTimeline(...$model->makeHistoryText("created"));
		});

		// self::saving(function ($model) {
		//     if ($model->getOriginal("starts_at") != $model->starts_at || $model->getOriginal("ends_at") != $model->ends_at) {
		//         $blocking = Meeting::byBusy(Meeting::class, $model->starts_at, $model->ends_at)
		//             ->where("id", "!=", $model->id)
		//             ->where("customer_id", $model->customer_id)
		//             ->where("meeting_room_id", $model->meeting_room_id)
		//             ->first();
		//         if ($blocking) {
		//             abort(402, "Data e horário já ocupados por " . $blocking->subject);
		//         }
		//     }
		// });
	}

	public function getFStartsAtAttribute()
	{
		return $this->starts_at->format("d/m/Y - H:i:s");
	}

	public function getFEndsAtAttribute()
	{
		return $this->ends_at->format("d/m/Y - H:i:s");
	}


	public function makeHistoryText($type)
	{
		if ($type == "created") {

			if ($this->google_event_id) {
				return ["Reunião Criada", "A reunião criada, iniciando "  . $this->getMeetingTimeText()];
			}
			return ["Reunião Criada", "A reunião foi criada passada para o status " . $this->status->name . "."];
		}
	}

	public function responsible()
	{
		return $this->belongsTo(User::class, "user_id");
	}

	public function updateCustomerTimeline($text)
	{
		$user = Auth::user();
		$model = $this;
		$model->customer->appendToTimeline(
			"Responsável : <b>{$user->name}</b></br>",
			"Reunião Atualizada pelo Usuário <b>{$user->name}</b>",
			"Status: " . $model->status->name .
				".</br></br>Descrição da atualização: </br>" .
				'<blockquote>
                <p><em>"' . $text . '"</em></p>
            </blockquote>'
		);
	}

	public function getMeetingTimeText()
	{
		return $this->starts_at->format("d/m/Y, \d\\e H:i") . " até " .  $this->ends_at->format("H:i");
	}

	public function status()
	{
		return $this->belongsTo(MeetingStatus::class);
	}

	public function customer()
	{
		return $this->belongsTo(Customer::class);
	}


	public static function hasTenant()
	{
		return true;
	}

	public function room()
	{
		return $this->belongsTo(MeetingRoom::class, "meeting_room_id");
	}

	public function getEventAttribute()
	{
		return Event::find($this->google_event_id);
	}

	public function createEvent()
	{
		$model = $this;
		if ($model->google_event_id) return $model->event;
		$event = Event::create([
			'name' => $model->subject,
			'startDateTime' => $model->starts_at,
			'endDateTime' => $model->ends_at,
			'location' => $model->room->f_address,
			'visibility' => "public"
			//"attendees" => [["email" => $model->customer->email]],
		]);
		$model->google_event_id = $event->id;
		$model->saveOrFail();
		return $event;
	}

	public function getFMeetingDurationAttribute()
	{
		$start = $this->starts_at->setTime(0, 0);

		return [$this->starts_at->diffInMinutes($start) / 60, $this->ends_at->diffInMinutes($start) / 60];
	}

	public function sendUpdateEmail($subject, $appendBody, $config = [])
	{
		if (!trim($subject)) {
			$subject = "Reunião: " . $this->subject;
		}
		return \Mail::to($this->responsible->email)
			// ->bcc($this->customer->email)
			->send(new MeetingUpdate($this, $subject, $appendBody, $config));
	}

	public function makeEventLink()
	{
		$link = Link::create($this->subject, $this->starts_at, $this->ends_at)
			//->description('Cookies & cocktails!')
			->address($this->room->f_address);

		return $link->google();
	}

	public function scopeByBusy($query, $starts_at, $ends_at)
	{
		return $query->whereBetween('starts_at', [$starts_at, $ends_at])
			->orWhereBetween('ends_at', [$starts_at, $ends_at])
			->orWhereRaw('? BETWEEN starts_at and ends_at', [$starts_at])
			->orWhereRaw('? BETWEEN starts_at and ends_at', [$ends_at]);
	}

	public function getcustomerUrlAttendanceAttribute()
	{
		$customer = $this->customer;
		return "<a href='/admin/customers/{$customer->code}/attendance#meetings' class='link' target='_BLANK'>{$customer->name}</a>";
	}
}
<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailPasswordReset extends Mailable
{
    use Queueable, SerializesModels;

	protected $user;
	protected $project;

	/**
	 * Create a new message instance.
	 *
	 * @param User $user
	 * @param Project $project
	 */
    public function __construct($user, $project)
    {
	    $this->user = $user;
	    $this->project = $project;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
	    return $this->view('emails.user.password_reset')->from("info@dbs.org", $this->project->name)->subject(trans('auth.reset_email_heading', [], $this->project->iso))->with(['user' => $this->user,'project' => $this->project]);
    }
}

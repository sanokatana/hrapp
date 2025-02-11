<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CutiApprovalNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $leaveApplication;
    public $showApprovalButtons;
    public $approveUrl;
    public $denyUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($leaveApplication, $approveUrl, $denyUrl, $showApprovalButtons)
    {
        $this->leaveApplication = $leaveApplication;
        $this->approveUrl = $approveUrl;
        $this->denyUrl = $denyUrl;
        $this->showApprovalButtons = $showApprovalButtons;
    }


    /**
     * Build the email.
     */
    public function build()
    {
        return $this->subject('Pengajuan Cuti Baru Dari ' . $this->leaveApplication->nama_karyawan . ' - ' . $this->leaveApplication->date_now)
            ->view('emails.cuti_approval')
            ->with([
                'leaveApplication' => $this->leaveApplication,
                'showApprovalButtons' => $this->showApprovalButtons,
                'approveUrl' => $this->approveUrl,
                'denyUrl' => $this->denyUrl
            ]);
    }

}

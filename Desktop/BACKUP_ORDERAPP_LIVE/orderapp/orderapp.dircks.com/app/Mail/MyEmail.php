<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MyEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $final_value;
    public $vehicles;
    public $pickupAddress;
    public $deliveryAddress;
    public $trailer_type;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name,$finalValue,$vehicles, $pickupAddress, $deliveryAddress, $trailer_type)
    {
        //
        $this->name = $name;
        $this->final_value = $finalValue;
        $this->vehicles = $vehicles;
        $this->pickupAddress = $pickupAddress;
        $this->deliveryAddress = $deliveryAddress;
        $this->trailer_type = $trailer_type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    // public function build()
    // {
    //     return $this->view('mail.email_template');

    //     // return $this->from('cketul50@gmail.com', 'Xelentor Technologies')
    //     //              ->view('emails.example');
    // }

        public function build()
    {
        return $this->from('auto@dircks.com', 'Dircks Auto Shipping')
                    ->subject("Shipping Quotation From Dircks Auto Shipping")
                    ->view('mail.email_template')
                    ->with([
                        'name' => $this->name,
                        'final_value' => $this->final_value,
                        'vehicles' => $this->vehicles,
                        'pickupAddress' => $this->pickupAddress,
                        'deliveryAddress' => $this->deliveryAddress,
                        'trailer_type' => $this->trailer_type,
                    ]);
    }

}

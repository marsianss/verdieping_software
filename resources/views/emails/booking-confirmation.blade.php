<x-mail::message>
# Booking Confirmation - Windkracht-12 Kitesurfing School

Dear {{ $booking->user->name }},

Thank you for booking a kitesurfing lesson with Windkracht-12! Your booking has been confirmed and we're looking forward to seeing you on the water.

## Booking Details

**Booking ID:** {{ $booking->id }}  
**Lesson:** {{ $booking->lesson->name }}  
**Date:** {{ \Carbon\Carbon::parse($booking->date)->format('l, F j, Y') }}  
**Time:** {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}  
**Instructor:** {{ $booking->instructor->name }}  
**Participants:** {{ $booking->participants }}  
**Total Price:** €{{ number_format($booking->total_price, 2) }}

<x-mail::panel>
**Important Information:**  
Please arrive 30 minutes before your scheduled lesson time for equipment fitting and safety briefing.
</x-mail::panel>

## What to Bring

- Swimwear to wear under wetsuit
- Towel
- Sunscreen
- Water bottle
- Change of clothes for after the lesson

## Location

Windkracht-12 Kitesurfing School  
Strandweg 86  
2586 JT Den Haag  
The Netherlands

<x-mail::button :url="route('bookings.show', $booking)">
View Booking Details
</x-mail::button>

If you need to change or cancel your booking, please visit our website or contact us directly. Please note that cancellations within 24 hours of the scheduled lesson are subject to our cancellation policy.

If you have any questions, feel free to contact us at info@windkracht-12.nl or call us at +31 70 123 4567.

We look forward to seeing you soon and helping you enjoy the thrill of kitesurfing!

Regards,  
The Windkracht-12 Team

<small>This is an automated email. Please do not reply to this message.</small>
</x-mail::message>
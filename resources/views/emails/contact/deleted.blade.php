@component('mail::message')
  # Contact was deleted

  @component('mail::panel')
    ##  Details of contact
    name: {{$contact['name']}}<br>
    email: {{$contact['email']}}
  @endcomponent

  Thanks,<br>
  {{ config('app.name') }}
@endcomponent

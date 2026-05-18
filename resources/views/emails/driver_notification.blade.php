<p>Hello {{ $coordinator->name ?? 'Coordinator' }},</p>

@if(!empty($isUpdate))
<p><strong>Update:</strong> The following training's driver arrangement has been edited, and this is a separate update notification.</p>
@else
<p>You have been assigned as the coordinator for the following training's driver arrangement:</p>
@endif

<ul>
    <li><strong>Course:</strong> {{ $training->course->course_name ?? 'N/A' }}</li>
    <li><strong>Company:</strong> {{ $training->company->company_name ?? 'N/A' }}</li>
    <li><strong>Facilitator:</strong> {{ $training->facilitator->name ?? 'N/A' }}</li>
    <li><strong>Location:</strong> {{ $training->location ?? 'N/A' }}</li>
    <li>
        <strong>Schedule:</strong>
        {{ $training->schedule->from_date ?? '' }}
        {{ $training->schedule->from_time ?? '' }}
        to
        {{ $training->schedule->to_date ?? '' }}
        {{ $training->schedule->to_time ?? '' }}
    </li>
</ul>

<h4>Driver Arrangement</h4>

<ul>
    <li>
        <strong>Transportation Needed:</strong>
        {{ $training->need_transportation ? 'Yes' : 'No' }}
    </li>

    @if($training->need_transportation)

        @if(!empty($training->outbound_pickup_time))
            <li>
                <strong>Outbound Pickup Time:</strong>
                {{ $training->outbound_pickup_time }}
            </li>
        @endif

        @if(!empty($training->outbound_contact_number))
            <li>
                <strong>Outbound Contact Number:</strong>
                {{ $training->outbound_contact_number }}
            </li>
        @endif

        @if(!empty($training->outbound_pickup_location))
            <li>
                <strong>Outbound Pickup Location:</strong>
                {{ $training->outbound_pickup_location }}
            </li>
        @endif

        @if(!empty($training->outbound_dropoff_location))
            <li>
                <strong>Outbound Dropoff Location:</strong>
                {{ $training->outbound_dropoff_location }}
            </li>
        @endif

        <li>
            <strong>Return Trip Needed:</strong>
            {{ $training->return_trip_needed ? 'Yes' : 'No' }}
        </li>

        @if($training->return_trip_needed)

            @if(!empty($training->return_pickup_time))
                <li>
                    <strong>Return Pickup Time:</strong>
                    {{ $training->return_pickup_time }}
                </li>
            @endif

            @if(!empty($training->return_contact_number))
                <li>
                    <strong>Return Contact Number:</strong>
                    {{ $training->return_contact_number }}
                </li>
            @endif

            @if(!empty($training->return_pickup_location))
                <li>
                    <strong>Return Pickup Location:</strong>
                    {{ $training->return_pickup_location }}
                </li>
            @endif

            @if(!empty($training->return_dropoff_location))
                <li>
                    <strong>Return Dropoff Location:</strong>
                    {{ $training->return_dropoff_location }}
                </li>
            @endif

        @endif

    @endif
</ul>

<p>
    Please make the necessary arrangements for:
    <strong>{{ $training->course->course_name ?? 'Training' }}</strong>.
</p>

<p>
    Regards,<br>
    ALPS Calendar
</p>
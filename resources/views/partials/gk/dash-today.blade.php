<section class="gk-dash-section">
    <h2 class="gk-dash-h">Today — who is out</h2>
    @if (count($todayPeople))
        <div class="gk-table-wrap">
            <table class="gk-data-table">
                <thead>
                    <tr>
                        <th>Person</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($todayPeople as $row)
                        <tr>
                            <td>{{ $row['name'] }}</td>
                            <td><span class="gk-dash-tag gk-dash-tag--{{ $row['status'] }}">{{ $row['label'] }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="gk-dash-empty">No WFH, leave, SL, or last day logged for today.</p>
    @endif
</section>

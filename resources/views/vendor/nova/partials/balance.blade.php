@php
    $model = getModelByUserType(user()->type);
    $userCast = $model::find(user()->id);
@endphp

<div class="flex items-center mr-3">
    <img
        class="h-5 mr-3" src="{{ asset('images/icons/coin-stack.svg') }}"
        alt="coin-stack"
    />
    <span id="user-current-balance" class="font-medium">
        {{ priceFormat($userCast->getCurrentBalance() ?? 0, $userCast->currency) }}
    </span>
</div>

<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script type="text/javascript">

    let userId = '{{user()->id}}';
    let type = '{{user()->type}}';
    let envId = '{{user()->environment_id}}';
    let pusherKey = '{{env('MIX_PUSHER_APP_KEY')}}';
    let pusherHost = '{{env('MIX_PUSHER_HOST')}}';
    let pusherPort = '{{env('MIX_PUSHER_PORT')}}';

    if (type !== 'company' && type !== 'developer') {
        // setInterval(function () {
        //     axios.post('/refresh-balance/' + userId + '/' + type).then(function (response) {
        //         document.getElementById('user-current-balance').innerHTML = response.data.balance;
        //     });
        // }, 5000)

        let client = new Pusher(pusherKey, {
            wsHost: pusherHost,
            wsPort: pusherPort,
            wssPort: pusherPort,
            forceTLS: false,
            encrypted: true,
            disableStats: true,
            enabledTransports: ['ws', 'wss'],
        });

        client.subscribe('user.' + envId).bind('balance.refresh.' + userId, message => {
            document.getElementById('user-current-balance').innerHTML = message.balance;
        });

        client.connection.bind('state_change', state => console.log(state));

        //test event
        // client.subscribe('dragon_tiger.table.0').bind('ticket.created', payload => {
        //     console.log('ticket.created', payload);
        // });
        //
        // client.subscribe('dragon_tiger.table.0').bind('game.created', payload => {
        //     console.log('game.created', payload);
        // });
    }

</script>

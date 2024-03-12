<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Whales Market | {{ $user->public_name }} > Support Ticket</title>
    @if ($user->theme == 'dark')
    <link rel="stylesheet" href="{{ asset('dark.theme.css') }}">
@else
    <link rel="stylesheet" href="{{ asset('white.theme.css') }}">
@endif
<link rel="stylesheet" href="{{ asset('market.white.css') }}">
<meta http-equiv="refresh" content="{{ session('session_timer') }};url=/kick/{{ $user->public_name }}/out">

    <link rel="stylesheet" href="{{ asset('filter.css') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
</head>

<body>
    @include('User.navebar')
    <div class="container">
        <div class="main-div">
            <div class="notific-container">
                <header>
                    <h1>Bug Bounty Program</h1>
                </header>
            
                <section>
                    <h2>Bug Bounty Program Guidelines</h2>
                    <p>Dear Whales Market Users,</p>
                    <p>We value the security and integrity of our platform, and we encourage our users to actively participate in making Whales Market a safer environment for everyone. As part of our commitment to security, we have established a Bug Bounty Program that allows users to submit valid bugs for review and rewards.</p>
            
                    <h3>Scope of Acceptance</h3>
                    <ul>
                        <li>We accept bug submissions related to the Whales Market platform.</li>
                        <li>Bugs should be reported through our official Bug Submission Form.</li>
                    </ul>
            
                    <h3>Eligible Bug Types</h3>
                    <ul>
                        <li>Withdraw</li>
                        <li>Deposit</li>
                        <li>Server</li>
                        <li>PGP Key Failed</li>
                        <li>Account Issue</li>
                        <li>Others</li>
                    </ul>
            
                    <h3>Valid Bug Criteria</h3>
                    <ul>
                        <li>Bugs must be original and previously unreported.</li>
                        <li>The bug should be specific and reproducible, providing clear steps to reproduce.</li>
                        <li>Submit only legitimate security vulnerabilities; refrain from exploiting, damaging, or sharing the vulnerability details publicly.</li>
                        <li>Do not attempt to access or manipulate user data without explicit consent.</li>
                    </ul>
            
                    <h3>Bug Submission Process</h3>
                    <ul>
                        <li>Use our official Bug Submission Form for reporting bugs.</li>
                        <li>Clearly specify the bug type and provide detailed steps to reproduce the issue.</li>
                        <li>Provide clear information that can assist in understanding and reproducing the bug.</li>
                    </ul>
            
                    <h3>Rewards</h3>
                    <ul>
                        <li>The Whales Market team will assess the severity and impact of the reported bug.</li>
                        <li>Rewards will be determined based on the significance of the vulnerability.</li>
                        <li>Reward amounts may vary and will be at the discretion of the Whales Market security team.</li>
                    </ul>
                </section>
                <style>
            
                    section {
                        max-width: 800px;
                        margin: 20px auto;
                        background-color: var(--white-background-color);
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    }
            
                    h3 {
                        border-bottom: 2px solid #ddd;
                        padding-bottom: 10px;
                        margin-bottom: 20px;
                        text-align: left;
                    }
            
                    p {
                        line-height: 1.6;
                    }
            
                    ul {
                        list-style-type: none;
                        padding: 0;
                    }
            
                    li {
                        margin-bottom: 10px;
                    }
                </style>
                <h1 class="notifications-h1" style="margin:0; padding:0px;;">_Bug Reporting Form_</h1>

                @if ($errors->any)
                    @foreach ($errors->all() as $error)
                        <p style="color: red; text-align:center">{{ $error }}</p>
                    @endforeach
                @endif
                @if (session('success'))
                    <p style="color: green; text-align:center;">{{ session('success') }}</p>
                @endif

                <form action="" method="post" class="support-form">
                    @csrf
                    <select class="select-opt" name="type" id="" required>
                        <option value="">---Select Bug Type---</option>
                        <option value="withdraw">Withdraw</option>
                        <option value="deposit">Deposit</option>
                        <option value="server">Server</option>
                        <option value="key failed">PGP key failed</option>
                        <option value="account  issue">Account issue</option>
                        <option value="others">Others</option>
                    </select> <br> <br>
                    <textarea name="content" class="support-msg"
                        placeholder="Well Written Report Message here... min 500 characters and less than 5K characters!" required></textarea>

                        <div id="capatcha-code-img">
                            <img src="/user/captcha" alt="Captcha Image">
                            <input type="text" id="captcha" maxlength="8" minlength="8" name="captcha"
                                placeholder="Captcha..." required>
                        </div>
                        
                    <input type="submit" class="submit-nxt" name="send_report" value="Send">
                </form>
            </div>
        </div>
    </div>
    @include('User.footer')
</body>

</html>

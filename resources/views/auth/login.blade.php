<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>VRMS Login</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/common.css') }}">
    <script type="text/javascript" src="{{ asset('jQuery/jquery-1.7.1.min.js') }}"></script>

    <style type="text/css">
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        #banner-header {
            background-color: #2d0662;
            padding: 0;
            margin: 0;
            text-align: center;
        }
        #banner-header img { max-width: 100%; height: auto; display:block; }

        #box-login {
            width: 50%;
            max-width: 520px;
            margin: 2% auto;
            background-color: #ffffff;
            padding: 0;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .box-title {
            color: #ffffff;
            font-weight: bold;
            background-color: #2d0662;
            padding: 10px 14px;
            font-size: 16px;
        }

        .box-content { padding: 16px; }

        table { width: 100%; border-collapse: collapse; }
        .td-left {
            text-align: right;
            padding-right: 10px;
            font-weight: bold;
            white-space: nowrap;
            width: 38%;
            vertical-align: middle;
        }
        .td-right { text-align: left; }

        #info {
            margin-left: 5px;
            color: #999999;
            font-style: italic;
            display: none;
            font-size: 12px;
        }

        select, input[type="text"], input[type="password"] {
            width: 100%;
            padding: 9px 10px;
            margin: 4px 0;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 14px;
        }
        select:focus, input:focus { outline: 2px solid #96b0ff; border-color: #5b8aff; }

        .btn {
            padding: 10px 16px;
            border-radius: 8px;
            cursor: pointer;
            border: 1px solid #d1d5db;
            background: #fff;
        }
        .btn-primary {
            background: #2d0662; color: #fff; border: 1px solid #1a0036; font-weight: 700;
        }
        .btn-primary:hover { background:#4a1899; }
        .btn + .btn { margin-left: 8px; }

        .footer-text {
            text-align: center;
            font-style: italic;
            margin: 16px 0 24px;
            color: #555;
            padding: 0 12px;
        }

        .alert-error {
            color: #b91c1c;
            background:#fef2f2;
            border:1px solid #fecaca;
            border-radius:8px;
            padding:8px 12px;
            text-align: center;
            font-weight: bold;
            margin: 10px 14px 0;
        }

        /* Password row: input + toggle button inline */
        .pw-wrap { display:flex; align-items:center; gap:8px; }
        .pw-toggle {
            white-space: nowrap;
            border:1px solid #d1d5db;
            background:#f7f7f9;
            padding:8px 12px;
            border-radius:8px;
            cursor:pointer;
            font-size: 13px;
        }

        /* Mobile: form sits lower and fills width */
        @media (max-width: 768px) {
            #box-login { width: 92%; margin: 20vh auto 16px; } /* lower on screen */
            .td-left { text-align: left; width: 100%; display:block; padding-right: 0; margin-top: 6px; }
            .td-right { display:block; }
            table tr { display:block; margin-bottom: 8px; }
        }
    </style>
</head>
<body>

    <div id="banner-header">
        <img id="logo" src="{{ asset('images/bannervrms.png') }}" alt="VRMS Banner">
    </div>

    <div id="box-login">
        @if(session('error'))
            <div class="alert-error" id="IdErr" aria-live="polite">{{ session('error') }}</div>
        @endif

        <div class="box-title">Login</div>
        <div class="box-content">
            <form method="POST" action="{{ route('login.custom') }}">
                @csrf
                <table cellpadding="3" cellspacing="0" border="0">
                    <tr>
                        <td class="td-left">Login Type:</td>
                        <td class="td-right">
                            <select id="usertype" name="role" aria-label="Login Type">
                                <option value="STAFF">STAFF</option>
                                <option value="STUDENT">STUDENT</option>
                                <option value="ADMIN">ADMIN</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td class="td-left" id="label-username">Username:</td>
                        <td class="td-right">
                            <input type="text" id="username" name="username" required autocomplete="username">
                        </td>
                    </tr>

                    <tr>
                        <td class="td-left" id="label-password">Password:</td>
                        <td class="td-right">
                            <div class="pw-wrap">
                                <input type="password" id="userpwd" name="password" required autocomplete="current-password">
                                <button type="button" id="togglePw" class="pw-toggle" aria-controls="userpwd" aria-pressed="false">Show</button>
                            </div>
                            <span id="info">- eg: 901205015048</span>
                        </td>
                    </tr>

                    <tr>
                        <td class="td-left">&nbsp;</td>
                        <td class="td-right">
                            <input type="submit" class="btn btn-primary" value="Login">
                            <input type="reset" class="btn" value="Reset">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

    <div class="footer-text">
        Untuk sebarang pertanyaan dan aduan berkenaan VRMS, sila emel ke <b>fleet@utm.my</b>
    </div>

    <script type="text/javascript">
        $(function () {
            // focus first input
            $('input')[0] && $('input')[0].focus();

            // fade out error
            $('#IdErr').delay(8000).fadeOut(800);

            // reset -> re-apply role logic
            $('input[type=reset]').click(function () {
                setTimeout(function(){ $('#usertype').trigger('change'); }, 0);
            });

            // role change behavior
            $('#usertype').on('change', function () {
                var type = $(this).val();

                if (type === 'STUDENT') {
                    $('#label-username').text('Matric No:');
                    $('#label-password').text('New IC/Passport No:');
                    $('#info').show();

                    changeInputType(document.getElementById('userpwd'), 'text');
                    // hide toggle for student text field
                    $('#togglePw').hide();
                } else {
                    $('#label-username').text('Username:');
                    $('#label-password').text('Password:');
                    $('#info').hide();

                    changeInputType(document.getElementById('userpwd'), 'password');
                    // show toggle for password field
                    $('#togglePw').show();
                }
            }).trigger('change');

            // show/hide password (staff/admin only)
            $('#togglePw').on('click', function(){
                var btn = $(this);
                var input = document.getElementById('userpwd'); // always fetch current (may be replaced)
                if (!input) return;

                if (input.type === 'password') {
                    input.type = 'text';
                    btn.text('Hide');
                    btn.attr('aria-pressed', 'true');
                } else {
                    input.type = 'password';
                    btn.text('Show');
                    btn.attr('aria-pressed', 'false');
                }
            });

            // util: swap input type while preserving attributes
            function changeInputType(oldObj, newType) {
                if (!oldObj || oldObj.type === newType) return oldObj;

                var newObj = document.createElement('input');
                newObj.type = newType;
                newObj.size = oldObj.size;
                newObj.name = oldObj.name;
                newObj.id = oldObj.id;
                newObj.required = true;
                newObj.value = oldObj.value;
                newObj.autocomplete = (newType === 'password') ? 'current-password' : 'on';
                newObj.className = oldObj.className;

                // replace
                oldObj.parentNode.replaceChild(newObj, oldObj);
                return newObj;
            }
        });
    </script>

</body>
</html>

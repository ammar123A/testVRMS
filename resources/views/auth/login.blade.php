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
        }

        #main {
            text-align: center;
            margin-top: 5%;
        }

        #logo {
            width: 60%;
            height: auto;
        }

        #box-login {
            width: 50%;
            margin: 2% auto;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #cccccc;
            border-radius: 5px;
        }

        .box-title {
            color: #ffffff;
            font-weight: bold;
            background-color: #2d0662;
            padding: 8px;
            font-size: 16px;
        }

        .box-content {
            padding: 15px;
        }

        .td-left {
            text-align: right;
            padding-right: 10px;
            font-weight: bold;
            white-space: nowrap;
        }

        .td-right {
            text-align: left;
        }

        #info {
            margin-left: 5px;
            color: #999999;
            font-style: italic;
            display: none;
        }

        select, input[type="text"], input[type="password"] {
            width: 100%;
            padding: 5px;
            margin: 4px 0;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        input[type="submit"], input[type="reset"] {
            padding: 5px 15px;
            margin-right: 10px;
            cursor: pointer;
        }

        .footer-text {
            text-align: center;
            font-style: italic;
            margin-top: 20px;
            color: #555;
        }

        .alert-error {
            color: red;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div id="main">
        <img id="logo" src="{{ asset('images/bannervrms.png') }}" alt="VRMS Banner">
    </div>

    <div id="box-login">
        @if(session('error'))
            <div class="alert-error" id="IdErr">{{ session('error') }}</div>
        @endif

        <div class="box-title">Login</div>
        <div class="box-content">
            <form method="POST" action="{{ route('login.custom') }}">
                @csrf
                <table cellpadding="3" cellspacing="0" border="0" width="100%">
                    <tr>
                        <td class="td-left">Login Type:</td>
                        <td class="td-right">
                            <select id="usertype" name="role">
                                <option value="STAFF">STAFF</option>
                                <option value="STUDENT">STUDENT</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-left" id="label-username">Username:</td>
                        <td class="td-right">
                            <input type="text" id="username" name="username" required>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-left" id="label-password">Password:</td>
                        <td class="td-right">
                            <input type="password" id="userpwd" name="password" required>
                            <span id="info">- eg: 901205015048</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-left">&nbsp;</td>
                        <td class="td-right">
                            <input type="submit" value="Login">
                            <input type="reset" value="Reset">
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
            $('input')[0].focus();
            $('#IdErr').delay(10000).fadeOut(1000);

            $('input[type=reset]').click(function () {
                $('#usertype').trigger('change');
            });

            $('#usertype').on('change', function () {
                var type = $(this).val();
                $('#label-username').text(type === 'STAFF' ? 'Username:' : 'Matric No:');
                $('#label-password').text(type === 'STAFF' ? 'Password:' : 'New IC/Passport No:');

                if (type === 'STAFF') {
                    $('#info').hide();
                    changeInputType(document.getElementById('userpwd'), 'password');
                } else {
                    $('#info').show();
                    changeInputType(document.getElementById('userpwd'), 'text');
                }
            }).trigger('change');

            function changeInputType(oldObj, newType) {
                var newObj = document.createElement('input');
                newObj.type = newType;
                newObj.size = oldObj.size;
                newObj.name = oldObj.name;
                newObj.id = oldObj.id;
                newObj.required = true;
                newObj.value = oldObj.value;
                oldObj.parentNode.replaceChild(newObj, oldObj);
                return newObj;
            }
        });
    </script>

</body>
</html>

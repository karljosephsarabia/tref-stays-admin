<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css" media="all">
        * {
            outline: none;
            box-sizing: inherit;
        }

        body {
            font-family: "Roboto", sans-serif;
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1.5;
            text-align: left;
            color: #464a53;
        }

        .color-dark, .text-dark {
            color: #464a53 !important;
        }

        .font-weight-bold {
            font-weight: 700 !important;
        }

        a {
            text-decoration: none;
            background-color: transparent;
        }

        .text-right {
            text-align: right !important;
        }

        .mb-0 {
            margin-bottom: 0 !important;
        }

        .mt-0 {
            margin-top: 0 !important;
        }

        p {
            margin-top: 0;
            margin-bottom: 1rem;
        }

        .card {
            margin-bottom: 30px;
            border: 0px;
            min-width: 0;
            word-wrap: break-word;
            background-clip: border-box;
        }

        .card-body {
            padding: 1.88rem 1.81rem !important;
            border: 1px solid rgba(0, 0, 0, 0.125);
            flex: 1 1 auto;
        }

        .card-title {
            font-size: 18px;
            font-weight: 500;
            line-height: 18px;
            margin-bottom: 0.75rem;
        }

        .card-header {
            background: transparent;
            padding: 0.75rem 1.25rem;
            margin-bottom: 0;
            border: 1px solid rgba(0, 0, 0, 0.125);
            border-bottom: 0;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
        }

        table {
            border-collapse: collapse;
        }

        .table td, .table th {
            border-color: #f3f3f3;
        }

        .table th, .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
            border-top-color: rgb(222, 226, 230);
        }

        th {
            text-align: inherit;
        }

        .table td {
            line-height: 1.2;
            vertical-align: middle;
        }
    </style>
</head>
<body>
@component('components.card')
    @slot('body')
        <h1 class="mt-0">{{config('app.name')}}</h1>
        <p><strong>{{trans('report.client_name')}}</strong> {{user_full_name($data['user'])}}</p>
        <p><strong>{{trans('report.summary_created')}}</strong> {{date_formatter($data['report']->created_at, 'M d, Y')}}</p>
    @endslot
@endcomponent

@component('components.income-report', ['report' => $data['report'], 'key' => 'current', 'owner_id' => $data['user']->id, 'printing' => true])
@endcomponent
</body>
</html>
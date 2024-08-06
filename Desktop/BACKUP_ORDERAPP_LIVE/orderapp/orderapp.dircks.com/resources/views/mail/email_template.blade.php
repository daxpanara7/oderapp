<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Quotation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .header {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .content {
            padding: 20px;
        }

        .content p {
            font-size: 16px;
            line-height: 1.6;
            margin: 8px 0;
        }

        .highlight {
            font-weight: bold;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .footer {
            text-align: center;
            padding: 10px;
            background-color: #f2f2f2;
            font-size: 14px;
            color: #777;
            border-radius: 0 0 8px 8px;
        }

        @media print {
            .container {
                box-shadow: none;
                border-radius: 0;
            }

            .header {
                background-color: #4CAF50;
                color: white;
            }

            th {
                background-color: #4CAF50;
                color: white;
            }

            tr:nth-child(even) {
                background-color: #f2f2f2;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Shipping Quotation from Dircks Auto Shipping</h2>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>Customer Name: <span class="highlight">{{ $name }}</span></p>
            <p>Routes: <span class="highlight">"{{$pickupAddress}}"</span> <b>To</b> <span class="highlight">"{{$deliveryAddress}}"</span></p>
            <p>Trailer Type: <span class="highlight">{{ $trailer_type == 'true' ? 'Open' : 'Enclosed' }}</span></p>

            <p>Vehicle Details:</p>
            <table>
                <tr>
                    <th>Year</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Type</th>
                    <th>INOP</th>
                </tr>
                @foreach($vehicles as $vehicle)
                <tr>
                    <td>{{$vehicle['year']}}</td>
                    <td>{{$vehicle['make']}}</td>
                    <td>{{$vehicle['model']}}</td>
                    <td>
                        @if(isset($vehicle['type']))
                        {{$vehicle['type']}}
                        @else
                        N/A
                        @endif
                    </td>
                    <td>{{ $vehicle['isInOperable'] ? 'Yes' : 'No' }}</td>
                </tr>
                @endforeach
            </table>
            <p>Estimated Carrier Price: <span class="highlight">${{$final_value}}</span></p>
            <p>Thank you for choosing Dircks Auto Shipping.</p>
            <p>Team Dircks</p>
        </div>
        <div class="footer">
            &copy; 2024 Dircks Auto Shipping. All rights reserved.
        </div>
    </div>
</body>

</html>
